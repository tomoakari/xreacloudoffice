<template>
  <div>
    <div class="card mb-20">
      <div class="card-header">お知らせ</div>
      <div class="heroImageArea">
        <img src="/image/company_lobby.jpg" />
      </div>

      <div class="card-body">ようこそ、{{ user_name }}さん</div>
    </div>
    <div class="card mb-20">
      <div class="card-header">メニュー</div>

      <div class="card-body">
        <table class="menu_table">
          <tr>
            <td>
              <a href="/conference">会議情報</a>
            </td>
            <td>参加する会議の確認や、新たな会議の作成が出来ます。</td>
          </tr>
          <tr>
            <td>
              <a href="/company">管理ルーム</a>
            </td>
            <td>
              新たな社員の登録や、ご利用プランの変更等、管理者用の設定が出来ます。
            </td>
          </tr>

          <tr>
            <td>
              <a href="/help">ヘルプ</a>
            </td>
            <td>進め方や活用方法のチュートリアルや、各項目の説明はこちら。</td>
          </tr>
        </table>
      </div>
    </div>

    <div class="card mb-20">
      <div class="card-header">
        本日の内部会議　<span class="syncbutton" @click="getTodayInnerConfs"
          ><i class="fas fa-sync-alt"></i
        ></span>
      </div>

      <div class="card-body">
        <table class="meetinglist">
          <tr>
            <th>状況</th>
            <th>日時</th>
            <th>会議名</th>
            <th>作成者</th>
            <th></th>
            <th></th>
          </tr>
          <tr v-for="innerConf in innerConfs" v-bind:key="innerConf.id">
            <td>{{ innerConf.status == "0" ? "未開催" : "開催済" }}</td>
            <td>{{ getJPcalendar(innerConf.schedule) }}</td>
            <td>{{ innerConf.name }}</td>
            <td>{{ innerConf.username }}</td>
            <td>
              <a
                v-bind:href="
                  'https://conference.aice.cloud/?secret=' + innerConf.secret
                "
                target="_blank"
                ><span class="roominbutton">入室する</span></a
              >
            </td>
            <td>
              <span class="infobutton" @click="showDetail('id')"
                ><i class="far fa-edit"></i
              ></span>
            </td>
          </tr>
        </table>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        本日の外部会議　<span class="syncbutton" @click="getTodayOuterConfs"
          ><i class="fas fa-sync-alt"></i
        ></span>
      </div>

      <div class="card-body">
        <table class="meetinglist">
          <tr>
            <th>状況</th>
            <th>日時</th>
            <th>会議名</th>
            <th>作成者</th>
            <th></th>
            <th></th>
          </tr>
          <tr v-for="outerConf in outerConfs" v-bind:key="outerConf.id">
            <td>{{ outerConf.status == "0" ? "未開催" : "開催済" }}</td>
            <td>{{ getJPcalendar(outerConf.schedule) }}</td>
            <td>{{ outerConf.name }}</td>
            <td>{{ outerConf.username }}</td>
            <td>
              <a
                v-bind:href="
                  'https://conference.aice.cloud/?secret=' + outerConf.secret
                "
                target="_blank"
                ><span class="roominbutton">入室する</span></a
              >
            </td>
            <td>
              <span class="infobutton" @click="showDetail('id')"
                ><i class="far fa-edit"></i
              ></span>
            </td>
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
      user_name: "temp_user_name",
      outerConfs: [],
      innerConfs: [],
      createParams: [],
    };
  },
  props: {},
  mounted() {
    console.log("Component mounted.");
    this.getTodayInnerConfs();
    this.getTodayOuterConfs();
    this.user_name = document.getElementById("login_user_name").value;
  },
  methods: {
    showDetail: function (id) {
      Swal.fire({
        title: "id: " + id,
        text: "詳細情報",
        icon: "info",
        confirmButtonText: "とじる",
      });
    },
    getTodayOuterConfs: function () {
      axios
        .get("/getTodayOuterConfs", {
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
    getTodayInnerConfs: function () {
      axios
        .get("/getTodayInnerConfs", {
          params: {
            //userId: "1",
          },
        })
        .then((response) => {
          this.innerConfs = response.data;
        })
        .catch((err) => {
          this.innerConfs = {};
        })
        .finally();
    },
    getJPcalendar(timestamp) {
      var dt = new Date(timestamp);
      var yy = dt.getFullYear();
      var MM = dt.getMonth() + 1;
      var dd = dt.getDate();
      var hh = dt.getHours();
      var mm = dt.getMinutes();
      return /*yy + "年" +*/ MM + "月" + dd + "日" + hh + "時" + mm + "分";
    },
    getNowDates() {
      var dt = new Date();
      return {
        yy: dt.getFullYear(),
        MM: dt.getMonth() + 1,
        dd: dt.getDate(),
        hh: dt.getHours(),
        mm: dt.getMinutes(),
      };
    },
  },
};
</script>
