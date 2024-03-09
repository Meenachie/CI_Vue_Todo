<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>signup</title>
    <link rel="icon" type="image/x-icon" href="images/favicon1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.7.16/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-sm bg-light navbar-light fixed-top">
        <div class="container-fluid">
            <ul class="navbar-nav">
            <li class="nav-item">
            <a class=" navbar-brand" href="welcome"><img src="images/home.png" style="width:40px; height:auto" alt="Home"></a>
            </li>
            <li class="nav-item">
            <a class="nav-link" style="color:#E74E35; font-size:20px;" href="welcome"><b>Taskify</b></a>
            </li>
            </ul>
        <form class="d-flex">
            <a href="login" class="login"><button class="btn btn-light me-3 login" type="button">Login</button></a>
            <a href=""><button class="btn me-2" style="background-color:#E74E35; color:white;" type="button">Signup</button></a>
        </form>
        </div>
    </nav>
<div id="appSignup"><br><br><br>
    <div class="container mt-5">
        <div class="shadow-lg p-4 mb-4 bg-white">
            <center><p class="h3">Sign Up</p></center>
            <div class="container mt-3"> 
                <div class="alert alert-danger" id="error" style="display: none;"></div>
                <form @submit.prevent="submitSignup" id="fsignup">
                    <div class="form-floating mb-3 mt-3">
                        <input type="text" class="form-control" v-model="name" id="name" placeholder="Enter Your Name" name="name" required>
                        <label for="name">Name</label>
                    </div>
                    <div class="form-floating mb-3 mt-3">
                        <input type="email" class="form-control" v-model="email" id="email" placeholder="Enter email" name="email" required>
                        <label for="email">Email</label>
                    </div>
                    <div class="form-floating mt-3 mb-3">
                        <input type="password" class="form-control" v-model="password" id="pwd" placeholder="Enter password" name="password" required>
                        <label for="pwd">Password</label> <!--for:password-->
                    </div>
                    <div class="form-floating mt-3 mb-3">
                        <input type="password" class="form-control"  v-model="confirmPassword" id="pwdd" placeholder="Re-enter password" name="confirm_password" required>
                        <label for="pwdd">Confirm Password</label> <!--confirm_password-->
                    </div>
                    <center><button type="submit" name="submit" class="btn" style="width:150px; background-color:#E74E35; color:white;">Sign Up</button><center>
                    <hr>
                    <p>Already have an account?&nbsp;<a href="login" class="login" style="text-decoration:none;color:#E74E35;">Login</a></p>
                </form>
            </div>
        </div>
    </div> 
</div>

<script>
    new Vue({
        el: '#appSignup',
        data: {
            name: '',
            email: '',
            password: '',
            confirmPassword: ''
        },
        methods: {

            async submitSignup(){
                var formData = new FormData();
                formData.append('name', this.name);
                formData.append('email', this.email);
                formData.append('password', this.password);
                formData.append('confirmPassword', this.confirmPassword);

                var nameValid = this.validateName(this.name);
                var emailValid = this.validateEmail(this.email);
                var passwordValid = this.validatePassword(this.password);
                var confirmPasswordValid = this.validateConfirmPassword(this.confirmPassword);

                if(nameValid && emailValid && passwordValid && confirmPasswordValid){
                    if(this.password === this.confirmPassword){
                        this.hideErrors();

                        var base_url = "<?php echo base_url(); ?>";
                        var token = '';

                        const response = await axios.post(base_url + 'ssignup', formData);
                        if(response.data.access_token){
                            token = response.data.access_token;
                            localStorage.setItem("taski_fy_user_token",token);
                            this.name = '';
                            this.email = '';
                            this.password = '';
                            this.confirmPassword = '';
                            window.location.href = base_url + 'aflogin';
                        }else{
                            let message = response.data[0];
                            this.showError(message);
                        }
                    }else{
                        this.showError("Password doesn't match");
                        return false;
                    }
                    
                }
            },

            validateName(name){
                if(name !== ''){
                    return true;
                }else{
                    this.showError("Please enter your name");
                    return false;
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

            validateConfirmPassword(confirmPassword){
                if(confirmPassword !== ''){
                    var regex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
                    if(regex.test(confirmPassword)){
                        return true;
                    }else{
                        this.showError("Confirm Password must contain at least one lowercase letter, one uppercase letter, one digit, and be at least 8 characters long");
                        return false;
                    }
                }else{
                    this.showError("Please enter the Confirm password");
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