<?php

    require_once "conexion/conexion.php";
    require_once "respuestas.class.php";

    class lineaArticulo extends conexion{

        
        private $table = "lineaArticulo";

        private $articulo = 0;
        private $username = "";
        private $cantidad = 0;

        private $token = "";
        //f6164ab0597635741ecef6fa834a1a25

        public function obtenerLineaArticulo($username){
            $query = "SELECT * FROM " . $this->table . " WHERE username = '$username'";
            return parent::obtenerDatos($query);
        }

        public function post($json){
            $_respuestas = new respuestas;

            $datos = json_decode($json, true);
            
            if(!isset($datos['token'])){
                return $_respuestas->error_401();

            }else{
                $this->token = $datos['token'];
                $arrayToken =   $this->buscarToken();
                if($arrayToken){

                    if(!isset($datos['articulo']) || !isset($datos['username']) || !isset($datos['cantidad'])){
                        return $_respuestas->error_400();
                    }
                    else{
                        $this->articulo = $datos['articulo'];
                        $this->username = $datos['username'];
                        $this->cantidad = $datos['cantidad'];

                        $resp = $this->insertarLineaArticulo();

                        if($resp){
                            $respuesta = $_respuestas->response;
                            $respuesta["result"] = array(
                                "respuesta" => $resp 
                            );
                            return $respuesta;
                        }else{
                            return $_respuestas->error_500;
                        }
                    }
                }else{
                return $_respuestas->error_500();
                }
            }
        }

        private function insertarLineaArticulo(){
            $query = "INSERT INTO " . $this->table . " (articulo, username, cantidad) VALUES
            (". $this->articulo . ",'" . $this->username ."'," . $this->cantidad . ")";
            
            $resp = parent::nonQuery($query);

            if($resp){
                return $resp;
            }
            else
            {
                return 0;
            }

        }

        public function put($json){
            //non requeriment
        }

        public function delete($json){

            $_respuestas = new respuestas;

            $datos = json_decode($json,true);

            if(!isset($datos['token'])){
                return $_respuestas->error_401();
            }else{
                $this->token = $datos['token'];
                $arrayToken =   $this->buscarToken();
                if($arrayToken){

                    if(!isset($datos['username'])){
                        return $_respuestas->error_400();
                    }else{
                        $this->username = $datos['username'];
                        $resp = $this->eliminarLineaArticulo();
                        if($resp){
                            $respuesta = $_respuestas->response;
                            $respuesta["result"] = array(
                                "username" => $this->username
                            );
                            return $respuesta;
                        }else{
                            return $_respuestas->error_500();
                        }
                    }

                }else{
                    return $_respuestas->error_401("El Token que envio es invalido o ha caducado");
                }
        }
    }

    private function buscarToken(){
        $query = "SELECT  idtoken, username, estado from token WHERE token = '" . $this->token . "' AND estado = 'Activo'";
        $resp = parent::obtenerDatos($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }

    public function eliminarLineaArticulo(){
        $query = "DELETE FROM " . $this->table . " WHERE username = '" . $this->username . "'";
        $resp = parent::nonQuery($query);
        if($resp >= 1 ){
            return $resp;
        }else{
            return 0;
        }
    }
}