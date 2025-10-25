<?php include ('header.php'); ?>

<div class='signup'>

<form class="form-signin" action='' method='post'>
            
              <div class="row">
                <div class="col-md-6 mb-4">
                  <div data-mdb-input-init class="form-outline">
                    <input type="text" id="form3Example1" class="form-control" placeholder="Name" name="name" />
                    <label class="form-label" for="form3Example1">Name</label>
                  </div>
                </div>

                <div class="col-md-6 mb-4">
                  <div data-mdb-input-init class="form-outline">
                    <input type="text" id="form3Example2" class="form-control" placeholder="Surname" name="surname"/>
                    <label class="form-label" for="form3Example2">Surname</label>
                  </div>
                

              <div data-mdb-input-init class="form-outline">
                    <input type="text" id="form3Example2" class="form-control" placeholder="surname" name="surname" />
                    <label class="form-label" for="form3Example2">Username</label>
                  </div>
                </div>
           
              <div data-mdb-input-init class="form-outline mb-4">
                <input type="email" id="form3Example3" class="form-control" placeholder="email" name="email"/>
                <label class="form-label" for="form3Example3">Email address</label >
              </div>

               <div data-mdb-input-init class="form-outline mb-4">
                <input type="password" id="form3Example3" class="form-control" placeholder="password" name="password"/>
                <label class="form-label" for="form3Example3">Password</label >
              </div>

        

             
              <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4" name="submit">
                Sign up
              </button>


<small>Aleready have am account? <a href="login.php">Log in</small>

            </form>
</div>

<?php include ('footer.php'); ?>