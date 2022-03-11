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
                placeholder="#カテゴリ #複数可"
                name="tagCategory"
                v-model="tagCategory"
            />
        </div>

        <div v-show="show" class="form-group" id="file-preview">
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
            <button class="btn btn-primary" v-if="imageData" @click="upload()">
                決定
            </button>
            <button
                class="btn btn-danger"
                v-if="imageData"
                @click="resetFile()"
            >
                削除
            </button>
        </div>

        <div v-show="hide" class="form-group" id="file-preview">
            <img
                class="userInfo__icon"
                v-bind:src="imageData"
                v-if="imageData"
                style="width: 270px"
            />
            <button @click="resetThumbnail()">別のサムネイルを選択する</button>
        </div>

        <div class="form-group">
            <p>本文</p>
            <editor ref="toastUiEditor" height="600px" :options="options" />
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
                <option disabled selected value>選択してください</option>
                <option value="1">公開</option>
                <option value="0">非公開</option>
            </select>
        </div>

        <button type="button" class="btn btn-primary" @click="getContent">
            投稿
        </button>
        <a href="/" class="btn btn-primary">キャンセル</a>
    </div>
</template>

<script>
import "codemirror/lib/codemirror.css";
import "@toast-ui/editor/dist/toastui-editor.css";
import { Editor } from "@toast-ui/vue-editor";

export default {
    name: "CreateComponent",
    components: {
        editor: Editor,
    },
    data() {
        return {
            title: "",
            tagCategory: "",
            is_published: 0,
            content: "",
            imageData: "", //画像格納用変
            uploadFile: "",
            thumbnail: "",
            hide: false,
            show: true,

            options: {
                hooks: {
                    addImageBlobHook(blob, callback) {
                        const uploadFile = new FormData();
                        uploadFile.append("imageData", blob);
                        axios
                            .post("/images/store", uploadFile)
                            .then((res) => {
                                callback(
                                    "/storage/image/" + res.data.thumbnail,
                                    "image"
                                );
                            })
                            .catch((err) => {
                                console.log(err);
                                console.log(err.response.data);
                            });
                    },
                },
            },
        };
    },

    methods: {
        scroll() {
            this.$refs.toastUiEditor.invoke("setScrollTop", 10);
        },
        moveTop() {
            this.$refs.toastUiEditor.invoke("moveCursorToStart");
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
            this.uploadFile = "";
        },
        upload() {
            // this.imageData = "";
            const thumbnailData = new FormData();
            thumbnailData.append("imageData", this.uploadFile);
            axios
                .post("/images/store", thumbnailData)
                .then((res) => {
                    this.thumbnail = res.data.thumbnail;

                    this.hide = !this.hide;
                    this.show = !this.show; //ファイル選択ボタン
                })
                .catch((err) => {
                    console.log(err);
                    console.log(err.response.data);
                });
        },
        resetThumbnail() {
            this.show = !this.show;
            this.hide = !this.hide;
            const input = this.$refs.file;
            input.type = "text";
            input.type = "file";
            this.uploadFile = "";
            this.imageFile = "";
            this.thumbnail = "";
            this.imageData = "";
        },

        getContent() {
            if (window.confirm("投稿してよろしいですか？")) {
                const content = this.$refs.toastUiEditor.invoke("getMarkdown");

                if (content === "") {
                    alert("本文が入力されていません");
                    return;
                } else if (this.title === "") {
                    alert("タイトルが入力されていません");
                    return;
                } else if (this.title.length > 255) {
                    alert("タイトルは255文字以内にしてください");
                    return;
                } else if (this.is_published === "") {
                    alert("公開設定を選択してください");
                    return;
                }

                const data = {
                    title: this.title,
                    tagCategory: this.tagCategory,
                    thumbnail: this.thumbnail,
                    content: content,
                    is_published: this.is_published,
                };

                axios
                    .post("/posts", data)
                    .then((res) => {
                        console.log(res);
                        this.posts = res.data.posts;
                        window.location = "/";
                    })
                    .catch((err) => {
                        console.log(err);
                        console.log(err.response.data);
                    });
            } else {
                return false;
            }
        },
    },
};
</script>
