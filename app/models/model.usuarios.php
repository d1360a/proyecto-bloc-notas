<?php

require_once 'model.php';

class UsuariosModel extends Model{
    
    # Obtener un usuario
    function obtenerUsuario($username){
        $sql = $this->db->prepare("SELECT * FROM usuarios WHERE username = ?");
        $sql->execute([$username]);
        $usuario = $sql->fetch(PDO::FETCH_OBJ);
        return $usuario;
    }

    # Crear un usuario
    function crearUsuario($username, $email, $password){
        $sql = $this->db->prepare("INSERT INTO usuarios(username,email,password) VALUES(?,?,?)");
        $sql->execute([$username, $email, $password]);
        return $this->db->lastInsertId();
    }

    # Actualizar datos de usuario
    function actualizarUsuario($id, $username, $email, $password){
        $sql = $this->db->prepare("UPDATE usuarios SET username = ?, email = ?, password = ? WHERE id = ?");
        $sql->execute([$username, $email, $password, $id]);
    }

    # Eliminar un usuario
    function eliminarUsuario($id){
        $sql = $this->db->prepare("DELETE FROM usuarios WHERE id = ?");
        $sql->execute([$id]);
    }

    # Obtener todos los usuarios (Para futuro panel de gestion de usuarios)
    function obtenerUsuarios(){
        $sql = $this->db->prepare("SELECT * FROM usuarios");
        $sql->execute();
        $usuarios = $sql->fetchAll(PDO::FETCH_OBJ);
        return $usuarios;
    }
}