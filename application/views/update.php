<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update task</title>
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('images/favicon1.png');?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.7.16/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    
    <nav class="navbar navbar-expand-sm bg-light navbar-light fixed-top">
        <div class="container-fluid">
            <ul class="navbar-nav">
            <li class="nav-item">
                <a class=" navbar-brand home" href="<?php echo base_url('aflogin'); ?>"><img src="<?php echo base_url('images/home.png');?>" style="width:40px; height:auto" alt="Home"></a>
            </li>
            <li class="nav-item">
                <a class="nav-link home" style="color:#E74E35; font-size:20px;" href="<?php echo base_url('aflogin'); ?>"><b>Taskify</b></a>
            </li>
            </ul>
        </div>
    </nav><br><br><p></p><br><br>

    <div class="container-fluid" id="edit">
        <form id="fupdate" @submit.prevent="submitEdit">
        <div class="container">
        <div class="row">
        <div class="col col-lg col-sm me-2">
            <label class="form-label" for="task" >Task</label>
            <textarea class= "form-control border-#adb5bd" v-model="task" id="task" name="task" style="width:320px;height:100px;"></textarea>
        </div>
        <div class="col col-lg col-sm">
            <label class="form-label" for="date" >Created On</label>
            <input type="text" class="form-control border-#adb5bd" id="date" name="datetime" style="width:250px;height:50px;" :placeholder="time" readonly>
        </div>
        <div class="col col-lg col-sm">
        <label class="form-label" for="dropdown">Status</label> 
        <select class="form-select" :value="status" v-model='status' name="status" style="width:250px;height:50px;">
        <option id="status"  value="" selected hidden></option>
        <option>Todo</option>
        <option>In Progress</option>
        <option>Completed</option>
        </select>
        </div>
        </div><br><br>
        <input type="submit" name="submit" class="btn" style="width: 150px; background-color:#E74E35; color:white;" value="Save changes">
        </div>
        </form>
    </div>
    
<script>

    var edit = new Vue({
        el: '#edit',
        data: {
            tasks: [],
            task: '',
            time: '',
            status: ''
        },
        mounted() {
            this.showTask();
        },
        methods:{
            async showTask(){
                let token = localStorage.getItem("taski_fy_user_token");
                let oldurl = window.location.href;
                let id = oldurl.substring(oldurl.lastIndexOf('/') + 1);
                const response = await axios.get('/sreadtask/' + id, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                });
                if(response.data.length > 0){
                    this.tasks = response.data;
                    this.task = response.data[0]['task'];
                    this.time = response.data[0]['created_on'];
                    this.status = response.data[0]['status'] 
                }else{
                    console.log("error")
                }
            },
            async submitEdit(){
                let token = localStorage.getItem("taski_fy_user_token");
                let oldurl = window.location.href;
                let id = oldurl.substring(oldurl.lastIndexOf('/') + 1);
                var base_url = "<?php echo base_url(); ?>";

                let formData = {
                    task: this.task,
                    status: this.status
                };

                const response = await axios.put('/supdate/' + id, formData, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                });
                if(response){
                    window.location.href = base_url + 'aflogin';
                }else{
                    console.log("failed")
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