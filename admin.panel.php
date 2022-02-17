<?php

    require_once 'clases/respuestas.class.php';
    require_once 'clases/admin.panel.class.php';

    $_respuestas = new respuestas; 
    $_adminpanel = new adminPanel;

    if($_SERVER['REQUEST_METHOD'] == "GET"){

        if(isset($_GET["page"])){

            $pagina = $_GET["page"];
            $listaArticulos = $_adminpanel->listaArticulos($pagina);
            
            header("Content-Type: application/json");
            echo json_encode($listaArticulos);
            http_response_code(200);

        }else if(isset($_GET['idArticulo'])){

            $articulo = $_GET['idArticulo'];
            $datosArticulo = $_adminpanel->obtenerArticulo($articulo);

            header("Content-Type: application/json");
            echo json_encode($datosArticulo);
            http_response_code(200);
        }
    }else if($_SERVER['REQUEST_METHOD'] == "POST"){

        $postBody = file_get_contents("php://input");
        $datosArray = $_adminpanel->post($postBody);
        
        header('Content-Type: application/json');
        if(isset($datosArray["result"]["error_id"])){
            $responseCode = $datosArray["result"]["error_id"];
            http_response_code($responseCode);
        }else{
            http_response_code(200);
        }

        echo json_encode($datosArray);

    }else if($_SERVER['REQUEST_METHOD'] == "PUT"){

        $postBody = file_get_contents("php://input");
        $datosArray = $_adminpanel->put($postBody);
        
       header('Content-Type: application/json');
       if(isset($datosArray["result"]["error_id"])){
           $responseCode = $datosArray["result"]["error_id"];
           http_response_code($responseCode);
       }else{
           http_response_code(200);
       }
       echo json_encode($datosArray);
  
    }else if($_SERVER['REQUEST_METHOD'] == "DELETE"){
        
        $postBody = file_get_contents("php://input");
        $datosArray = $_adminpanel->delete($postBody);
        
        header('Content-Type: application/json');
        if(isset($datosArray["result"]["error_id"])){
            $responseCode = $datosArray["result"]["error_id"];
            http_response_code($responseCode);
        }else{
            http_response_code(200);
        }
        
        echo json_encode($datosArray);

    }else{
        header('Content-Type: application/json');
        $datosArray = $_respuestas->error_405();
        echo json_encode($datosArray);
    }

?>