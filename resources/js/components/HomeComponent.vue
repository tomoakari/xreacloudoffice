<template>
  <div>
    <div class="card mb-20">
      <div class="heroImageArea">
        <img src="/image/company_lobby.jpg" />
      </div>

      <div class="card-body">ようこそ、{{ user_name }}さん</div>
    </div>
    <div class="card mb-20">
      <div class="card-header">メニュー</div>

      <div class="card-body">
        <div class="row">
          <div class="col-md-2">
            <a href="/conference">会議情報</a>
          </div>
          <div class="col-md-10 mb-10">
            参加する会議の確認や、新たな会議の作成が出来ます。
          </div>
          <div class="col-md-2">
            <a href="/company">管理ルーム</a>
          </div>
          <div class="col-md-10 mb-10">
            新たな社員の登録や、ご利用プランの変更等、管理者用の設定が出来ます。
          </div>
          <div class="col-md-2">
            <a href="/help">ヘルプ</a>
          </div>
          <div class="col-md-10 mb-10">
            各項目の使い方を画像と共に説明します。
          </div>
        </div>
      </div>
    </div>

    <div class="card mb-20">
      <div class="card-header">
        本日の内部会議　<span class="syncbutton" @click="getTodayInnerConfs"
          ><i class="fas fa-sync-alt"></i
        ></span>
      </div>

      <div class="card-body">
        <table class="meetinglist" v-show="innerConfs.length > 0">
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
                  'https://conference.aice.cloud/?secret=' +
                  innerConf.secret +
                  '&user_name=' +
                  user_name
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
        <p v-show="innerConfs.length == 0">
          本日、予定されている会議はありません。
        </p>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        本日の外部会議　<span class="syncbutton" @click="getTodayOuterConfs"
          ><i class="fas fa-sync-alt"></i
        ></span>
      </div>

      <div class="card-body">
        <table class="meetinglist" v-show="outerConfs.length > 0">
          <tr>
            <th>状況</th>
            <th>日時</th>
            <th>会議名</th>
            <th>作成者</th>
            <th></th>
            <th></th>
          </tr>
          <tr v-for="outerConf in outerConfs" v-bind:key="outerConf.id">
            <td>
              <span
                v-show="getStatusViewFlg(outerConf) == -1"
                style="color: lightgray"
                >中止</span
              >
              <span
                v-show="getStatusViewFlg(outerConf) == 0"
                style="color: gray"
                >未開催</span
              >
              <span
                v-show="getStatusViewFlg(outerConf) == 1"
                style="color: cornflowerblue"
                >開催中</span
              >
              <span
                v-show="getStatusViewFlg(outerConf) == 2"
                style="color: orange"
                >開催中</span
              >
              <span
                v-show="getStatusViewFlg(outerConf) == 999"
                style="color: lightgray"
                >終了</span
              >
            </td>
            <td>{{ getJPcalendar(outerConf.schedule) }}</td>
            <td>{{ outerConf.name }}</td>
            <td>{{ outerConf.username }}</td>
            <td>
              <a
                v-bind:href="
                  'https://conference.aice.cloud/?secret=' +
                  outerConf.secret +
                  '&user_name=' +
                  user_name
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
        <p v-show="outerConfs.length == 0">
          本日、予定されている会議はありません。
        </p>
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
      console.log("■■■getJPcalendar");
      console.log("timestamp: " + timestamp);
      console.log("dt: " + dt);

      // var yy = dt.getFullYear();
      var MM = dt.getMonth() + 1;
      var dd = dt.getDate();
      var hh = ("00" + dt.getHours()).slice(-2);
      var mm = ("00" + dt.getMinutes()).slice(-2);
      return /*yy + "年" +*/ MM + "月" + dd + "日" + hh + "時" + mm + "分";
    },
    getStatusViewFlg(conf) {
      var st = new Date(conf.schedule);
      var end = new Date(conf.schedule);
      var end2 = new Date(conf.schedule);
      end.setHours(end.getHours() + 1);
      end2.setHours(end2.getHours() + 2);
      var now = new Date();
      var status = conf.status;

      if (status == -1) {
        return -1;
      }
      if (now < st) {
        return 0;
      }
      if (st < now && now < end) {
        return 1;
      }
      if (end < now && now < end2) {
        return 2;
      }
      if (end2 < now) {
        return 999;
      }
      console.log("no hit");
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
<style scoped>
</style>
