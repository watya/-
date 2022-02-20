<template>
  <div>
    <div class="form-group">
        <label for="exampleInputEmail1">タイトル</label>
        <input type="text" class="form-control" id="exampleInputEmail1" placeholder="title" name="title" v-model="title">
    </div>

    <div class="form-group">
        <label for="exampleInputEmail1">カテゴリ</label>
        <input type="text" class="form-control" placeholder="category" name="tagCategory" v-model="tagCategory">
    </div>

    <div class="form-group">
        <p>本文</p>
        <markdown-component></markdown-component>
    </div>

    <div class="form-group">
        <label for="exampleFormControlSelect1">公開設定</label>
        <select input type="id" id="exampleFormControlSelect1" name="is_published" v-model="is_published">
        <option value="1">公開</option>
        <option value="0">非公開</option>
        </select>
    </div>

    <a href="/posts" class="btn btn-primary" @click="test">DD</a>
    <button type="button" class="btn btn-primary" @click="test">ネットワーク確認</button>

    <button type="submit" class="btn btn-primary">投稿</button>
    <a href="/" class="btn btn-primary">キャンセル</a>
  </div>
</template>

<script>
  export default {
    name: "AxiosComponent",
    data() {
      return {
        title: "",
        content: "",
        tagCategory: "",
        is_published: "",
      };
    },
    methods: {
      test: function () {
        axios
          .post("/posts", {
            title: this.title,
            content: this.content,
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
