<template>
  <div>
    <!-- カード -->
    <div class="card mb-20">
      <div class="card-header">ヘルプ</div>
      <div class="card-body">
        <table class="menu_table">
          <tr>
            <td>
              <a href="#help1">組織内会議の登録方法について</a>
            </td>
            <td>
              <a href="#help2">組織外会議の登録方法について</a>
            </td>
            <td>
              <a href="#help3">組織内会議への参加方法について</a>
            </td>
            <td>
              <a href="#help4">組織外会議への参加方法について</a>
            </td>
            <td>
              <a href="#help5">登録済み会議を中止する方法を知りたい</a>
            </td>
            <td>
              <a href="#help6">ユーザー情報の確認、編集方法を知りたい</a>
            </td>
            <td>
              <a href="#help7">ログインパスワードを忘れてしまった</a>
            </td>
            <td>
              <a href="#help8">会議内の各機能について</a>
            </td>
            <td>
              <a href="#help9">メンバー（社員等）の招待方法について＊管理者</a>
            </td>
          </tr>
        </table>
      </div>
    </div>

    <div class="card mb-20" id="help1">
      <div class="card-header">組織内会議の登録方法について</div>
      <div class="card-body row">
        <div class="col-md-6">
          <img src="/image/help/1-1.jpg" />
        </div>
        <div class="col-md-6">会議情報を開きます。</div>
        <div class="col-md-6">
          <img src="/image/help/1-2.jpg" />
        </div>
        <div class="col-md-6">
          内部会議一覧にある、新規登録をクリックします。
        </div>
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
      isShowInviteWindow: false,
      show_mode: "create",
      company_secret: "",
      user_id: "temp_user_id",
      company_name: "",
      max_member: 10,
      company: {},
      createParams: [],
      userList: [
        {
          id: 0,
          name: "loading...",
          email: "loading...",
        },
      ],
      inviteMails: [""],
      inviteList: [
        {
          secret: "",
          mail: "loading...",
          created_at: "loading...",
        },
      ],
    };
  },
  props: {},
  mounted() {
    this.user_id = document.getElementById("login_user_id").value;
    this.getCompanyInfo();
  },
  methods: {
    showInviteWindow() {
      this.isShowInviteWindow = true;
    },
    closeInviteWindow() {
      this.inviteMails = [""];
      this.isShowInviteWindow = false;
    },
    sendInvite() {
      var mails = this.inviteMails.filter((mail) => {
        return mail !== "";
      });

      var errFlg = 0;
      mails.forEach((mail) => {
        if (!this.isEmail(mail)) {
          errFlg++;
        }
      });
      if (errFlg > 0) {
        Swal.fire({
          icon: `error`,
          html: `
          <p>メールアドレスの形式が間違っています</p>
          `,
          confirmButtonText: "とじる",
        });
        return false;
      }

      axios
        .get("/inviteMember", {
          params: {
            mails: mails,
          },
        })
        .then((response) => {
          if (response.data.result) {
            Swal.fire({
              icon: `success`,
              html: `招待メールを送信しました`,
              confirmButtonText: "とじる",
            });
            this.inviteMails = [""];
            this.isShowInviteWindow = false;
          } else {
            Swal.fire({
              icon: `error`,
              html: `招待メールの送信に失敗しました`,
              confirmButtonText: "とじる",
            });
            this.inviteMails = [""];
          }
        });
    },
    createCompany: function () {
      axios
        .get("/createCompany", {
          params: {
            name: this.company_name,
            plan: this.max_member,
          },
        })
        .then((res) => {
          if (res.data.result) {
            Swal.fire({
              icon: `success`,
              html: `会社を作成しました`,
              confirmButtonText: "とじる",
            });
            this.getCompanyInfo();
          } else {
            console.log(JSON.stringify(res.data));
          }
        });
    },
    getCompanyInfo: function () {
      axios
        .get("/getCompanyInfo", {
          params: {},
        })
        .then((res) => {
          console.log("getCompanyInfo..." + res.data.result);
          if (res.data.result) {
            // 会社登録済み
            this.show_mode = "detail";
            this.company = res.data.data.company_info;
            this.userList = res.data.data.company_member;
            this.inviteList = res.data.data.invited_member;
          } else {
            // 会社未登録
            this.show_mode = "create";
          }
        });
    },
    showSecret: function () {
      Swal.fire({
        icon: `success`,
        html:
          `
          <p>以下のコードを共有してください。</p>
          <p>` +
          this.company.secret +
          `</p>
          `,
        confirmButtonText: "とじる",
      });

      // alert(
      //   "こちらの８文字のコードを共有してください ： " + this.company.secret
      // );
    },
    joinCompany: function () {
      if (this.company_secret == "") return;
      axios
        .get("/joinCompany", {
          params: {
            secret: this.company_secret,
          },
        })
        .then((response) => {
          if (response.data.result) {
            Swal.fire({
              icon: `success`,
              html: `会社に参加しました`,
              confirmButtonText: "とじる",
            });
            this.getCompanyInfo();
          } else {
            Swal.fire({
              icon: `error`,
              html: `企業への参加に失敗しました`,
              confirmButtonText: "とじる",
            });
          }
        });
    },
    inviteMember: function () {
      Swal.fire({
        html: `
        <p>招待を送る</p>
        <p>
          招待する社員のメールアドレスを入力してください。<br />
          改行することで複数への招待も可能です。
        </p>
        <textarea id="mails" placeholder="example@〇〇〇〇〇.co.jp"></textarea>
        <style>
          #mails{width:100%; height:100px; overflow: scroll;}
        </style>
        `,
        confirmButtonText: "送信する",
        focusConfirm: false,
        showCancelButton: true,
        canselButtonText: "とじる",
        allowOutsideClick: false,
        preConfirm: () => {
          const mailstr = document.getElementById("mails").value;
          if (mailstr) {
            var mailArr = mailstr.split(/\n/);
            mailArr.forEach((elm) => {
              if (!this.isEmail(elm)) {
                return Swal.fire({
                  icon: `error`,
                  html: `メールアドレスの形式で入力してください`,
                  confirmButtonText: "とじる",
                });
              }
            });
          } else {
            return Swal.fire({
              icon: `error`,
              html: `メールアドレスを入力してください`,
              confirmButtonText: "とじる",
            });
          }
        },
      }).then(() => {
        var mailstr = document.getElementById("mails").value;
        var mailArr = mailstr.split(/\n/);
        axios
          .get("/inviteMember", {
            params: {
              mails: mailArr,
            },
          })
          .then((response) => {
            if (response.data.result) {
              Swal.fire({
                icon: `success`,
                html: `招待メールを送信しました`,
                confirmButtonText: "とじる",
              });
            } else {
              Swal.fire({
                icon: `error`,
                html: `招待メールの送信に失敗しました`,
                confirmButtonText: "とじる",
              });
            }
          });
      });
    },
    addInviteMails() {
      if (this.inviteMails[this.inviteMails.length + 1] !== "") {
        this.inviteMails.push("");
      }
    },
    removeInviteMails(index) {
      this.inviteMails.splice(index, 1);
    },
    showInviteUrl(secret) {
      const url = "https://kaigishitsu.aice.cloud/register/?secret=" + secret;
      Swal.fire({
        html:
          `
          <a href="` +
          url +
          `" target="_blank">` +
          url +
          `</a>
        `,
      });
    },
    isEmail(email) {
      var re =
        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email);
    },
  },
};
</script>
<style scoped>
ul.invitelist {
  list-style: none;
}
ul.invitelist > li > input {
  border: 0px;
  border-bottom: solid 1px #808080;
}
ul.invitelist > li > span {
  font-size: 20px;
  padding: 2px 5px;
  border-radius: 50%;
  cursor: pointer;
}
ul.invitelist > li > span:hover {
  border: #808080 solid 1px;
  color: #808080;
}
</style>
