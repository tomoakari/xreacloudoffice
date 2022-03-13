<template>
  <div>
    <div class="card" v-show="show_mode == 'detail'">
      <div class="card-header">会社管理画面</div>

      <div class="card-body">
        <h4>{{ company.name }}</h4>
        <ul>
          <li><a href="" @click="showSecret">従業員を招待する</a></li>
          <li><a href="/company/invite">従業員を作成する</a></li>
          <li><a href="">会社情報を編集する</a></li>
          <li><a href="">支払い情報を管理する</a></li>
        </ul>
      </div>
    </div>
    <br />
    <div class="card" v-show="show_mode == 'detail'">
      <div class="card-header">従業員一覧</div>

      <div class="card-body">
        <ul>
          <li>＊＊＊＊＊</li>
          <li>＊＊＊＊＊</li>
          <li>＊＊＊＊＊</li>
        </ul>
      </div>
    </div>
    <br />

    <div class="card" v-show="show_mode == 'create'">
      <div class="card-header">会社新規作成</div>

      <div class="card-body">
        <h3>組織名を登録しましょう</h3>
        <input v-model="company_name" />

        <h3>ご利用の人数は何人ですか？</h3>
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

        <h3>決済方法の登録（飛ばして新規登録します）</h3>
        <div class="button" @click="createCompany">企業を作成する</div>
      </div>
    </div>

    <div class="card" v-show="show_mode == 'create'">
      <div class="card-header">招待コードで企業に参加する</div>

      <div class="card-body">
        <h3>企業招待ID</h3>
        <input v-model="company_secret" />
        <div class="button" @click="joinCompany">企業に参加する</div>
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
      show_mode: "create",
      company_secret: "",
      user_id: "temp_user_id",
      company_name: "",
      max_member: 10,
      company: {},
      createParams: [],
    };
  },
  props: {},
  mounted() {
    this.user_id = document.getElementById("login_user_id").value;
    this.getCompanyInfo();
  },
  methods: {
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
        .then((response) => {
          if (response.data.result) {
            // 会社登録済み
            this.show_mode = "detail";
            this.company = response.data.data;
          } else {
            // 会社未登録
            this.show_mode = "create";
          }
        });
    },
    showSecret: function () {
      /* なんか誤動作するので一旦スルー
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
      */
      alert(
        "こちらの８文字のコードを共有してください ： " + this.company.secret
      );
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
  },
};
</script>
