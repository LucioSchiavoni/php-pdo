<?php  
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("content-type: application/json; charset=utf-8");
include("../model/turistModel.php");

$userModel = new userModel();


    switch($_SERVER['REQUEST_METHOD']){

        case 'POST':

            //PUT
            if(!empty($_GET['put'])) {  

                $res = $userModel->updateUsers($_POST['id'],$_POST['nombre'],$_POST['email'],$_POST['pwd'],$_POST['hotel'],$_POST['fecha_inicio'],$_POST['fecha_fin'],$_POST['barrio']);
                echo json_encode($res);

            //DELETE
            }else if(!empty($_GET['delete'])){

                $res = $userModel->deleteUsers($_POST['id']);
                echo json_encode($res);



            //POST
            }else if (!empty($_GET['post'])){

                if(strlen($_POST['pwd']) < 8){ //El largo de la password debe ser de mas de 8 caracteres
                    $res = ['Error', 'Su contrasena debe contener mas de 8 caracteres'];

                }else if(preg_match('/\d/',$_POST['nombre'])){//Si pone un numero en el nombre, no es valido
                    $res = ['Error', 'Ingrese un nombre valido'];

                
                }else{ //Si pasa las validaciones se ejecuta la funcion

                    $res = $userModel->saveTurist($_POST['nombre'],$_POST['email'],$_POST['pwd'],$_POST['hotel'],$_POST['fecha_inicio'],$_POST['fecha_fin'],$_POST['barrio']);
                }
                echo json_encode($res);
                
            
        
            }else if(!empty($_GET['login'])) {
                
                $email = $_POST['email'];
                $password = $_POST['pwd'];

                $res = $userModel->loginUser($email, $password);
                echo json_encode($res);
            }else if{
            $res = ['Error', 'Error en el servidor'];
    }
            break;

        case 'GET':
        //GET      
        if(!empty($_GET['getUser'])){

            $res = $userModel->getUsers();
            echo json_encode($res);
        }
        
            break;

}



?>