<template>
  <div>
    <input type="file" ref="file" class="form-control-file" id="exampleFormControlFile1" name="image" accept="image/*" v-on:change="onFileChange">
    <img class="userInfo__icon" v-bind:src="imageData" v-if="imageData" style="width: 270px;">
    <button class="btn btn-danger" v-if="imageData" @click="resetFile()">削除</button>
  </div>
</template>

<script>
export default {
  name: "PreviewComponent",
  el: '#file-preview',
  data: {
      imageData: '' //画像格納用変数
  },
  methods: {
    onFileChange(e) {
      const files = e.target.files;

      if(files.length > 0) {

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
      input.type = 'text';
      input.type = 'file';
      this.imageData = '';
    }
  }
}
</script>