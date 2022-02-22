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

        <div class="form-group" id="file-preview">
            <label for="exampleFormControlFile1">サムネイル</label>
            <input
                type="file"
                ref="file"
                class="form-control-file"
                id="exampleFormControlFile1"
                name="image"
                accept="image/*"
                v-on:change="onFileChange"
            />
            <img
                class="userInfo__icon"
                v-bind:src="imageData"
                v-if="imageData"
                style="width: 270px"
            />
            <button
                class="btn btn-danger"
                v-if="imageData"
                @click="resetFile()"
            >
                削除
            </button>
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
            imageData: "", //画像格納用変数
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
                    title: this.title,
                    tagCategory: this.tagCategory,
                    contents: HTML,
                    is_published: this.is_published,
                    imageData:this.imageData,
                })
                .then((res) => {
                    console.log(res);
                    this.posts = res.data.posts;
                })
                .catch((err) => {
                    console.log(err);
                });
        },

        onFileChange(e) {
            const files = e.target.files;
            if (files.length > 0) {
                const file = files[0];
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imageData = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        resetFile() {
            const input = this.$refs.file;
            input.type = "text";
            input.type = "file";
            this.imageData = "";
        },
    },
};
</script>
