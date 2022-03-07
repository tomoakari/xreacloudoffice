<template>
  <div>
    <div class="card">
      <div class="card-header">会社管理画面（会社作成済み時にのみ表示）</div>

      <div class="card-body">
        <h4>{{ admin_company_name }}</h4>
        <ul>
          <li><a href="/company/invite">従業員を作成する</a></li>
          <li><a href="">edit</a></li>
          <li><a href="">payment</a></li>
        </ul>
      </div>
    </div>
    <br />

    <div class="card">
      <div class="card-header">会社新規作成（会社未作成時にのみ表示）</div>

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
  </div>
</template>

<script>
//import Swal from "sweetalert2/dist/sweetalert2.js";
//import "sweetalert2/src/sweetalert2.scss";
export default {
  data: function () {
    return {
      show_mode: "create",
      user_id: "temp_user_id",
      company_name: "",
      max_member: 10,
      admin_company_name: "",
      createParams: [],
    };
  },
  props: {},
  mounted() {
    this.user_id = document.getElementById("login_user_id").value;
    this.getCompanyInfo();
  },
  methods: {
    setShowMode: function () {},
    createCompany: function () {
      axios
        .get("/createcompany", {
          name: this.user_name,
          plan: this.createParams[0],
        })
        .then((res) => {
          if (!res.result) {
            console.log(JSON.stringify(res.data));
          } else {
            Swal.fire({
              icon: `success`,
              html: `会社を作成しました`,
              confirmButtonText: "とじる",
            });
          }
        });
    },
    getCompanyInfo: function () {
      axios
        .get("/getCompanyInfo", {
          params: {
            user_id: this.createParams[0],
          },
        })
        .then((response) => {
          alert(response.data);
        });
    },
  },
};
</script>
