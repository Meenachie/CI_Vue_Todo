<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
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
            <a class=" navbar-brand home" href="welcome"><img src="images/home.png" style="width:40px; height:auto" alt="Home"></a>
            </li>
            <li class="nav-item">
            <a class="nav-link home" style="color:#E74E35; font-size:20px;" href="welcome"><b>Taskify</b></a>
            </li>
            </ul>
        <form class="d-flex">
            <a href="login"><button class="btn me-3" style="background-color:#E74E35; color:white;" type="button">Login</button></a>
            <a href="signup" class="signup"><button class="btn btn-light me-2 signup" type="button">Signup</button></a>
        </form>
        </div>
    </nav>
<div id="app"><br><br><p></p><br><br>
    <div class="container mt-5">
        <div class="shadow-lg p-4 mb-4 bg-white">
            <center><p class="h3">Log In</p></center>
        <div class="container mt-3">
        <div class="alert alert-danger" id="error" style="display: none;"></div>
            <form @submit.prevent="submitLogin" id="flogin"> 
                <div class="form-floating mb-3 mt-3">
                    <input type="email" class="form-control"  v-model="email" id="email" placeholder="Enter email" name="email" required>
                    <label for="email">Email</label>
                    <small class="text-danger"></small>
                </div>
                <div class="form-floating mt-3 mb-3">
                    <input type="password" class="form-control" v-model="password" id="pwd" placeholder="Enter password" name="password" required>
                    <label for="pwd">Password</label>
                    <small class="text-danger"></small>
                </div>
                    <center><button type="submit" name="login" class="btn" style="width: 150px;background-color:#E74E35; color:white;">Log In</button><center>
                    <hr>
                    <p>Don't have an account?&nbsp;<a href="signup" class="signup" style="text-decoration:none;color:#E74E35;">Sign Up</a></p>
            </form>
        </div>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#app',
        data: {
            email: '',
            password: ''
        },
        methods: {

            async submitLogin(){
                var formData = new FormData();
                formData.append('email', this.email);
                formData.append('password', this.password);

                var emailValid = this.validateEmail(this.email);
                var passwordValid = this.validatePassword(this.password);

                if(emailValid && passwordValid){
                    this.hideErrors();

                    var base_url = "<?php echo base_url(); ?>";
                    var token = '';

                    const response = await axios.post(base_url + 'slogin', formData);
                    if(response.data.access_token){
                        token = response.data.access_token;
                        localStorage.setItem("taski_fy_user_token",token);
                        this.email = '';
                        this.password = '';
                        window.location.href = base_url + 'aflogin';
                    }else{
                        let message = response.data[0];
                        this.showError(message);
                    }
                }
            },

            validateEmail(email){
                if(email !== ''){
                    return true;
                }else{
                    this.showError("Please enter your email");
                    return false;
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
</script>    

</body>
</html>
