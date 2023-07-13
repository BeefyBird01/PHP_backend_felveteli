<?php
namespace felveteli;

class Validator{
    private $namePattern;
    private $phonePattern;
    private $emailPattern;
    private $link;
    
    public function __construct($plink){
        $this->namePattern = "/^[a-zA-Z]+(?: [a-zA-Z]+)*$/";
        $this->phonePattern = "/^\+?\d{6,}$/";
        $this->link = $plink;
    }

    public function ValidateName(string $name){
        $escape_name = mysqli_real_escape_string($this->link,$name);
        if(preg_match($this->namePattern,$escape_name)){
            return $escape_name;
        }
        else return false;
    }

    public function ValidatePhone(string $phone){
        $escape_phone = mysqli_real_escape_string($this->link,$phone);
        if(strlen($escape_phone)==0){
            return null;
        }
        if(preg_match($this->phonePattern,$escape_phone)){
            return $escape_phone;
        }
        else return false;
    }

    public function ValidatePassword(string $pw){
        $password = mysqli_real_escape_string($this->link,$pw);
        if(strlen($password)<6){
            return false;
        }
        else return $password;
    }

    public function ValidateEmail(string $email){
        $escape_email = mysqli_real_escape_string($this->link,$email);
        if (filter_var($escape_email, FILTER_VALIDATE_EMAIL)) {
            return $escape_email;
        }
        else return false;
    }
}