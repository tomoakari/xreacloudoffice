<template>
  <div>
    <!-- 新規作成ウィンドウ -->
    <div
      class="modal_background"
      v-show="isShowCreateWindow"
      @click.self="closeCreateWindow()"
    >
      <div class="modal_window card">
        <div class="card-header">
          会議の新規作成
          <span class="closebutton" @click="closeCreateWindow()">×</span>
        </div>
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
              <tr v-for="dm in domesticMembers" v-bind:key="dm.deptId">
                <td><input type="checkbox" v-model="dm.isInvite" /></td>
                <td>{{ dm.deptName }}</td>
                <td>{{ dm.name }}</td>
                <td>{{ dm.mail }}</td>
              </tr>
            </table>
          </span>
          <span class="centerbutton" @click="sendCreateConf()">作成</span>
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
          <input type="text" v-model="detailInfo.name" />
          <p>
            開催日時
            <datetime
              v-model="detailInfo.schedule"
              type="datetime"
              format="yyyy-MM-dd HH:mm"
              :minute-step="5"
              value-zone="Asia/Tokyo"
              zone="Asia/Tokyo"
            ></datetime>
          </p>

          <span v-show="isCreateInner">
            <p>招待メンバー</p>
            <table class="newConfInvitelist">
              <tr v-for="name in detailInfo.invitelist" v-bind:key="name.index">
                <td>{{ name }}</td>
              </tr>
            </table>
          </span>
          <span class="createbutton" @click="deleteConf()">中止する</span>
          <span class="centerbutton" @click="updateConf()">更新する</span>
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
          ><i class="fas fa-sync-alt" :class="{ 'fa-spin': isInnerloading }"></i
        ></span>
        <span class="createbutton" @click="showCreateWindow(1)">新規登録</span>
      </div>

      <div class="card-body">
        <table class="meetinglist" v-show="!isMobileMode">
          <tr>
            <th>状況</th>
            <th class="datecell">日時</th>
            <th class="confnamecell">会議名</th>
            <th>作成者</th>
            <th></th>
            <th></th>
          </tr>
          <tr v-for="innerConf in innerConfs" v-bind:key="innerConf.id">
            <td>
              <span
                v-show="getStatusViewFlg(innerConf) == -1"
                style="color: #f08080"
                >中止</span
              >
              <span
                v-show="getStatusViewFlg(innerConf) == 0"
                style="color: gray"
                >予定</span
              >
              <span
                v-show="getStatusViewFlg(innerConf) == 1"
                style="color: cornflowerblue"
                >開催</span
              >
              <span
                v-show="getStatusViewFlg(innerConf) == 2"
                style="color: orange"
                >開催</span
              >
              <span
                v-show="getStatusViewFlg(innerConf) == 999"
                style="color: lightgray"
                >終了</span
              >
            </td>
            <td>{{ getJPcalendar(innerConf.schedule) }}</td>
            <td>{{ innerConf.name }}</td>
            <td>{{ innerConf.username }}</td>
            <td>
              <span
                v-show="
                  getStatusViewFlg(innerConf) !== -1 &&
                  getStatusViewFlg(innerConf) !== 999
                "
              >
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
              </span>
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
        <table class="meetinglist" v-show="isMobileMode">
          <tr>
            <th>状況</th>
            <th>日時</th>
            <th>会議名</th>
            <th></th>
          </tr>
          <tr v-for="innerConf in innerConfs" v-bind:key="innerConf.id">
            <td>
              <span
                v-show="getStatusViewFlg(innerConf) == -1"
                style="color: #f08080"
                >中止</span
              >
              <span
                v-show="getStatusViewFlg(innerConf) == 0"
                style="color: gray"
                >予定</span
              >
              <span
                v-show="getStatusViewFlg(innerConf) == 1"
                style="color: cornflowerblue"
                >開催</span
              >
              <span
                v-show="getStatusViewFlg(innerConf) == 2"
                style="color: orange"
                >開催</span
              >
              <span
                v-show="getStatusViewFlg(innerConf) == 999"
                style="color: lightgray"
                >終了</span
              >
            </td>
            <td>{{ getJPcalendar(innerConf.schedule) }}</td>
            <td>
              <span
                v-show="
                  getStatusViewFlg(innerConf) == -1 ||
                  getStatusViewFlg(innerConf) == 999
                "
              >
                {{ innerConf.name }}
              </span>
              <span
                v-show="
                  getStatusViewFlg(innerConf) !== -1 &&
                  getStatusViewFlg(innerConf) !== 999
                "
              >
                <a
                  v-bind:href="
                    'https://conference.aice.cloud/?secret=' +
                    innerConf.secret +
                    '&user_name=' +
                    user_name
                  "
                  target="_blank"
                >
                  {{ innerConf.name }}
                </a>
              </span>
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
          ><i class="fas fa-sync-alt" :class="{ 'fa-spin': isOuterloading }"></i
        ></span>
        <span class="createbutton" @click="showCreateWindow(0)">新規登録</span>
      </div>

      <div class="card-body">
        <table class="meetinglist" v-show="!isMobileMode">
          <tr>
            <th>状況</th>
            <th class="datecell">日時</th>
            <th class="confnamecell">会議名</th>
            <th>作成者</th>
            <th></th>
            <th></th>
          </tr>
          <tr v-for="outerConf in outerConfs" v-bind:key="outerConf.id">
            <td>
              <span
                v-show="getStatusViewFlg(outerConf) == -1"
                style="color: #f08080"
                >中止</span
              >
              <span
                v-show="getStatusViewFlg(outerConf) == 0"
                style="color: gray"
                >予定</span
              >
              <span
                v-show="getStatusViewFlg(outerConf) == 1"
                style="color: cornflowerblue"
                >開催</span
              >
              <span
                v-show="getStatusViewFlg(outerConf) == 2"
                style="color: orange"
                >開催</span
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
              <span
                v-show="
                  getStatusViewFlg(outerConf) !== -1 &&
                  getStatusViewFlg(outerConf) !== 999
                "
              >
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
              </span>
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
        <table class="meetinglist" v-show="isMobileMode">
          <tr>
            <th>状況</th>
            <th>日時</th>
            <th>会議名</th>
            <th></th>
          </tr>
          <tr v-for="outerConf in outerConfs" v-bind:key="outerConf.id">
            <td>
              <span
                v-show="getStatusViewFlg(outerConf) == -1"
                style="color: #f08080"
                >中止</span
              >
              <span
                v-show="getStatusViewFlg(outerConf) == 0"
                style="color: gray"
                >予定</span
              >
              <span
                v-show="getStatusViewFlg(outerConf) == 1"
                style="color: cornflowerblue"
                >開催</span
              >
              <span
                v-show="getStatusViewFlg(outerConf) == 2"
                style="color: orange"
                >開催</span
              >
              <span
                v-show="getStatusViewFlg(outerConf) == 999"
                style="color: lightgray"
                >終了</span
              >
            </td>
            <td>{{ getJPcalendar(outerConf.schedule) }}</td>
            <td>
              <span
                v-show="
                  getStatusViewFlg(outerConf) == -1 ||
                  getStatusViewFlg(outerConf) == 999
                "
                >{{ outerConf.name }}
              </span>
              <span
                v-show="
                  getStatusViewFlg(outerConf) !== -1 &&
                  getStatusViewFlg(outerConf) !== 999
                "
              >
                <a
                  v-bind:href="
                    'https://conference.aice.cloud/?secret=' +
                    outerConf.secret +
                    '&user_name=' +
                    user_name
                  "
                  target="_blank"
                  >{{ outerConf.name }}</a
                >
              </span>
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
      isMobileMode: false,
      isShowCreateWindow: false,
      isShowDetailWindow: false,
      isInnerloading: false,
      isOuterloading: false,
      newConfName: "",
      newConfDate: "",
      isCreateInner: "",
      newURL: "",
      user_name: "temp_user_name",
      detailInfo: {
        id: "",
        name: "",
        username: "",
        schedule: "",
        status: "0",
        invitelist: [""],
      },
      outerConfs: [],
      innerConfs: [],
      createParams: [],
      domesticMembers: [
        {
          isInvite: false,
          deptId: 1,
          deptName: "営業部",
          name: "松本",
          mail: "mail@mmmmmm.jp",
        },
        {
          isInvite: true,
          deptId: 0,
          deptName: "営業部",
          name: "大矢部",
          mail: "yabeeee@mmmmmm.jp",
        },
      ],
    };
  },
  props: {},
  mounted() {
    console.log("Component mounted.");
    this.getInnerConfs();
    this.getOuterConfs();
    this.getDomesticMembers();
    this.user_name = document.getElementById("login_user_name").value;
    this.isMobileMode = this.isMobile();
  },
  methods: {
    isMobile: function () {
      var userAgent = window.navigator.userAgent.toLowerCase();
      if (
        userAgent.indexOf("iphone") != -1 ||
        userAgent.indexOf("ipad") != -1 ||
        userAgent.indexOf("android") != -1 ||
        userAgent.indexOf("mobile") != -1
      ) {
        return true;
      } else {
        return false;
      }
    },
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
          this.detailInfo.schedule = this.getTs2Gmt(this.detailInfo.schedule);
        }
      });
    },
    closeDetailWindow: function () {
      this.isShowDetailWindow = false;
    },
    deleteConf: function () {
      axios
        .get("/deleteConf", {
          params: {
            id: this.detailInfo.id,
          },
        })
        .then((res) => {
          if (res) {
            Swal.fire({
              html: `<p>会議を中止しました</p>`,
              confirmButtonText: "とじる",
              confirmButtonAriaLabel: "とじる",
              allowOutsideClick: true,
            });
            this.getInnerConfs();
            this.getOuterConfs();
            this.isShowDetailWindow = false;
          } else {
            Swal.fire({
              html: `<p>処理に失敗しました</p>`,
              confirmButtonText: "とじる",
              confirmButtonAriaLabel: "とじる",
              allowOutsideClick: true,
            });
          }
        });
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
      var invitedUser = this.domesticMembers.filter((user) => {
        return user.isInvite;
      });

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
              "YYYY-MM-DD HH:mm:ss"
            ),
            invitedUser: invitedUser,
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
          this.isShowCreateWindow = false;
        });
    },
    updateConf: function () {
      axios
        .get("/updateConf", {
          params: {
            id: this.detailInfo.id,
            name: this.detailInfo.name,
            schedule: this.$moment(this.detailInfo.schedule).format(
              "YYYY-MM-DD HH:mm:ss"
            ),
          },
        })
        .then((res) => {
          if (res) {
            Swal.fire({
              html: `<p>会議情報を変更しました</p>`,
              confirmButtonText: "とじる",
              confirmButtonAriaLabel: "とじる",
              allowOutsideClick: true,
            });
            this.getInnerConfs();
            this.getOuterConfs();
            this.isShowDetailWindow = false;
          } else {
            Swal.fire({
              html: `<p>会議情報の変更に失敗しました</p>`,
              confirmButtonText: "とじる",
              confirmButtonAriaLabel: "とじる",
              allowOutsideClick: true,
            });
          }
        });
    },

    getOuterConfs: function () {
      this.isOuterloading = true;
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
        .finally(() => {
          setTimeout(() => {
            this.isOuterloading = false;
          }, 1500);
        });
    },
    getInnerConfs: function () {
      this.isInnerloading = true;
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
        .finally(() => {
          setTimeout(() => {
            this.isInnerloading = false;
          }, 1500);
        });
    },

    getDomesticMembers: function () {
      axios
        .get("/getMembers", {
          params: {},
        })
        .then((response) => {
          var enrollList = response.data.enrollList;
          var userList = response.data.userList;
          var deptList = response.data.deptList;

          var tempList = [];
          enrollList.forEach((enr) => {
            var targetUser = userList.filter((user) => {
              user.id == enr.user_id;
            });
            var targetDept = deptList.filter((dept) => {
              dept.id == enr.department_id;
            });
            var tempUser = {
              id: targetUser.id,
              name: targetUser.name,
              mail: targetUser.email,
              deptId: enr.department_id,
              deptName: targetDept.depname1,
              isInvite: false,
            };
            console.log(targetUser);
            console.log(targetDept);
            console.log(enr);

            tempList.push(tempUser);
          });

          this.domesticMembers = tempList;
        })
        .catch((err) => {
          this.domesticMembers = [];
        });
    },
    /**
     * *************************************************
     * ツール類
     * *************************************************
     */
    getJPcalendar(timestamp) {
      var youbi = ["日", "月", "火", "水", "木", "金", "土"];
      var dt = new Date(timestamp);
      // var yy = dt.getFullYear();
      var MM = (" " + (dt.getMonth() + 1)).slice(-2);
      var dd = (" " + dt.getDate()).slice(-2);
      var hh = (" " + dt.getHours()).slice(-2);
      var mm = ("0" + dt.getMinutes()).slice(-2);
      var yb = youbi[dt.getDay()];

      if (this.isMobileMode) {
        return MM + "/" + dd + " " + hh + ":" + mm;
      } else {
        return MM + "月" + dd + "日(" + yb + ") " + hh + "時" + mm + "分";
      }
    },

    getTs2Gmt(ts) {
      "0123456789012345678";
      "YYYY-MM-DD HH:mm:ss";
      var ymd = ts.substr(0, 10);
      var hms = ts.substr(11, 8);
      return ymd + "T" + hms + ".000+09:00";
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
  },
};
</script>
<style scoped>
</style>
