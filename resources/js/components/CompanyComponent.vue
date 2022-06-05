<template>
  <div>
    <!-- 招待ウィンドウ -->
    <div
      class="modal_background"
      v-show="isShowInviteWindow"
      @click.self="closeInviteWindow()"
    >
      <div class="modal_window card">
        <div class="card-header">
          招待を送る
          <span class="closebutton" @click="closeInviteWindow()">×</span>
        </div>
        <div class="card-body">
          <p>招待する社員のメールアドレスを入力してください。</p>
          <ul
            v-for="n in inviteMails.length"
            v-bind:key="n.index"
            class="invitelist"
          >
            <li>
              <input type="text" v-model="inviteMails[n]" />
              <span
                v-show="n > 1 && n !== inviteMails.length"
                @click="removeInviteMails(n)"
                >ー</span
              >
              <span v-show="n == inviteMails.length" @click="addInviteMails()"
                >＋</span
              >
            </li>
          </ul>
          <span class="centerbutton" @click="sendCreateConf()">送信する</span>
        </div>
      </div>
    </div>

    <!-- カード -->
    <div class="card mb-20">
      <div class="heroImageArea">
        <img src="/image/kanri.jpg" />
      </div>
      <div class="card-body">
        管理ルーム　こちらでは、新たな社員の登録、ご利用プランの変更等が行えます。
      </div>
    </div>
    <div class="card" v-show="show_mode == 'detail'">
      <div class="card-body">
        <h4>{{ company.name }}</h4>
        <ul>
          <li>
            <span class="linkbutton" @click="showInviteWindow()"
              >従業員を招待する</span
            >
          </li>
          <!--
          <li>
            <span class="linkbutton" @click="showSecret"
              >ジョインコードを表示</span
            >
          </li>
          -->
          <li class="disabletext">会社情報を編集する</li>
          <li class="disabletext">支払い情報を管理する</li>
        </ul>
      </div>
    </div>
    <br />
    <div class="card" v-show="show_mode == 'detail'">
      <div class="card-header">従業員一覧</div>

      <div class="card-body">
        <table class="userlistTable">
          <thead>
            <tr>
              <td>id</td>
              <td>名前</td>
              <td>メールアドレス</td>
            </tr>
          </thead>
          <tbody v-for="user in userList" v-bind:key="user.id">
            <tr>
              <td>{{ user.id }}</td>
              <td>{{ user.name }}</td>
              <td>{{ user.email }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <br />
    <div class="card" v-show="show_mode == 'detail'">
      <div class="card-header">招待済み一覧</div>

      <div class="card-body">
        <table class="invitedTable">
          <thead>
            <tr>
              <td>メールアドレス</td>
              <td>招待日</td>
              <td>招待URL</td>
            </tr>
          </thead>
          <tbody
            v-for="secret in inviteList"
            v-bind:key="secret.id"
            class="invitedTable"
          >
            <tr>
              <td>{{ secret.mail }}</td>
              <td>{{ secret.created_at }}</td>
              <td @click="showInviteUrl(secret.secret)" class="linkbutton">
                URLを表示
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <br />

    <div class="card" v-show="show_mode == 'create'">
      <div class="card-header">会社新規作成</div>

      <div class="card-body">
        <p>組織名を登録しましょう</p>
        <input v-model="company_name" />

        <p>ご利用の人数は何人ですか？</p>
        <p>
          <input type="radio" id="1_5" value="5" v-model="max_member" />
          <label for="1-5">1~5人</label>
        </p>
        <p>
          <input type="radio" id="6-10" value="10" v-model="max_member" />
          <label for="6-10">6~10人</label>
        </p>
        <p>
          <input type="radio" id="11-20" value="20" v-model="max_member" />
          <label for="11-20">11~20人</label>
        </p>
        <p>
          <input type="radio" id="21-50" value="50" v-model="max_member" />
          <label for="21-50">21~50人</label>
        </p>

        <p>決済方法の登録</p>
        <select>
          <option>現在は選択不要です</option>
        </select>
        <div class="button" @click="createCompany">企業を作成する</div>
      </div>
    </div>

    <!--
    <div class="card" v-show="show_mode == 'create'">
      <div class="card-header">ジョインコードで企業に参加する</div>

      <div class="card-body">
        <h3>ジョインコード</h3>
        <input v-model="company_secret" />
        <div class="button" @click="joinCompany">企業に参加する</div>
      </div>
    </div>
    -->
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
      this.isShowInviteWindow = false;
    },
    sendInvite() {
      var errFlg = 0;
      this.inviteMails.forEach((mail) => {
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
      }
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
