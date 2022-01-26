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
        本日の内部会議　<span @click="getInnerConfs"
          ><i class="fas fa-sync-alt"></i
        ></span>
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
        本日の外部会議　<span @click="getOuterConfs"
          ><i class="fas fa-sync-alt"></i
        ></span>
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
      user_name: "temp_user_name",
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
    createConf: function (param) {
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
              <option value="2021">2021</option>
              <option value="2022">2022</option>
              <option value="2022">2023</option>
            </select>年　` +
            `<select id="input_month">
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
            </select>日<br><br>` +
            `<select id="input_noon">
              <option value="AM">AM</option>
              <option value="PM">PM</option>
            </select>　　` +
            `<select id="input_hour">
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
            </select>時　` +
            `<select id="input_minut">
              <option value="00">00</option>
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
            var nn = document.getElementById("input_noon").value;
            if (nn == "PM") {
              hh = hh + 12;
            }
            var mm = document.getElementById("input_minut").value;
            console.log(yy + "-" + MM + "-" + dd + " " + hh + ":" + dd + ":00");
            if (yy == "" || MM == "" || dd == "" || hh == "" || mm == "") {
              Swal.showValidationMessage(`日程を入力してください`);
            } else {
              this.createParams[1] =
                yy + "-" + MM + "-" + dd + " " + hh + ":" + dd + ":00";
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
                    }).then((result) => {
                      if (result.value) {
                        copyToClipboard();
                      }
                    });
                    /*
                    Swal.fire({
                      title: "参加者を招待する",
                      html: `
                        <input name="input_invite" class="swal2-input" placeholder="メールアドレス">
                        <input name="input_invite" class="swal2-input" placeholder="メールアドレス">
                        <input name="input_invite" class="swal2-input" placeholder="メールアドレス">
                        <input name="input_invite" class="swal2-input" placeholder="メールアドレス">
                        <input name="input_invite" class="swal2-input" placeholder="メールアドレス">
                        <input name="input_invite" class="swal2-input" placeholder="メールアドレス">
                        <input name="input_invite" class="swal2-input" placeholder="メールアドレス">
                        <input name="input_invite" class="swal2-input" placeholder="メールアドレス">
                        `,
                      confirmButtonText: "送信する",
                      focusConfirm: false,
                      showCancelButton: true,
                      cancelButtonText: "今は招待しない",
                      allowOutsideClick: false,
                      preConfirm: () => {
                        var invites = document.getElementsByName("input_invite");
                        var isInvited = false;
                        invites.forEach(iu => {
                          if(iu !== ""){
                            isInvited = true;
                            break;
                          }
                        });
                        if(isInvited){
                          return false;
                        }
                        if (document.getElementsByName("input_invite") == "") {
                          Swal.showValidationMessage(
                            `会議名を入力してください`
                          );
                        } else {
                          this.createParams[0] =
                            document.getElementById("input_name").value;
                        }
                      },
                    });
                    */
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
