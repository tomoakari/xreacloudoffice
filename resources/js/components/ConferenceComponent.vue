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
            <td>{{ innerConf.schedule.substr(0, 16) }}</td>
            <td>{{ innerConf.name }}</td>
            <td>{{ innerConf.username }}</td>
            <td>
              <span class="roominbutton" @click="showDetail('id')">詳細</span>
            </td>
            <td>
              <a
                v-bind:href="
                  'https://conference.aice.cloud/?secret=' + innerConf.secret
                "
                target="_blank"
                ><span class="roominbutton">入室する</span></a
              >
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
            <td>{{ outerConf.schedule.substr(0, 16) }}</td>
            <td>{{ outerConf.name }}</td>
            <td>{{ outerConf.username }}</td>
            <td>
              <span class="roominbutton" @click="showDetail('id')">詳細</span>
            </td>
            <td>
              <a
                v-bind:href="
                  'https://conference.aice.cloud/?secret=' + outerConf.secret
                "
                target="_blank"
                ><span class="roominbutton">入室する</span></a
              >
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
      const { value: formValues } = Swal.fire({
        title: param == 1 ? "内部" : "外部" + "会議を作成する",
        html:
          `<input id="input_name" class="swal2-input" placeholder="会議名">` +
          `<input id="input_schedule" class="swal2-input" placeholder="開催日（2021-04-20 09:30:00）">`,
        confirmButtonText: "送信",
        focusConfirm: false,
        preConfirm: () => {
          //var input_name = document.getElementById("input_name").value;
          //var input_schedule = document.getElementById("input_schedule").value;
          return [
            document.getElementById("swal-input1").value,
            document.getElementById("swal-input2").value,
          ];
        },
      }).then(() => {
        if (formValues) {
          axios
            .get("/createConf", {
              params: {
                name: formValues[0],
                username: "",
                secret: "",
                password: "",
                innerflg: param,
                status: 0,
                schedule: formValues[1],
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
  },
};
</script>
