<template>
  <div>
    <div class="card">
      <div class="card-header">
        内部会議一覧
        <span class="createbutton" @click="createConf(1)"
          >会議を新たに作成</span
        >
      </div>

      <div class="card-body">
        <table class="meetinglist">
          <tr>
            <th>ステータス</th>
            <th>日時</th>
            <th>会議名</th>
            <th>作成者</th>
            <th></th>
            <th></th>
          </tr>
          <tr v-for="innerConf in innerConfs" v-bind:key="innerConf.status">
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
        外部会議一覧
        <span class="createbutton" @click="createConf(0)"
          >会議を新たに作成</span
        >
      </div>

      <div class="card-body">
        <table class="meetinglist">
          <tr>
            <th>ステータス</th>
            <th>日時</th>
            <th>会議名</th>
            <th>作成者</th>
            <th></th>
            <th></th>
          </tr>
          <tr v-for="outerConf in outerConfs" v-bind:key="outerConf.status">
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
      outerConfs: [],
      innerConfs: [],
      createParams: [],
    };
  },
  props: {},
  mounted() {
    console.log("Component mounted.");
    this.getInnerConfs();
    this.getOuterConfs();
  },
  methods: {
    createConf: function (param) {
      Swal.fire({
        title: "会議情報の登録",
        html: `<input id="input_name" class="swal2-input" placeholder="会議名">`,
        confirmButtonText: "次へ",
        focusConfirm: false,
        preConfirm: () => {
          if (document.getElementById("input_name").value == "") {
            Swal.showValidationMessage(`会議名を入力してください`);
          } else {
            this.createParams[0] = document.getElementById("input_name").value;
          }
        },
      }).then(() => {
        Swal.fire({
          title: "開催日の登録",
          html:
            `<style>
            select{padding: 10px;}
            </style>
            <select id="input_year">
              <option value="2021">2021</option>
              <option value="2022">2022</option>
              <option value="2022">2023</option>
            </select>年　` +
            `<select id="input_month">
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
              <option value="11">11</option>
              <option value="12">12</option>
            </select>月　` +
            `<select id="input_date">
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
              <option value="11">11</option>
              <option value="12">12</option>
              <option value="13">13</option>
              <option value="14">14</option>
              <option value="15">15</option>
              <option value="16">16</option>
              <option value="17">17</option>
              <option value="18">18</option>
              <option value="19">19</option>
              <option value="20">20</option>
              <option value="21">21</option>
              <option value="22">22</option>
              <option value="23">23</option>
              <option value="24">24</option>
              <option value="25">25</option>
              <option value="26">26</option>
              <option value="27">27</option>
              <option value="28">28</option>
              <option value="29">29</option>
              <option value="30">30</option>
              <option value="31">31</option>
            </select>日<br>` +
            `<input id="input_hour" class="swal2-input" placeholder="00">時　
            <input id="input_minut" class="swal2-input" placeholder="00">分`,
          confirmButtonText: "次へ",
          focusConfirm: false,
          preConfirm: () => {
            var yy = document.getElementById("input_year").value;
            var MM = document.getElementById("input_month").value;
            var dd = document.getElementById("input_date").value;
            var hh = document.getElementById("input_hour").value;
            var mm = document.getElementById("input_minut").value;
            if (yy == "" || MM == "" || dd == "" || hh == "" || mm == "") {
              Swal.showValidationMessage(`日程を入力してください`);
            } else {
              this.createParams[1] =
                yy + "-" + MM + "-" + dd + " " + hh + ":" + dd + ":00";
            }
          },
        }).then(() => {
          if (this.createParams[0] == "" || this.createParams[1] == "") {
            axios
              .get("/createConf", {
                params: {
                  name: this.createParams[0],
                  username: "test",
                  secret: "12345678901234567890",
                  password: "pw",
                  innerflg: param,
                  status: 0,
                  schedule: this.createParams[1],
                },
              })
              .then((response) => {
                //this.outerConfs = response.data;
                Swal.fire(JSON.stringify(response.data));
              })
              .catch((err) => {
                Swal.fire(JSON.stringify(err));
              })
              .finally();
          }
        });
      });
    },
    showDetail: function (id) {
      Swal.fire({
        title: "id: " + id,
        text: "詳細情報",
        icon: "info",
        confirmButtonText: "とじる",
      });
    },
    getOuterConfs: function () {
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
    getInnerConfs: function () {
      axios
        .get("/getInnerConfs", {
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
