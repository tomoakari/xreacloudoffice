<template>
  <div>
    <!-- 新規作成ウィンドウ -->
    <div
      class="modal_background"
      v-show="isShowCreateWindow"
      @click.self="closeCreateWindow()"
    >
      <div class="modal_window card">
        <div class="card-header">会議の新規作成</div>
        <div class="card-body">
          <p>会議名</p>
          <input type="text" v-model="newConfName" />
          <p>開催日時</p>
          <datetime
            v-model="newConfDate"
            type="datetime"
            format="yyyy-MM-dd HH:mm"
            :minute-step="5"
            value-zone="Asia/Tokyo"
            zone="Asia/Tokyo"
          ></datetime>
          <span v-show="isCreateInner">
            <p>招待メンバー</p>
            <table class="newConfInvitelist">
              <tr>
                <td><input type="checkbox" /></td>
                <td>名前（仮）</td>
                <td>メールアドレス（仮）</td>
              </tr>
              <tr>
                <td><input type="checkbox" /></td>
                <td>名前（仮）</td>
                <td>メールアドレス（仮）</td>
              </tr>
              <tr>
                <td><input type="checkbox" /></td>
                <td>名前（仮）</td>
                <td>メールアドレス（仮）</td>
              </tr>
            </table>
          </span>
          <span class="centerbutton" @click="sendCreateConf()">送信</span>
          <span v-show="newURL !== ''">
            <p>会議室のURLはこちら</p>
            <input type="text" v-model="newURL" />
            <a v-bind:href="newURL" target="_blank">
              <span class="centerbutton">いますぐ入室</span>
            </a>
          </span>
        </div>
      </div>
    </div>

    <!-- 会議詳細ウィンドウ -->
    <div
      class="modal_background"
      v-show="isShowDetailWindow"
      @click.self="closeDetailWindow()"
    >
      <div class="modal_window card">
        <div class="card-header">会議の詳細</div>
        <div class="card-body">
          <p>会議名</p>
          {{ detailInfo.name }}
          <p>開催日時</p>
          {{ getJPcalendar(detailInfo.schedule) }}
          <span v-show="isCreateInner">
            <p>招待メンバー</p>
            <table class="newConfInvitelist">
              <tr v-for="name in detailInfo.invitelist" v-bind:key="name.index">
                <td>{{ name }}</td>
              </tr>
            </table>
          </span>
          <span class="createbutton" @click="deleteConf()">削除する</span>
        </div>
      </div>
    </div>

    <!-- カード部 -->
    <div class="card mb-20">
      <div class="heroImageArea">
        <img src="/image/conferenceroom.jpg" />
      </div>
      <div class="card-body">
        会議情報　参加する会議の日時確認や、新たな会議の作成が行えます。
      </div>
    </div>
    <div class="card mb-20">
      <div class="card-header">
        内部会議一覧　<span class="syncbutton" @click="getInnerConfs"
          ><i class="fas fa-sync-alt"></i
        ></span>
        <span class="createbutton" @click="showCreateWindow(1)">新規登録</span>
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
              <span
                class="infobutton"
                @click="showDetailWindow(1, innerConf.id)"
                ><i class="far fa-edit"></i
              ></span>
            </td>
          </tr>
        </table>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        外部会議一覧　<span class="syncbutton" @click="getOuterConfs"
          ><i class="fas fa-sync-alt"></i
        ></span>
        <span class="createbutton" @click="showCreateWindow(0)">新規登録</span>
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
              <span
                class="infobutton"
                @click="showDetailWindow(0, outerConf.id)"
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
import "vue-datetime/dist/vue-datetime.css";
export default {
  data: function () {
    return {
      isShowCreateWindow: false,
      isShowDetailWindow: false,
      newConfName: "",
      newConfDate: "",
      isCreateInner: "",
      newURL: "",
      user_name: "temp_user_name",
      detailInfo: {
        name: "",
        username: "",
        schedule: "",
        status: "0",
        invitelist: [""],
      },
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
    this.user_name = document.getElementById("login_user_name").value;
  },
  methods: {
    showCreateWindow: function (innerflg) {
      this.isCreateInner = innerflg;
      this.isShowCreateWindow = true;
    },
    closeCreateWindow: function () {
      this.isShowCreateWindow = false;
      this.newURL = "";
      this.newConfDate = "";
      this.newConfName = "";
    },
    showDetailWindow: function (innerflg, id) {
      this.isShowDetailWindow = true;
      var confarr;
      if (innerflg) {
        confarr = this.innerConfs;
      } else {
        confarr = this.outerConfs;
      }
      confarr.forEach((elm) => {
        if (elm.id == id) {
          this.detailInfo = elm;
        }
      });
    },
    closeDetailWindow: function () {
      this.isShowDetailWindow = false;
    },
    deleteConf: function () {
      alert("さくじょ");
    },
    sendCreateConf: function () {
      this.generateNewConf();
    },
    generateNewConf: function () {
      axios
        .post("https://conference.aice.cloud/apicreate", {
          user_name: this.user_name,
          room_name: this.newConfName,
        })
        .then((res) => {
          this.createNewConf(res.data.secret);
        })
        .catch((err) => {
          Swal.fire({
            html: `<p>会議を作成することができませんでした。： ` + err + `</p>`,
            confirmButtonText: "とじる",
            confirmButtonAriaLabel: "とじる",
            allowOutsideClick: true,
          });
        });
    },
    createNewConf: function (secret) {
      axios
        .get("/createConf", {
          params: {
            name: this.newConfName,
            username: this.user_name,
            secret: secret,
            password: "pw",
            innerflg: this.isCreateInner,
            status: 0,
            schedule: this.$moment(this.newConfDate).format(
              "YYYY-MM-DD hh:mm:ss"
            ),
          },
        })
        .then((response) => {
          if (response.data.length == 0) {
            return Swal.fire({
              html: `<p>会社に入っていない状態では、会議を作成することはできません。</p>`,
              confirmButtonText: "とじる",
              confirmButtonAriaLabel: "とじる",
              allowOutsideClick: true,
            });
          }
          this.newURL =
            "https://conference.aice.cloud/?secret=" + response.data.secret;
          this.getInnerConfs();
          this.getOuterConfs();
        });
    },

    createConf_old: function (param) {
      Swal.fire({
        title: "会議情報の登録",
        html: `<input id="input_name" class="swal2-input" placeholder="会議名">`,
        confirmButtonText: "次へ",
        focusConfirm: false,
        showCancelButton: true,
        canselButtonText: "とじる",
        allowOutsideClick: false,
        preConfirm: () => {
          if (document.getElementById("input_name").value == "") {
            Swal.showValidationMessage(`会議名を入力してください`);
          } else {
            this.createParams[0] = document.getElementById("input_name").value;
          }
        },
      }).then((result) => {
        if (!result.isConfirmed) {
          return false;
        }
        Swal.fire({
          title: "開催日時の登録",
          html:
            `<style>
            select{padding: 10px; width: 90px;}
            </style>
            <select id="input_year">
              <option value="2022">2022</option>
              <option value="2022">2023</option>
            </select>年　` +
            `<select id="input_month">
              <option value="01">1</option>
              <option value="02">2</option>
              <option value="03">3</option>
              <option value="04">4</option>
              <option value="05">5</option>
              <option value="06" selected>6</option>
              <option value="07">7</option>
              <option value="08">8</option>
              <option value="09">9</option>
              <option value="10">10</option>
              <option value="11">11</option>
              <option value="12">12</option>
            </select>月　` +
            `<select id="input_date">
              <option value="01">1</option>
              <option value="02">2</option>
              <option value="03">3</option>
              <option value="04">4</option>
              <option value="05">5</option>
              <option value="06">6</option>
              <option value="07">7</option>
              <option value="08">8</option>
              <option value="09">9</option>
              <option value="10">10</option>
              <option value="11">11</option>
              <option value="12">12</option>
              <option value="13">13</option>
              <option value="14">14</option>
              <option value="15" selected>15</option>
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
            </select>日<br><br>` +
            `<select id="input_hour">
              <option value="0">0</option>
              <option value="01">1</option>
              <option value="02">2</option>
              <option value="03">3</option>
              <option value="04">4</option>
              <option value="05">5</option>
              <option value="06">6</option>
              <option value="07">7</option>
              <option value="08">8</option>
              <option value="09">9</option>
              <option value="10">10</option>
              <option value="11">11</option>
              <option value="12" selected>12</option>
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
            </select>時　` +
            `<select id="input_minut">
              <option value="00" selected>00</option>
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="30">30</option>
              <option value="40">40</option>
              <option value="50">50</option>
            </select>分`,
          confirmButtonText: "次へ",
          showCancelButton: true,
          canselButtonText: "とじる",
          allowOutsideClick: false,
          focusConfirm: false,
          preConfirm: () => {
            var yy = document.getElementById("input_year").value;
            var MM = document.getElementById("input_month").value;
            var dd = document.getElementById("input_date").value;
            var hh = document.getElementById("input_hour").value;
            var mm = document.getElementById("input_minut").value;
            console.log(yy + "-" + MM + "-" + dd + " " + hh + ":" + mm + ":00");
            if (yy == "" || MM == "" || dd == "" || hh == "" || mm == "") {
              Swal.showValidationMessage(`日程を入力してください`);
            } else {
              this.createParams[1] =
                yy + "-" + MM + "-" + dd + " " + hh + ":" + mm + ":00";
            }
          },
        }).then((result) => {
          if (!result.isConfirmed) {
            return false;
          }
          if (this.createParams[0] !== "" || this.createParams[1] !== "") {
            var scrt = "defaultsecret";
            axios
              .post("https://conference.aice.cloud/apicreate", {
                user_name: this.user_name,
                room_name: this.createParams[0],
              })
              .then((res) => {
                scrt = res.data.secret;

                axios
                  .get("/createConf", {
                    params: {
                      name: this.createParams[0],
                      username: this.user_name,
                      secret: scrt,
                      password: "pw",
                      innerflg: param,
                      status: 0,
                      schedule: this.createParams[1],
                    },
                  })
                  .then((response) => {
                    if (response.data.length == 0) {
                      return Swal.fire({
                        html: `<p>会社に入っていない状態では、会議を作成することはできません。</p>`,
                        confirmButtonText: "とじる",
                        confirmButtonAriaLabel: "とじる",
                        allowOutsideClick: true,
                      });
                    }
                    this.getInnerConfs();
                    this.getOuterConfs();
                    const url =
                      "https://conference.aice.cloud/?secret=" +
                      response.data.secret;
                    Swal.fire({
                      title: "会議室のURLはこちら",
                      html:
                        `<input id="copyTarget" class="linkinputtext" value="` +
                        url +
                        `"/>` +
                        `<style>
                          h2, p{color: #ffffff; font-size: 14px;}
                          .linkinputtext {
                            width: 90%;
                            font-size: 16px;
                            padding: 10px 10px;
                          }
                        </style>`,
                      focusConfirm: false,
                      confirmButtonText: "コピー",
                      confirmButtonAriaLabel: "Close",
                      allowOutsideClick: true,
                      preConfirm: () => {
                        navigator.clipboard.writeText(url);
                      },
                    }).then((result) => {
                      if (result.isConfirmed) {
                        Swal.fire({
                          icon: "success",
                          title: "クリップボードにコピーしました",
                          toast: true,
                          timer: 2500,
                          timerProgressBar: true,
                          showConfirmButton: false,
                        });
                      }
                    });
                  })
                  .catch((err) => {
                    Swal.fire(JSON.stringify(err));
                  })
                  .finally();
              });
          }
        });
      });
    },
    showDetail: function (innerflg, id) {
      var targetConf;
      var confarr;
      if (innerflg) {
        confarr = this.innerConfs;
      } else {
        confarr = this.outerConfs;
      }
      confarr.forEach((elm) => {
        if (elm.id == id) {
          targetConf = elm;
        }
      });

      axios
        .get("/getConferenceInfo", {
          params: {
            id: targetConf,
          },
        })
        .then((response) => {
          if (response.data.result) {
            this.company = response.data.data;
          } else {
            // 会社未登録
            this.show_mode = "create";
          }
        });

      console.log("mmm" + targetConf);
      Swal.fire({
        title: "詳細情報",
        html:
          `
        <table>
          <tr><td>会議名</td><td>` +
          targetConf.name +
          `</td></tr>
          <tr><td>参加者</td><td>` +
          "xxxx, xxxx, xxxx" +
          `</td></tr>
        </table>
        `,
        text: JSON.stringify(targetConf),
        confirmButtonText: "削除する",
        showCancelButton: true,
        cancelButtonText: "とじる",
      }).then((res) => {
        if (res.isConfirmed) {
          alert("削除処理");
        }
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
<style scoped>
.modal_background {
  background: rgba(0, 0, 0, 0.6);
  width: 100vw;
  height: 100vh;
  position: fixed;
  z-index: 100;
  top: 0;
  left: 0;
}
.modal_window {
  margin-left: auto;
  margin-right: auto;
  margin-top: 50px;
  width: 80%;
}
</style>
