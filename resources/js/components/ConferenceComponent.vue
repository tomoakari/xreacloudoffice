<template>
  <div>
    <div class="card">
      <div class="card-header">
        内部会議一覧
        <span class="createbutton" @click="createConf('inner')"
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
          <tr v-for="innerConf in innerConfs" v-bind:key="innerConf.id">
            <td>{{ innerConf.status }}</td>
            <td>{{ innerConf.schedule }}</td>
            <td>{{ innerConf.name }}</td>
            <td>{{ innerConf.username }}</td>
            <td>
              <span class="roominbutton" @click="showDetail(outerConf.id)"
                >詳細</span
              >
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
        <span class="createbutton" @click="createConf('outer')"
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
          <tr v-for="outerConf in outerConfs" v-bind:key="outerConf.id">
            <td>{{ outerConf.status == "0" ? "未開催" : "開催済" }}</td>
            <td>{{ outerConf.schedule.substr(0, 10) }}</td>
            <td>{{ outerConf.name }}</td>
            <td>{{ outerConf.username }}</td>
            <td>
              <span class="roominbutton" @click="showDetail(outerConf.id)"
                >詳細</span
              >
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
      Swal.fire({
        title: param,
        text: "Do you want to continue",
        icon: "error",
        confirmButtonText: "Cool",
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
