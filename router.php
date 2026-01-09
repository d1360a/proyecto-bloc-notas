<?php

// Incluir controladores


// incluir libs
require_once "./libs/router.php";

$router = new Router();

// Tabla de ruteo addRoute(URL/:ID, GET|POST|DELETE|PUT|PATCH, Controlador, Metodo(del controlador))
$router->addRoute(); // nueva nota
$router->addRoute(); // traer nota
$router->addRoute(); // eliminar nota
$router->addRoute(); // editar nota completa
$router->addRoute(); // traer todas las notas

// Tabla de ruteo de rutas auth
$router->addRoute(); // crear usuario
$router->addRoute(); // obtener token (iniciar sesion)
$router->addRoute(); // eliminar usuario (de la DB)
$router->addRoute(); // editar informacion usuario