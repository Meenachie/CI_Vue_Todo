<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model{

    public function signup($data){
        return $this->db->insert('user', $data);
    }

    public function login($email){
        return $this->db->where('email',$email)->get('user')->row();
    }

    public function change_password($email, $new_password){
       return $this->db->query("UPDATE `user` SET password='$new_password' WHERE email='$email'");
    }

    public function profile($id){
        $this->db->where('id',$id);
        $query= $this->db->get('user');
        return $query->result_array();
    }

    public function read($id){
        $query = $this->db->query("SELECT t.id,t.task,t.status,t.created_on  FROM `task` as t LEFT JOIN `user` as u ON t.user_id=u.id WHERE u.id='$id'");
        return $query-> result_array();
    }

    public function create($id,$task){
        return $this->db->query("INSERT INTO `task` (task,user_id) VALUES('$task','$id')");
    }

    public function edit($id){
        $query= $this->db->query("SELECT id, task ,status, created_on  FROM `task` WHERE id='$id'");
        return $query-> result_array();
    }

    public function update($id,$task,$status){
        $this->db->set('task', $task);
        $this->db->set('status', $status);
        $this->db->set('created_on','NOW()',false);
        $this->db->where('id',$id);
        return $this->db->update('task');
    }

    public function delete($id){
        return $this->db->query("DELETE FROM `task` WHERE id='$id'");
    }
}
?>