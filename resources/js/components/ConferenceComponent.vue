<template>
  <div>
    <!--
    <div class="card">
      <div class="card-header">
        内部会議一覧
        <span class="createbutton" @click="test">会議を新たに作成</span>
      </div>

      <div class="card-body">
        <table class="meetinglist">
          <tr>
            <th>ステータス</th>
            <th>日時</th>
            <th>会議名</th>
            <th>作成者</th>
            <th></th>
          </tr>
          @foreach ($innerConfs as $innerConf)
          <tr>
            <td>{{ $innerConf["status"] }}</td>
            <td>{{ $innerConf["schedule"] }}</td>
            <td>{{ $innerConf["name"] }}</td>
            <td>{{ $innerConf["username"] }}</td>
            <td>
              <a
                href="https://conference.aice.cloud/?secret={{ $innerConf['secret'] }}"
                target="_blank"
                ><span class="roominbutton">入室する</span></a
              >
            </td>
          </tr>
          @endforeach
        </table>
      </div>
    </div>
    <br />
    -->

    <div class="card">
      <div class="card-header">
        外部会議一覧
        <span class="createbutton" @click="test">会議を新たに作成</span>
      </div>

      <div class="card-body">
        <table class="meetinglist">
          <tr>
            <th>ステータス</th>
            <th>日時</th>
            <th>会議名</th>
            <th>作成者</th>
            <th></th>
          </tr>
          <tr v-for="outerConf in outerConfs" v-bind:key="outerConf.schedule">
            <td>{{ outerConf.status }}</td>
            <td>{{ outerConf.schedule }}</td>
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
          </tr>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      outerConfs: [],
    };
  },
  props: {},
  mounted() {
    console.log("Component mounted.");
    this.getOuterConfs();
  },
  methods: {
    test: function () {
      alert("test!!!");
    },
    getOuterConfs: function () {
      axios
        .get("/getOuterConfs", {
          params: {
            userId: "1",
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
  },
};
</script>
