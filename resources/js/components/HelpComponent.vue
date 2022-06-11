<template>
  <div>
    <!-- フロートボタン -->
    <div class="right_bottom">
      <a href="#topindex">
        <div class="fab darkgray">
          <i class="fa-solid fa-chevron-up"></i>
        </div>
      </a>
    </div>
    <!-- カード -->
    <div class="card mb-20" id="topindex">
      <div class="card-header">ヘルプ</div>
      <div class="card-body">
        <p>各項目の使い方を画像と共に説明します。</p>
        <p class="helptitle">一般ユーザ向け</p>
        <table class="menu_table mb-20">
          <tr>
            <td>
              <a href="#help1">組織内会議の登録方法について</a>
            </td>
          </tr>
          <tr>
            <td>
              <a href="#help2">組織外会議の登録方法について</a>
            </td>
          </tr>
          <tr>
            <td>
              <a href="#help3">組織内会議への参加方法について</a>
            </td>
          </tr>
          <tr>
            <td>
              <a href="#help4">組織外会議への参加方法について</a>
            </td>
          </tr>
          <tr>
            <td>
              <a href="#help5">登録済み会議を中止する方法を知りたい</a>
            </td>
          </tr>
          <tr>
            <td>
              <a href="#help6">ユーザー情報の確認、編集方法を知りたい</a>
            </td>
          </tr>
          <tr>
            <td>
              <a href="#help7">ログインパスワードを忘れてしまった</a>
            </td>
          </tr>
          <tr>
            <td>
              <a href="#help8">会議内の各機能について</a>
            </td>
          </tr>
        </table>

        <p class="helptitle">管理者ユーザ向け</p>
        <table class="menu_table mb-20">
          <tr>
            <td>
              <a href="#help9">メンバー（社員等）の招待方法について</a>
            </td>
          </tr>
        </table>
      </div>
    </div>

    <div class="card mb-20" id="help1">
      <div class="card-header">組織内会議の登録方法について</div>
      <div class="card-body row">
        <div class="col-md-6 mb-10">
          <img src="/image/help/1-1.jpg" class="helpimage" />
        </div>
        <div class="col-md-6 mb-10">会議情報を開きます。</div>
        <div class="col-md-6 mb-10">
          <img src="/image/help/1-2.jpg" class="helpimage" />
        </div>
        <div class="col-md-6 mb-10">
          内部会議一覧にある、新規登録をクリックします。
        </div>
        <div class="col-md-6 mb-10">
          <img src="/image/help/1-3.jpg" class="helpimage" />
        </div>
        <div class="col-md-6 mb-10">
          会議名、開催日時、招待メンバーを選択し、作成します。
        </div>
      </div>
    </div>

    <div class="card mb-20" id="help2">
      <div class="card-header">組織外会議の登録方法について</div>
      <div class="card-body row">
        <div class="col-md-6 mb-10">
          <img src="/image/help/2-1.jpg" class="helpimage" />
        </div>
        <div class="col-md-6 mb-10">会議情報を開きます。</div>
        <div class="col-md-6 mb-10">
          <img src="/image/help/2-2.jpg" class="helpimage" />
        </div>
        <div class="col-md-6 mb-10">
          外部会議一覧にある、新規登録を開きます。
        </div>
        <div class="col-md-6 mb-10">
          <img src="/image/help/2-3.jpg" class="helpimage" />
        </div>
        <div class="col-md-6 mb-10">
          会議名（クライアント名等）、開催日時を選択し、登録します。
        </div>
        <div class="col-md-6 mb-10">
          <img src="/image/help/2-4.jpg" class="helpimage" />
        </div>
        <div class="col-md-6 mb-10">
          作成を押した際に表示されるURLを会議に参加される方へお知らせください。URLを開くと会議へ直接参加することが出来ます。
        </div>
      </div>
    </div>

    <div class="card mb-20" id="help3">
      <div class="card-header">組織内会議への参加方法について</div>
      <div class="card-body row">
        <div class="col-md-6 mb-10">
          <img src="/image/help/3-1.jpg" class="helpimage" />
        </div>
        <div class="col-md-6 mb-10">会議情報を開きます。</div>
        <div class="col-md-6 mb-10">
          <img src="/image/help/3-2.jpg" class="helpimage" />
        </div>
        <div class="col-md-6 mb-10">
          内部会議一覧にある会議情報を確認し、入室するをクリックします。
        </div>
        <div class="col-md-6 mb-10">
          <img src="/image/help/3-3.jpg" class="helpimage" />
        </div>
        <div class="col-md-6 mb-10">
          参加する会議情報に間違いがなければ、会議へ参加をクリックします。
        </div>
        <div class="col-md-6 mb-10">
          <img src="/image/help/3-4.jpg" class="helpimage" />
        </div>
        <div class="col-md-6 mb-10">会議室へ入室することが出来ました。</div>
      </div>
    </div>

    <div class="card mb-20" id="help4">
      <div class="card-header">組織外会議への参加方法について</div>
      <div class="card-body row">
        <div class="col-md-6 mb-10">
          <img src="/image/help/4-1.jpg" class="helpimage" />
        </div>
        <div class="col-md-6 mb-10">会議情報を開きます。</div>
        <div class="col-md-6 mb-10">
          <img src="/image/help/4-2.jpg" class="helpimage" />
        </div>
        <div class="col-md-6 mb-10">
          外部会議一覧にある会議情報を確認し、入室するをクリックします。
        </div>
        <div class="col-md-6 mb-10">
          <img src="/image/help/4-3.jpg" class="helpimage" />
        </div>
        <div class="col-md-6 mb-10">
          参加する会議情報に間違いがなければ、会議へ参加をクリックします。
        </div>
        <div class="col-md-6 mb-10">
          <img src="/image/help/4-4.jpg" class="helpimage" />
        </div>
        <div class="col-md-6 mb-10">会議室へ入室することが出来ました。</div>
      </div>
    </div>

    <div class="card mb-20" id="help5">
      <div class="card-header">登録済み会議を中止する方法を知りたい</div>
      <div class="card-body row">
        <div class="col-md-6 mb-10">
          <img src="/image/help/5-1.jpg" class="helpimage" />
        </div>
        <div class="col-md-6 mb-10">会議情報を開きます。</div>
        <div class="col-md-6 mb-10">
          <img src="/image/help/5-2.jpg" class="helpimage" />
        </div>
        <div class="col-md-6 mb-10">
          内部会議、外部会議一覧を確認し、中止したい会議を見つけます。<br />
          該当する会議の入室するボタン右側にある編集アイコンをクリックします。
        </div>
        <div class="col-md-6 mb-10">
          <img src="/image/help/5-3.jpg" class="helpimage" />
        </div>
        <div class="col-md-6 mb-10">
          会議の詳細画面右側にある、削除するをクリックすることで、会議を中止することができます。<br />
          中止すると会議一覧にある該当会議の状況が中止に変更されます。
        </div>
      </div>
    </div>

    <div class="card mb-20" id="help6">
      <div class="card-header">ユーザー情報の確認、編集方法を知りたい</div>
      <div class="card-body row">
        <div class="col-md-6 mb-10">
          <img src="/image/help/6-1.jpg" class="helpimage" />
        </div>
        <div class="col-md-6 mb-10">
          ユーザー情報欄にある、編集するをクリックすることで、編集が可能です。
        </div>
      </div>
    </div>

    <div class="card mb-20" id="help7">
      <div class="card-header">ログインパスワードを忘れてしまった</div>
      <div class="card-body row">
        <div class="col-md-6 mb-10">
          <img src="/image/help/7-1.jpg" class="helpimage" />
        </div>
        <div class="col-md-6 mb-10">
          ログイン画面を開き、ログインボタン右側にある、パスワードを忘れた方はこちらをクリックします。
        </div>
        <div class="col-md-6 mb-10">
          <img src="/image/help/7-2.jpg" class="helpimage" />
        </div>
        <div class="col-md-6 mb-10">
          ご登録のメールアドレスを入力し、パスワードリセットのリンクを送信するボタンを押します。<br />
          ご入力されたメールアドレスの受信メールに送付されたＵＲＬより新しいパスワードをご入力ください。
        </div>
      </div>
    </div>

    <div class="card mb-20" id="help8">
      <div class="card-header">会議内の各機能について</div>
      <div class="card-body row">
        <div class="col-md-6 mb-10">
          <img src="/image/help/8-1.jpg" class="helpimage" />
        </div>
        <div class="col-md-6 mb-10">
          <table>
            <tr>
              <td>1</td>
              <td>カメラ</td>
              <td>カメラのオン・オフを切り替えます。</td>
            </tr>
            <tr>
              <td>2</td>
              <td>マイク</td>
              <td>マイクのオン・オフを切り替えます。</td>
            </tr>
            <tr>
              <td>3</td>
              <td>ミュート</td>
              <td>相手側の音声をオフにします。</td>
            </tr>
            <tr>
              <td>4</td>
              <td>参加者</td>
              <td>参加している方を一覧できます。</td>
            </tr>
            <tr>
              <td>5</td>
              <td>チャット</td>
              <td>文字の送信、受信ができます。</td>
            </tr>
            <tr>
              <td>6</td>
              <td>画面共有</td>
              <td>共有したい画面を選択できます。</td>
            </tr>
            <tr>
              <td>7</td>
              <td>録画共有</td>
              <td>会議をローカルエリアへ保存します。</td>
            </tr>
            <tr>
              <td>8</td>
              <td>招待</td>
              <td>この会議へのリンクを表示します。</td>
            </tr>
            <tr>
              <td>9</td>
              <td>退室</td>
              <td>通信を切断、退室します。</td>
            </tr>
            <tr>
              <td>10</td>
              <td>挙手賛成</td>
              <td>相手側へ意思表示にお使いください。</td>
            </tr>
            <tr>
              <td>11</td>
              <td>背景変更</td>
              <td>会議背景を変更することができます。</td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    <div class="card mb-20" id="help9">
      <div class="card-header">メンバー（社員等）の招待方法について</div>
      <div class="card-body row">
        <div class="col-md-6 mb-10">
          <img src="/image/help/9-1.jpg" class="helpimage" />
        </div>
        <div class="col-md-6 mb-10">管理ルームを開きます。</div>
        <div class="col-md-6 mb-10">
          <img src="/image/help/9-2.jpg" class="helpimage" />
        </div>
        <div class="col-md-6 mb-10">従業員を招待するをクリックします。</div>
        <div class="col-md-6 mb-10">
          <img src="/image/help/9-3.jpg" class="helpimage" />
        </div>
        <div class="col-md-6 mb-10">
          招待するメンバーのメールアドレスを入力し、招待するをクリックします。<br />
          入力したメールアドレスへ、登録のためのURLが送付されます。
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      example: "",
    };
  },
  props: {},
  mounted() {},
  methods: {
    example() {
      alert("hey!");
    },
  },
};
</script>
<style scoped>
.helpimage {
  width: 100%;
  box-shadow: 0 0 5px #808080;
}
.helptitle {
  margin-bottom: 0px;
  font-weight: 600;
}
</style>
