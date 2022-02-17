<?php

    require_once "conexion/conexion.php";
    require_once "respuestas.class.php";

    class adminPanel extends conexion{

        private $table = "Articulo";

        private $idArticulo = 0;
        private $catalogo = 0;
        private $nombre = "";
        private $descripcion = "";
        private $precio = 0;

        public function listaArticulos($pagina = 1){
            $inicio  = 0 ;
            $cantidad = 10;
            if($pagina > 1){
                $inicio = ($cantidad * ($pagina - 1)) + 1 ;
                $cantidad = $cantidad * $pagina;
            }
            $query = "SELECT * FROM " . $this->table . " limit $inicio, $cantidad";
            $datos = parent::obtenerDatos($query);
            return ($datos);
        }
    
        public function obtenerArticulo($id){
            $query = "SELECT * FROM " . $this->table . " WHERE idArticulo = $id";
            return parent::obtenerDatos($query);
    
        }

        public function post($json){

            $_respuestas = new respuestas;
            $datos = json_decode($json,true);

            if(!isset($datos['catalogo']) || !isset($datos['nombre']) || !isset($datos['descripcion']) || !isset($datos['precio'])){
                return $_respuestas->error_400();
            }
            else{
                $this->catalogo = $datos['catalogo'];
                $this->nombre = $datos['nombre'];
                $this->descripcion = $datos['descripcion'];
                $this->precio = $datos['precio'];
                //if(isset($datos['telefono'])) { $this->telefono = $datos['telefono']; }
                $resp = $this->insertarArticulo();
                if($resp){
                    $respuesta = $_respuestas->response;
                    $respuesta["result"] = array(
                        "idArticulo" => $resp
                    );
                    return $respuesta;
                }else{
                    return $_respuestas->error_500();
                }
            }
        }

        private function insertarArticulo(){
            $query = "INSERT INTO " . $this->table . " (catalogo, nombre, descripcion, precio)
            values
            (" . $this->catalogo . ",'" . $this->nombre . "','" . $this->descripcion ."'," . $this->precio . ")"; 
            $resp = parent::nonQueryId($query);
            if($resp){
                return $resp;
            }else{
                return 0;
            }
        }

        public function put($json){

            $_respuestas = new respuestas;
            $datos = json_decode($json,true);

            $this->idArticulo = $datos['idArticulo'];
            if(isset($datos['catalogo'])) { $this->catalogo = $datos['catalogo']; }
            if(isset($datos['nombre'])) { $this->nombre = $datos['nombre']; }
            if(isset($datos['descripcion'])) { $this->descripcion = $datos['descripcion']; }
            if(isset($datos['precio'])) { $this->precio = $datos['precio']; }

            $resp = $this->modificarArticulo();
            
            if($resp){
                $respuesta = $_respuestas->response;
                $respuesta["result"] = array(
                    "idArticulo" => $this->idArticulo
                );
                return $respuesta;
            }else{
                return $_respuestas->error_500();
            }
        }

        private function modificarArticulo(){

        $query = "UPDATE " . $this->table . " SET catalogo = " . $this->catalogo . ", nombre = '" . $this->nombre . 
        "', descripcion = '" . $this->descripcion . "', precio = " . $this->precio . " WHERE idArticulo = " . 
        $this->idArticulo; 

            $resp = parent::nonQuery($query);
            if($resp >= 1){
                return $resp;
            }else{
                return 0;
            }
        }

        public function delete($json){

            $_respuestas = new respuestas;
            $datos = json_decode($json, true);

            if(!isset($datos['idArticulo'])){
                return $_respuestas->error_400();
            }else{
                $this->idArticulo = $datos['idArticulo'];
                $resp = $this->eliminarArticulo();
                if($resp){
                    $respuesta = $_respuestas->response;
                    $respuesta["result"] = array(
                        "idArticulo" => $this->idArticulo
                    );
                    return $respuesta;
                }else{
                    return $_respuestas->error_500();
                }
            }
        }

        private function eliminarArticulo(){
            $query = "DELETE FROM " . $this->table . " WHERE idArticulo = " . $this->idArticulo;
            $resp = parent::nonQuery($query);
            if($resp >= 1 ){
                return $resp;
            }else{
                return 0;
            }
        }

    }
