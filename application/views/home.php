<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskify</title>
    <link rel="icon" type="image/x-icon" href="images/favicon1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.7.16/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        .dropdown-item:active {
        background-color: #E74E35;
    }
    </style>
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-sm bg-light navbar-light fixed-top">
        <div class="container-fluid" id="app1">
            <ul class="navbar-nav">
            <li class="nav-item">
                <a class=" navbar-brand" href=""><img src="images/home.png" style="width:40px; height:auto" alt="Home"></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" style="color:#E74E35; font-size:20px;" href=""><b>Taskify</b></a>
            </li>
            </ul>
            <form class="d-flex" action="<?php echo base_url('logout');?>" method="post">
                <button class="btn me-3 d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#myModal" style="background-color:#E74E35; color:white; width:200px" type="button">Create New Task &nbsp&nbsp<img src="images/plus.png" style="width:15px; height:auto;" ></button>
                <div class="dropdown">
                <button type="button" class="btn" style="border:none" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="images/user.png" style="width:30px; height:auto" alt="user">
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" id="profile" type="submit" @click="goToProfile">Profile</a></li>
                    <li><a class="dropdown-item" id='changePassword' type="submit" @click="goToChangePassword">Change Password</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item"  id="logout" type="submit" name="logout" @click="logout">Log Out</a></li>
                </ul>
                </div>
            </form>
        </div>
    </nav><br><br><p></p><br><br>
    <div class="modal fade" id="myModal">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header border-0">
            <h4 class="modal-title">To Do</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form @submit.prevent="submitTask" id="ftask">
            <div class="model-body">
                <textarea type="text" class="form-control" v-model="task" id="task" placeholder="new task" name="task" style="height: 100px;"></textarea>    
            </div>
            <div class="modal-footer border-0">
                <button class="btn btn-danger" type="submit" name="add" style="background-color:#E74E35; color:white;">Add task</button>
            </div>
        </form>
    </div>
    </div>
    </div>
</div>

    <div id="appTask" class="container-fluid">
        <center><p class="h1" style="color:#E74E35;"> To Doo </p></center>
    </div>
    <div class="container" id="ttask">
        <table class="table mt-5">
        <thead>
            <tr>
            <th scope="col">S.No</th>
            <th scope="col">Task</th>
            <th scope="col">Status</th>
            <th scope="col">Created On</th>
            <th scope="col">Update</th>
            <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody id="results">
            <tr v-for="(task, index) in tasks" :key="task.id">
            <td> {{ index + 1 }} </td> 
            <td> {{ task.task }} </td>
            <td> {{ task.status }} </td>
            <td> {{ task.created_on }} </td>
            <td>&nbsp&nbsp&nbsp<a @click="editTask(task.id)"><img src="images/edit.png"></a></td>
            <td>&nbsp&nbsp&nbsp<a @click="deleteTask(task.id)"><img src="images/del.png"></a></td>
            </tr>
        </tbody>
        </table>
    </div><br>
    <center><h4 id="notask" ></h4></center>

<script>
 
    var ttask = new Vue({
        el: '#ttask',
        data: {
            tasks: [],
            token: ''
        },
        mounted(){
            this.getAllTasks();
        },
        methods: {
            async getAllTasks(){
                var self = this
                let token = localStorage.getItem("taski_fy_user_token");
                self.token = token
                const response = await axios.get('sread', {
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                });
                if(response.data.length > 0){
                    self.tasks = response.data;
                }else{
                    document.getElementById('notask').innerText = "No tasks found!! Start creating your tasks";
                }
            },
            editTask(id){
                window.location.href = 'bfedit/' + id;
            },
            deleteTask(id){
                let token = localStorage.getItem("taski_fy_user_token");
                const response = axios.delete('sdelete/' + id, {
                    headers: {
                        'Authorization': 'Bearer ' + token
                    }
                } );
                if(response){
                    this.getAllTasks();
                }else{
                    alert("Failed, Try again!!");
                }
            }
        }
    });

    var myModal = new Vue({
        el: '#myModal',
        data: {
            task: '',
        },
        methods: {
            async submitTask(){
                var formData = new FormData();
                formData.append('task', this.task);
                let token = localStorage.getItem("taski_fy_user_token");
                const response = await axios.post('screate', formData, {
                    headers: {
                        'Authorization': 'Bearer ' + token
                    }
                });
                if(response){
                    this.task= '';
                    ttask.getAllTasks();
                }else{
                    alert("Failed, Try again!!");
                }
            }
        }
    });

    var app1 = new Vue({
        el: '#app1',
        methods: {
            logout(){
                var base_url = "<?php echo base_url(); ?>";
                localStorage.removeItem("taski_fy_user_token");
                window.location.href = base_url + 'welcome';
            },
            goToProfile(){
                console.log('Profile');
                var base_url = "<?php echo base_url(); ?>";
                window.location.href = base_url + 'vprofile';
            },
            goToChangePassword(){
                console.log('Change Password');
                var base_url = "<?php echo base_url(); ?>";
                window.location.href = base_url + 'vchange_password';
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