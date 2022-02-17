<?php

    require_once 'clases/respuestas.class.php';
    require_once 'clases/linea.articulo.class.php';

    $_respuestas = new respuestas;
    $_lineaArticulo = new lineaArticulo;


    if($_SERVER['REQUEST_METHOD'] == "GET"){

        if(isset($_GET['username'])){
            $user = $_GET['username'];
            $datosUser = $_lineaArticulo->obtenerLineaArticulo($user);
            header("Content-Type: application/json");
            echo json_encode($datosUser);
            http_response_code(200);

        }
    }else if($_SERVER['REQUEST_METHOD'] == "POST"){

        $postBody = file_get_contents("php://input");

        $datosArray = $_lineaArticulo->post($postBody);

        header('Content-Type: application/json');
        if(isset($datosArray["result"]["error_id"])){
            $responseCode = $datosArray["result"]["error_id"];
            http_response_code($responseCode);
        }else{
            http_response_code(200);
        }

        echo json_encode($datosArray);

    }else if($_SERVER['REQUEST_METHOD'] == "PUT"){
        //non requirement
    }else if($_SERVER['REQUEST_METHOD'] == "DELETE"){ 

        $headers = getallheaders();
        if(isset($headers["token"]) && isset($headers["username"])){
            $send = [
                "token" => $headers["token"],
                "pacienteId" =>$headers["pacienteId"]
            ];
            $postBody = json_encode($send);
        }else{
            $postBody = file_get_contents("php://input");
        }
        
        $datosArray = $_lineaArticulo->delete($postBody);
        
        header('Content-Type: application/json');
        if(isset($datosArray["result"]["error_id"])){
            $responseCode = $datosArray["result"]["error_id"];
            http_response_code($responseCode);
        }else{
            http_response_code(200);
        }
        echo json_encode($datosArray);
    }
    else{
        header('Content-Type: application/json');
        $datosArray = $_respuestas->error_405();
        echo json_encode($datosArray);
    }

?>