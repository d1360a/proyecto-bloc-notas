<?php 
    include_once 'app/models/model.blog.php';

    class ControlerBlog {
        private $model;

        function __construct(){
            $this->model = new ModelBlog();
        }

        /////traer todas las notas
        function getdbblogNotas($req , $res){
            
            $datosDB = $this->model->getNotitas();

            return $res->json($datosDB,200);
        }
        /////traer una nota
        function getIDblogNotas($req,$res){
            $id = $req->params->id;

            $nota = $this->model->getNotitaID($id);

            if (!$nota) {
                return $res->json("la nota con el id = $id no existe",404);
            }

            return $res->json($nota,200);
        }
        //////agregar
        function postblogNotas($req,$res){
            $body = $req->body;

            if (empty($body->titulo)) {
                return $res->json("faltan datos", 400);
            }

            if (empty($body->contenido)) {
                return $res->json("faltan datos", 400);
            }

            if (empty($body->fecha)) {
                return $res->json("faltan datos", 400);
            }

            $titulo = $body->titulo;
            $contenido = $body->contenido;
            $fecha = $body->fecha;

            $nuevaNota = $this->model->postNotitas($titulo,$contenido,$fecha);

            if ($nuevaNota == null) {
                return $res->json('Error del servidor', 500);
            }

            return $res->json("Nota creado con exito",201);
        }

        function putblogNotas($req,$res){

            $id = $req->params->id;
            $body = $req->body;

            $nota = $this->model->getNotitaID($id);

            if (!$nota) {
                return $res->json("la nota con el id = $id no existe",404);
            }

            if (empty($body->titulo)) {
                return $res->json("faltan datos", 400);
            }

            if (empty($body->contenido)) {
                return $res->json("faltan datos", 400);
            }

            if (empty($body->fecha)) {
                return $res->json("faltan datos", 400);
            }

            $titulo = $body->titulo;
            $contenido = $body->contenido;
            $fecha = $body->fecha;

            $nuevaNota = $this->model->putNotita($titulo,$contenido,$fecha,$id);

            if ($nuevaNota == null) {
                return $res->json('Error del servidor', 500);
            }

            return $res->json("Nota creado con exito",201);
        }

    //////eliminar
        function deletblogNotas($req, $res) {
            $id = $req->params->id;
            $nota = $this->model-> getNotitaID($id);

            if (!$nota) {
                return $res->json("La nota con el id= $id no existe", 404);
            }
            
            $this->model->deleteNota($id);
            
            return $res->json("La nota id= $id eliminado con éxito", 204);
        }
    }
