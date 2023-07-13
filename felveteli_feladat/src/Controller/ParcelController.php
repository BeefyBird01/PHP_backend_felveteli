<?php

namespace felveteli\Controller;
use Exception;
use felveteli\Request;

class ParcelController {
    public static function Show(){

        //-----------get parcelnumber from url--------------//
        $request = new Request;
        $parcelNumber = $request->getParcelNumber();
        
        //-----------database connection--------------//
        try{
            require '..\src\conn.php';
            $link = getDb();

            }
            catch(Exception $e){
                echo $e->getMessage();
                exit;
            }

        //-----------get parcel from database--------------//

            $sql = "SELECT * FROM parcels WHERE parcel_number='$parcelNumber'";
            $parcel = mysqli_query($link,$sql);
            $parcel = mysqli_fetch_array($parcel);
            if(empty($parcel)){
                http_response_code(404);
                echo 'Parcel not found';
                exit;
            }
        //-----------get parcel from database--------------//
            $sql = "SELECT id,first_name,last_name,email_address,phone_number FROM users WHERE id='{$parcel['user_id']}'";
            $user = mysqli_query($link,$sql);
            $user = mysqli_fetch_array($user);

        //-----------create output data--------------//
            $userData = array(
                'id' => $user['id'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'email_address' => $user['email_address'],
                'phone_number' => $user['phone_number']
            );

            $parcelData = array(
                'id' => $parcel['id'],
                'parcel_number' => $parcel['parcel_number'],
                'size' => $parcel['size'],
                'user' => $userData
            );

            header('Content-Type: application/json');
            http_response_code(200);
            $jsonData = json_encode($parcelData);

            closeDb($link);
            echo $jsonData;
            
    }

    public static function Create(){
        $sizeArray = array(
            'S',
            'M',
            'L',
            'XL'
        );
        //-----------get input/validate--------------//
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

        if(empty($data['size']) || empty($data['user_id'])){
            http_response_code(400);
            echo "Invalid json data";
            exit;
        }

        $data['size']=strtoupper($data['size']);

        if(!in_array($data['size'],$sizeArray)){
            http_response_code(400);
            echo "Invalid size. Allowed: S, M, L, XL";
            exit;
        }

        //-----------database connection--------------//
        try{
            require '..\src\conn.php';
            $link = getDb();

            }
            catch(Exception $e){
                echo $e->getMessage();
                exit;
            }
        //-----------create unique hexcode--------------//
            $unique = false;
            while(!$unique){
                $hexCode = bin2hex(random_bytes(5));
                $sql = "SELECT * FROM parcels WHERE parcel_number = '$hexCode'";
                $result = mysqli_query($link,$sql);
                $result = mysqli_fetch_array($result);
                if($result == null){
                    $unique = true;
                }
            }
        //-----------insert result into database--------------//
            $sql = "INSERT INTO parcels (parcel_number, size, user_id) VALUES
            ('$hexCode', '{$data['size']}', '{$data['user_id']}')";
            try{
                mysqli_query($link,$sql);
            }
            catch(Exception $e){
                echo $e->getMessage();
                exit;
            }
        
        //-----------get inserted data back from database--------------//
            $sql = "SELECT id FROM parcels WHERE parcel_number = '$hexCode'";
            $result = mysqli_query($link,$sql);
            $result = mysqli_fetch_array($result);
            $parcelId = $result['id'];
        //-----------create output file--------------//
            $data = array(
                'id' => $parcelId,
                'parcel_number' => $hexCode,
                'size' => $data['size'],
                'user_id' => $data['user_id']
            );

            header('Content-Type: application/json');
            http_response_code(200);

            closeDb($link);
            
            $jsonData = json_encode($data);
            echo $jsonData;
            
            
    }
}