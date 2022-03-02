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
            <editor ref="toastuiEditor" height="500px" :initialValue= "editorText" />
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

        <button type="button" class="btn btn-primary" @click="getContent">更新</button>
        <!-- <a href="{{ route('posts.show', $post->id) }}" class="btn btn-primary">キャンセル</a> -->
    </div>
</template>

<script>
import "codemirror/lib/codemirror.css";
import "@toast-ui/editor/dist/toastui-editor.css";
import { Editor } from "@toast-ui/vue-editor";

export default {
    name: "MarkdownComponent",
    components: {
        editor : Editor,
    },
    props: {
        post: {type: Object, required: true},
        initialValue: {type: String},
    },

    postData(post) {
        this.post.id = post.id;
        this.post.title = post.title;
        this.post.content = post.content;
        this.post.tag = post.tag;
    },

    data(){
        return {
            editorText: this.post.content,
            title: this.post.title,
            tagCategory: this.post.tag,
            content: "",
            is_published: "",
            imageData: "", //画像格納用変
            uploadFile: "",
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
            this.uploadFile = "";
        },

        getContent() {
            if (window.confirm('投稿してよろしいですか？')) {
                let content = this.$refs.toastuiEditor.invoke('getMarkdown');

                if(content === ''){
                    alert('本文が入力されていません');
                    return;
                }else if(this.title === ''){
                    alert('タイトルが入力されていません');
                    return;
                }else if(this.title.length > 255){
                    alert('タイトルは255文字以内にしてください');
                    return;
                }else if(this.is_published === ''){
                    alert('公開設定を選択してください');
                    return;
                }

                const data = new FormData;
                data.append('imageData', this.uploadFile);
                data.append('title', this.title);
                data.append('content', content);
                data.append('is_published', this.is_published);
                data.append('tagCategory', this.tagCategory);

                const id = $id;
                axios
                    .put('/posts' + id, data)
                    .then(res => {
                        // alert("「" + modify.name + "」更新完了");
                        // this.$router.push({path: '/articles/list'});
                        console.log(res);
                        this.posts = res.data.posts;
                        window.location = "/";
                    })
                    .catch(error => {
                        // alert("「" + modify.name + "」更新失敗");
                        // console.log(error, id, modify);
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
