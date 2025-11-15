<?php
require_once("Model.php");

class User extends Model{
    private int $id;
    private string $name;
    private string $email;
    private string $password;
     private string $role = '';

    protected static string $table="users";

    public function __construct(array $data){
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->role = $data['role'] ?? '';
    }
    public function getID(){
        return $this->id;
    }
     public function getName(){
        return $this->name;
    }
     public function setName(string $name){
        $this->name = $name;
    }
    public function getEmail(){
        return $this->email;
    }
    public function setEmail(string $email){
        $this->email=$email;
    }
    public function setpassword(string $password){
        $this->password=$password;
    }
    public function getrole(){
        return $this->role;
    }
    public function setrole(string $role){
        $this->role=$role;
    }
    public function toArray(){
        return ["id"=>$this->id, "name"=>$this->name, "email"=>$this->email,"password"=>$this->password,"role"=>$this->role];
    }

}