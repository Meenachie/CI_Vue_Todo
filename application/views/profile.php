<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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
        <form class="d-flex">
            <a href="change_password.php" class="changePassword" style="text-decoration:none"><input class="btn me-3 d-flex align-items-center justify-content-center" style="background-color:#E74E35; color:white; width:200px" type="submit" value="Change Password"></a>&nbsp&nbsp
            <div class="dropdown">
            <button type="button" class="btn" style="border:none" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="images/user.png" style="width:30px; height:auto" alt="user">
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="">Profile</a></li>
                <li><a class="dropdown-item changePassword" href="change_password">Change Password</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><input class="dropdown-item" id="logout" type="submit" name="logout" value="Log Out"></li>
            </ul>
            </div>
        </form>
    </div>
    </nav><br><br><p></p><br><br>

    <div class="container-fluid mt-3" id='profile'>
    <div class="container mt-5">
    <div class="shadow-lg p-4 mb-4 bg-white">
        <p><b>Name:</b>&nbsp&nbsp<span id='name'>{{name}}</span></p>
        <p><b>Email:</b>&nbsp&nbsp&nbsp<span id='email'>{{email}}</span></p>
    </div>
    </div>
</div>

<script>
    
    var profile = new Vue({
        el: '#profile',
        data: {
            name: '',
            email: ''
        },
        mounted() {
            this.showProfile();
        },
        methods: {
            async showProfile(){
                let token = localStorage.getItem("taski_fy_user_token");
                const response = await axios.get('sprofile', {
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                });
                if(response){
                    this.name = response.data[0]['name'];
                    this.email = response.data[0]['email'];
                }else{
                    console.log("failed");
                }
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