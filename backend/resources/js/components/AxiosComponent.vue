<template>
  <div>
    <!-- <a href="/posts" class="btn btn-primary" @click="test">DD</a>
    <button type="button" class="btn btn-primary" @click="test">ネットワーク確認</button>

    <button type="submit" class="btn btn-primary">投稿</button>
    <a href="/" class="btn btn-primary">キャンセル</a> -->
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
