<?php
    include_once 'app/models/model.php';

    class ModelBlog extends Model{
        
        //get
        function getNotitas(){

            //solicitud  SQL
            $sql = " SELECT * FROM notas";

            $query = $this->db->prepare($sql);

            $query->execute();

            // obtengo todos los resultados de la consulta que arroja la query
            return $query->fetchAll(PDO::FETCH_OBJ);
        }

        //post
        function postNotitas($titulo,$contenido,$fecha){
             // 2. 
            $insertVehiculo = $this->db->prepare("INSERT INTO notas(titulo,contenido,fecha) VALUES (?,?,?)");
            
            $insertVehiculo->execute([$titulo,$contenido,$fecha]);

            return $this->db->lastInsertId();
        }
        
        //delete
        function deleteNota($id) {
            $query = $this->db->prepare('DELETE FROM notas WHERE id = ?');
            $query->execute([$id]);
        }

        //get/id
        function getNotitaID($id){

             // 2. consulta SQL (SELECT * FROM vehiculos)
            $query = $this->db->prepare('SELECT * FROM notas WHERE id = ?');

            $query->execute([$id]);
            
            // obtengo rl resultado de la consulta que arroja la query
            return $query->fetch(PDO::FETCH_OBJ);
        }

        //put
        function putNotita($titulo,$contenido,$fecha,$id){
            $query = $this->db->prepare('UPDATE notas SET titulo = ?, contenido = ?, fecha = ? WHERE id = ?');
            $query->execute([ $titulo,$contenido,$fecha,$id]);
        }

        //patch

    }



?>