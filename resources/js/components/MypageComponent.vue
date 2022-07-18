<template>
  <div>
    <div class="card mb-20">
      <div class="card-header">ユーザ管理</div>

      <div class="card-body">ユーザー登録情報の編集や確認を行います。</div>
    </div>
    <div class="card mb-20">
      <div class="card-header">
        <i class="fa-solid fa-user"></i> ユーザ情報
        <span class="createbutton" @click="isUserEditMode = !isUserEditMode"
          >編集する</span
        >
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
            <td>
              <span v-show="!isUserEditMode">{{ userInfo.name }}</span>
              <span v-show="isUserEditMode"
                ><input type="text" v-model="userInfo.name"
              /></span>
            </td>
          </tr>
        </table>
        <div class="centerbutton" v-show="isUserEditMode" @click="updateUser()">
          更新する
        </div>
      </div>
    </div>

    <div class="card mb-20">
      <div class="card-header">
        <i class="fa-solid fa-id-card"></i> 所属情報
        <span class="createbutton" @click="isShowEnrollWindow = true"
          >編集する</span
        >
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
                enrollInfo.dept_name_1 == "設定なし" ||
                enrollInfo.dept_name_1 == "所属設定なし"
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

    <!-- 所属編集ウィンドウ -->
    <div
      class="modal_background"
      v-show="isShowEnrollWindow"
      @click.self="isShowEnrollWindow = false"
    >
      <div class="modal_window card">
        <div class="card-header">所属情報の編集</div>
        <div class="card-body">
          <span class="createbutton">更新する</span>
        </div>
      </div>
    </div>

    <!-- ログアウト -->
    <div class="card-header">
      ログイン管理
      <div class="centerbutton" @click="logout()">ログアウト</div>
    </div>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      isUserEditMode: false,
      isShowEnrollWindow: false,
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
    updateUser() {
      axios
        .get("/updateUser", {
          params: {
            id: this.userInfo.id,
            name: this.userInfo.name,
            email: this.userInfo.email,
          },
        })
        .then((res) => {
          Swal.fire({
            icon: `success`,
            html: `ユーザ情報を更新しました`,
            confirmButtonText: "とじる",
            toast: true,
            timer: 1500,
            showConfirmButton: false,
            timerProgressBar: true,
          });
        })
        .catch(() => {
          Swal.fire({
            icon: `error`,
            html: `ユーザ情報の更新に失敗しました`,
            toast: true,
            timer: 1500,
            showConfirmButton: false,
            timerProgressBar: true,
          });
        });
    },
  },
};
</script>
