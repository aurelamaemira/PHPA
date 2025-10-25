<?php include ('header.php'); ?>

<div class='login'>

<form class='form-sigin' action='' method='post'>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Username</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Username" name="username">
    
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password">
  </div>

  <button type="submit" class="btn btn-primary" name="submit" >Sign in</button>
<small>Don't have account?<a href="signup.php">Sign Up</a></small>
</form>
</div>

<?php include ('footer.php'); ?>
