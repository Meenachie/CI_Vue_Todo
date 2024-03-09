<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>change password</title>
    <link rel="icon" type="image/x-icon" href="images/favicon1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.7.16/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-sm bg-light navbar-light fixed-top">
        <div class="container-fluid">
            <ul class="navbar-nav">
            <li class="nav-item">
                <a class=" navbar-brand home" href="aflogin"><img src="images/home.png" style="width:40px; height:auto" alt="Home"></a>
            </li>
            <li class="nav-item">
                <a class="nav-link home" style="color:#E74E35; font-size:20px;" href="aflogin"><b>Taskify</b></a>
            </li>
            </ul>
        </div>
    </nav><br><br><p></p><br>
<div class="container mt-5" id="changepwd">
  <div class="shadow-lg p-4 mb-4 bg-white">
    <center><p class="h3">Change Password</p></center>
    <div class="container mt-3">
    <div class="alert alert-danger" id="error" style="display:none;" ></div>
    <div class="alert alert-success" v-show="successMessage" id="message" >{{ successMessage }}</div>
      <form @submit.prevent="submitPassword" id="fpwd">
        <div class="form-floating mt-3 mb-3">
          <input type="password" class="form-control" v-model="oldPassword" id="oldpwd" placeholder="Old password" name="oldpwd">
          <label for="oldpwd">Old Password</label>
          <small class="text-danger"></small>
        </div>
        <div class="form-floating mt-3 mb-3">
          <input type="password" class="form-control" v-model="newPassword" id="newpwd" placeholder="New password" name="newpwd">
          <label for="newpwd">New Password</label>
          <small class="text-danger"></small>
        </div>
        <center><button type="submit" name="submit" class="btn" style="width: 150px; background-color:#E74E35; color:white;">Submit</button><center>
      </form>
    </div>
  </div>
</div>    

<script>
  new Vue({
    el: '#changepwd',
    data: {
      oldPassword: '',
      newPassword: '',
      successMessage: ''
    },
    methods: {
      async submitPassword(){
      
        var oldPasswordValid = this.validatePassword(this.oldPassword);
        var newPasswordValid = this.validatePassword(this.newPassword);

        if(oldPasswordValid && newPasswordValid){
          if(this.oldPassword === this.newPassword){
            this.showError("Both Old Password and New Password are same!! Please enter different Passwords!");
          }else{
            this.hideErrors();
            let formData = {
              oldpwd: this.oldPassword,
              newpwd: this.newPassword
            };

            let token = localStorage.getItem("taski_fy_user_token");
          
            const response = await axios.put('schange_pwd', formData, {
              headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
              }
            });

            if(response){
              this.oldPassword = '';
              this.newPassword = '';
              this.successMessage = response.data[0]; 
            }
            else{
              console.log("error");
            }
          }
        }
        
      },
      validatePassword(password){
        if(password !== ''){
          var regex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
          if(regex.test(password)){
            return true;
          }else{
            this.showError("Password must contain at least one lowercase letter, one uppercase letter, one digit, and be at least 8 characters long");
            return false;
          }
        }else{
            this.showError("Please enter the password");
            return false;
        }
      },  
      hideErrors(){
        document.getElementById('error').style.display = 'none';
      },
      showError(message) {
        document.getElementById('error').innerText = message;
        document.getElementById('error').style.display = 'block';
      }
    }
  });

  if (!localStorage.getItem('taski_fy_user_token')){
    var base_url = "<?php echo base_url(); ?>";
    window.location.href = base_url + 'welcome'; 
  }
  
</script>

</body>
</html>