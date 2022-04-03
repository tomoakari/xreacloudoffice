<template>
  <div>
    <div class="card mb-20">
      <div class="card-header">
        ユーザ管理
        <span class="createbutton" @click="logout()">ログアウト</span>
      </div>

      <div class="card-body">自分の登録情報を確認・編集できます</div>
    </div>
    <div class="card mb-20">
      <div class="card-header">
        基本情報
        <span class="createbutton">編集する</span>
      </div>

      <div class="card-body">
        <table class="menu_table">
          <tr>
            <td>ユーザID</td>
            <td>xxxxxxxxxxx</td>
          </tr>
          <tr>
            <td>メールアドレス</td>
            <td>xxxxxxxxxxx</td>
          </tr>
          <tr>
            <td>名前</td>
            <td>xxxxxxxxxxx</td>
          </tr>
        </table>
      </div>
    </div>

    <div class="card mb-20">
      <div class="card-header">
        詳細情報
        <span class="createbutton">編集する</span>
      </div>

      <div class="card-body">
        <table class="menu_table">
          <tr>
            <td>企業名</td>
            <td>xxxxxxxxxxx</td>
          </tr>
          <tr>
            <td>所属部署</td>
            <td>xxxxxxxxxxx</td>
          </tr>
          <tr>
            <td>システム権限</td>
            <td>xxxxxxxxxxx</td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
//import Swal from "sweetalert2/dist/sweetalert2.js";
//import "sweetalert2/src/sweetalert2.scss";
export default {
  data: function () {
    return {
      userInfo: [],
      companyInfo: [],
    };
  },
  props: {},
  mounted() {
    //this.getUserInfo();
    //this.getCompanyInfo();
  },
  methods: {
    logout() {
      event.preventDefault();
      document.getElementById("logout-form").submit();
      axios.post("https://conference.aice.cloud/logout", {
        user_name: this.user_name,
        room_name: this.createParams[0],
      });
    },
    getUserInfo() {
      axios
        .get("/getOuterConfs", {
          params: {
            //userId: "1",
          },
        })
        .then((response) => {
          this.outerConfs = response.data;
        })
        .catch((err) => {
          this.outerConfs = {};
        })
        .finally();
    },
  },
};
</script>
