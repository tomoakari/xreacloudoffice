<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Example Component</div>

          <div class="card-body">
            <p>{{ message }}<input v-model="message" /></p>
          </div>
          sss
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      message: "hey!!!!!!",
    };
  },
  mounted() {
    console.log("Component mounted.");
  },
  methods: {
    test: function () {
      alert("test!!!");
    },
  },
};
</script>
