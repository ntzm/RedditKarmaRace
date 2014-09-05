<?php include "templates/header.php"; ?>
<h1 class="text-center">Reddit Karma Race</h1>
<div class="panel">
  <p>Enter two usernames below and see who can reach their goal the fastest!</p>
</div>
<hr>
<form action="" id="form-main">
  <div class="row">
    <div class="large-6 columns" id="user-1">
      <label>User 1
        <input type="text" maxlength="20">
      </label>
      <small></small>
      <div class="panel hide"></div>
    </div>
    <div class="large-6 columns" id="user-2">
      <label>User 2
        <input type="text" maxlength="20">
      </label>
      <small></small>
      <div class="panel hide"></div>
    </div>
  </div>
  <div class="row">
    <div class="large-6 columns" id="amount">
      <label>Amount of karma to increase by
        <input type="number" value="100" maxlength="10">
      </label>
      <small></small>
    </div>
    <div class="large-6 columns">
      <label>
        <input name="karma-type" type="radio" id="lkarma" checked> Link karma
      </label>
      <label>
        <input name="karma-type" type="radio" id="ckarma"> Comment karma
      </label>
    </div>
  </div>
  <button class="button expand" role="button">Go</button>
  <div id="message"></div>
</form>
<?php include "templates/footer.php"; ?>