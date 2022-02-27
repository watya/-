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
                name="imageData"
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
            content:"",
            imageData: "", //画像格納用変
        };
    },
    methods: {
        scroll() {
            this.$refs.toastuiEditor.invoke("setScrollTop", 10);
        },
        moveTop() {
            this.$refs.toastuiEditor.invoke("moveCursorToStart");
        },

        onFileChange(e) {
            const files = e.target.files;
            if (files.length > 0) {
                this.uploadFile = files[0];
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

        getHTML() {
            if (window.confirm('投稿してよろしいですか？')) {
                const HTML = this.$refs.toastuiEditor.invoke("getHTML");

                const data = new FormData;
                data.append('imageData', this.uploadFile);
                data.append('title', this.title);
                data.append('content', HTML);
                data.append('is_published', this.is_published);
                data.append('tagCategory', this.tagCategory);
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                };
                axios
                    .post("/posts1",data,config)
                    .then((res) => {
                        console.log(res);
                        this.posts = res.data.posts;
                        window.location = "/";
                    })
                    .catch((err) => {
                        console.log(err);
                    });
            } else {
                return false;
            }
        },
    },
};
</script>
