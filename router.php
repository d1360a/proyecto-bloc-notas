<?php

// Incluir controladores
require_once './app/controllers/usuarios.controller.php';

// incluir libs
require_once "./libs/router.php";

$router = new Router();

// Tabla de ruteo addRoute(URL/:ID, GET|POST|DELETE|PUT|PATCH, Controlador, Metodo(del controlador))
// $router->addRoute(); // nueva nota
// $router->addRoute(); // traer nota
// $router->addRoute(); // eliminar nota
// $router->addRoute(); // editar nota completa
// $router->addRoute(); // traer todas las notas

// Tabla de ruteo de rutas auth
$router->addRoute("user", "POST", "UsuariosController", "usuarioNuevo"); // crear usuario
$router->addRoute("user", "PUT", "UsuariosController", "modificarUsuario"); // obtener token (iniciar sesion)
$router->addRoute("user", "GET", "UsuariosController", "getToken"); // eliminar usuario (de la DB)
$router->addRoute("user", "DELETE", "UsuariosController", "borrarUsuario"); // editar informacion usuario

$router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);