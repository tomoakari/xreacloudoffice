<template>
  <div>
    <div class="card mb-20">
      <div class="card-header">お知らせ</div>

      <div class="card-body">ようこそ、{{ user_name }}さん</div>
    </div>
    <div class="card mb-20">
      <div class="card-header">メニュー</div>

      <div class="card-body">
        <table class="menu_table">
          <tr>
            <td>
              <a href="/conference">会議管理</a>
            </td>
            <td>会議を作成したり、参加したりできます</td>
          </tr>
          <tr>
            <td>
              <a href="/company">会社管理</a>
            </td>
            <td>会社のメンバー管理などを行います</td>
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
      console.log(this.outerConfs);
      axios
        .get("/getTodayOuterConfs", {
          params: {
            //userId: "1",
          },
        })
        .then((response) => {
          this.outerConfs = response.data;
          console.log(this.outerConfs);
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
