<div class="login">
  <?php if(isset($error)) echo $error; ?>

  <div class="al_load">
    <i class="fa fa-user userlog"></i>
  </div>

  <form id="loginForm" action="#" method="post">
    <div class="input-group" style="width: 40%;">
      <span class="input-group-addon" style="width: 120px;">Username</span>
      <input name="usr" type="text" class="form-control">
    </div>
    <div class="input-group" style="width: 40%;">
      <span class="input-group-addon" style="width: 120px;">Password</span>
      <input name="pw" type="password" class="form-control" >
    </div>
    <input name="login" type="number" value="1" style="display:none">
  </form>

  <div onclick="document.getElementById('loginForm').submit()" class='al_btn al_submit'>
    <div class='anim_btn al_submit'>
        Login
    </div>
      Login <i class="fa fa-arrow-right"></i>
  </div>
</div>
