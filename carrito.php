<?php
    require_once "clases/carrito.class.php";
    require_once "clases/respuestas.class.php";

    $_respuestas = new respuestas;
    $_carrito = new carrito;

    if($_SERVER['REQUEST_METHOD'] == "GET") {
        # code...
        print_r("GET");
    }
    elseif($_SERVER['REQUEST_METHOD'] == "POST") {
        # code...
        print_r("POST");
    }
    elseif($_SERVER['REQUEST_METHOD'] == "PUT") {
        # code...
        print_r("PUT");
    }
    elseif($_SERVER['REQUEST_METHOD'] == "DELETE") {
        # code...
        print_r("DELETE");
    }
    else{
        header('Content-Type: application/json');
        $datosArray = $_respuestas->error_405();
        echo json_encode($datosArray);
    }
?>