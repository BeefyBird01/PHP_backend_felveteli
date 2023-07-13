<?php

namespace felveteli\Controller;
use Exception;
use felveteli\Validator;

class UserController{    
    public static function Show(){
        //-----------database connection--------------//
        try{
            require '..\src\conn.php';
            $link = getDb();

            }
            catch(Exception $e){
                echo $e->getMessage();
                exit;
            }
        
        //-----------get users from database--------------//
        try{
            $sql = "SELECT id,first_name,last_name,email_address,phone_number FROM users";
            $users = mysqli_query($link,$sql) or die(mysqli_error($link));
        }
        catch(Exception $e){
            echo $e->getMessage();
            return;
        }
        
        //-----------create output data--------------//
        $data = array();
        if ($users->num_rows > 0) {
            while ($row = $users->fetch_assoc()) {
                $data[] = $row;
            }
        }
        
        $response = array(
            'status' =>'success',
            'data' =>$data
        );

        header('Content-Type: application/json');
        http_response_code(200);

        $jsonData = json_encode($response);
        closeDb($link);
        echo $jsonData;
        
    }

    public static function Create(){
        //-----------get data from request--------------//
        $json = file_get_contents('php://input');

        if($json === false){
            http_response_code(400);
            echo "Invalid json data";
            exit;
        }

        $data = json_decode($json,true);

        if($data ===false){
            http_response_code(400); 
            echo 'Error decoding JSON data.';
            exit;
        }

        //----------database connection--------------//
        try{
            require '..\src\conn.php';
            $link = getDb();

            }
            catch(Exception $e){
                echo $e->getMessage();
                exit;
            }

        //-----------validation--------------//

        $validator = new Validator($link);
        
        if(empty($data['first_name'])){
            http_response_code(400);
            echo 'First Name needed';
            exit;
        }
        $first_name = $validator->ValidateName($data['first_name']);
        if($first_name === false){
            http_response_code(400);
            echo 'Invalid firstname';
            exit;
        }

        if(empty($data['last_name'])){
            http_response_code(400);
            echo 'Last Name needed';
            exit;
        }
        $last_name = $validator->ValidateName($data['last_name']);
        if($last_name === false){
            http_response_code(400);
            echo 'Invalid lastname';
            exit;
        }

        if(!empty($data['phone_number'])){
            $phoneNumber = $validator->ValidatePhone($data['phone_number']);
                if($phoneNumber === false){
                    http_response_code(400);
                    echo 'Invalid phone number';
                    exit;
                }
        }
        else $phoneNumber = NULL;


        if(empty($data['email_address'])){
            http_response_code(400);
            echo 'Email needed';
            exit;
        }
        $email = $validator->ValidateEmail($data['email_address']);
        if($email === false){
            http_response_code(400);
            echo 'Invalid email';
            exit;
        }

        if(empty($data['password'])){
            http_response_code(400);
            echo 'Password needed';
            exit;
        }
        $password = $validator->ValidatePassword($data['password']);
        if($password === false){
            http_response_code(400);
            echo 'Password must be at least 6 characters';
            exit;
        }
        $password = password_hash($password,PASSWORD_BCRYPT);

        //-----------insert into database--------------//

        try{
            $sql = "INSERT INTO users (first_name, last_name, password, email_address, phone_number) VALUES
        ('$first_name', '$last_name', '$password', '$email', '$phoneNumber')";

        mysqli_query($link,$sql);
        }
        catch(Exception $e){
            echo $e->getMessage();
            exit;
        }

        //-----------get inserted data back from database--------------//

        $sql = "SELECT id FROM users WHERE email_address='$email'";
        $result = mysqli_query($link,$sql);
        $row = mysqli_fetch_array($result);
        $id=$row['id'];

        header('Content-Type: application/json');
        http_response_code(200);

        //-----------create output file--------------//

        $data = array(
            'id' => $id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email_address' => $email,
            'phone_number' => $phoneNumber
        );

        $response = array(
            'status' =>'success',
            'data' =>$data
        );

        $jsonData = json_encode($response);
        closeDb($link);
        echo $jsonData;
            
    }
}