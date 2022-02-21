<template>
    <div>
        <div class="form-group">
            <label for="exampleInputEmail1">タイトル</label>
            <input
                type="text"
                class="form-control"
                id="exampleInputEmail1"
                placeholder="title"
                name="title"
                v-model="title"
            />
        </div>

        <div class="form-group">
            <label for="exampleInputEmail1">カテゴリ</label>
            <input
                type="text"
                class="form-control"
                placeholder="category"
                name="tagCategory"
                v-model="tagCategory"
            />
        </div>

        <div class="form-group">
            <p>本文</p>
            <editor ref="toastuiEditor" />
        </div>

        <div class="form-group">
            <label for="exampleFormControlSelect1">公開設定</label>
            <select
                input
                type="id"
                id="exampleFormControlSelect1"
                name="is_published"
                v-model="is_published"
            >
                <option value="1">公開</option>
                <option value="0">非公開</option>
            </select>
        </div>

        <button type="button" @click="getHTML">保存</button>

        <a href="/posts" class="btn btn-primary" @click="getHTML">DD</a>

        <button type="submit" class="btn btn-primary">投稿</button>
        <a href="/" class="btn btn-primary">キャンセル</a>
    </div>
</template>

<script>
import "codemirror/lib/codemirror.css";
import "@toast-ui/editor/dist/toastui-editor.css";
import { Editor } from "@toast-ui/vue-editor";

export default {
    name: "MarkdownComponent",
    components: {
        editor: Editor,
    },
    data() {
      return {
        title: "",
        tagCategory: "",
        is_published: "",
      };
    },
    methods: {
        scroll() {
            this.$refs.toastuiEditor.invoke("setScrollTop", 10);
        },
        moveTop() {
            this.$refs.toastuiEditor.invoke("moveCursorToStart");
        },
        getHTML() {
            let HTML = this.$refs.toastuiEditor.invoke("getHTML");
            axios
                .post("/posts", {
                    contents: HTML,
                    title: this.title,
                    tagCategory: this.tagCategory,
                    is_published: this.is_published,
                })
                .then((res) => {
                    console.log(res);
                    this.posts = res.data.posts;
                })
                .catch((err) => {
                    console.log(err);
                });
        },

    },
};
</script>
