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
        ユーザ情報
        <span class="createbutton disabletext">編集する</span>
      </div>

      <div class="card-body">
        <table class="menu_table">
          <tr>
            <td>ユーザID</td>
            <td>{{ userInfo.id }}</td>
          </tr>
          <tr>
            <td>メールアドレス</td>
            <td>{{ userInfo.email }}</td>
          </tr>
          <tr>
            <td>名前</td>
            <td>{{ userInfo.name }}</td>
          </tr>
        </table>
      </div>
    </div>

    <div class="card mb-20">
      <div class="card-header">
        所属情報
        <span class="createbutton disabletext">編集する</span>
      </div>

      <div class="card-body">
        <table class="menu_table">
          <tr>
            <td>企業名</td>
            <td>{{ enrollInfo.company_name }}</td>
          </tr>
          <tr>
            <td>所属部署</td>
            <td>
              {{
                enrollInfo.dept_name_1 == "設定なし"
                  ? ""
                  : enrollInfo.dept_name_1
              }}
            </td>
          </tr>
          <tr>
            <td>会社権限</td>
            <td>{{ enrollInfo.compadminflg == 1 ? "管理者" : "一般" }}</td>
          </tr>
          <tr>
            <td>部署権限</td>
            <td>{{ enrollInfo.depadminflg == 1 ? "管理者" : "一般" }}</td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      userInfo: {
        id: 0,
        name: "",
        email: "",
      },
      enrollInfo: {
        company_id: 0,
        company_name: "",
        dept_name_1: "",
        dept_id_1: 0,
        depadminflg: 0,
        compadminflg: 0,
      },
    };
  },
  props: {},
  mounted() {
    this.getUserInfo();
    this.getEnrallInfo();
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
        .get("/getUserInfo")
        .then((response) => {
          this.userInfo = response.data;
        })
        .catch((err) => {
          this.userInfo = {};
        })
        .finally();
    },
    getEnrallInfo() {
      axios
        .get("/getEnrollInfo")
        .then((response) => {
          this.enrollInfo = response.data;
        })
        .catch((err) => {
          this.enrollInfo = {};
        })
        .finally();
    },
  },
};
</script>
