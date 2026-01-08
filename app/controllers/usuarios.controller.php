<?php

require_once 'api.controller.php';
require_once './app/models/model.usuarios.php';
require_once './app/helper/api.helper.php';

class UsuariosController extends ApiController
{
    private $helper;
    private $model;

    function __construct()
    {
        parent::__construct();
        $this->model = new UsuariosModel();
        $this->helper = new AuthHelper();
    }

    private function hasher($unhashed)
    {
        return password_hash($unhashed, PASSWORD_BCRYPT);
    }

    private function validateToken($user)
    {
        if (!$user) {
            $this->view->response("Sesion no iniciada", 401);
            exit;
        }
    }

    # Controlar: crear usuario
    function usuarioNuevo()
    {
        try {
            $uu = $this->getData();

            # Valida si los campos del usuario estan completos
            if (empty($uu->username) || empty($uu->email) || empty($uu->password)) {
                $this->view->response("Campos incompletos", 400);
                return;
            }

            # Hasheo de password 
            $password = $this->hasher($uu->password);

            # Si sale todo bien entonces mandamos los datos al modelo
            $id = $this->model->crearUsuario($uu->username, $uu->email, $password);
            if ($id !== 0) {
                $this->view->response("Usuario: " . $uu->username . " id: " . $id . " creado con exito", 201);
                return;
            } else {
                $this->view->response("La creacion del usuario falló", 400);
                return;
            }
        } catch (\Throwable $th) {
            $this->view->response("Usuario duplicado", 400);
            return;
        }
    }

    # Controlar: Obtener un usuario
    function getToken()
    {

        # Entrega el header de la request
        $basic = $this->helper->getAuthHeaders();

        # Verificacion del contenido del header
        if (empty($basic)) {
            $this->view->response('No se enviaron encabezados', 401);
            return;
        }

        # Separacion de la palabra clave del par user y pass
        $basic = explode(" ", $basic);

        # Verificacion del modo de autenticacion
        if ($basic[0] != "Basic") {
            $this->view->response('Los encabezados de autenticacion son incorrectos', 401);
            return;
        }

        # Decodificacion
        $userpass = base64_decode($basic[1]);
        # Desglose de user y pass
        $userpass = explode(":", $userpass);

        # Asignacion de user y pass a cada variable
        $user = $userpass[0];
        $pass = $userpass[1];

        # Datos del usuarios traidos de la base de datos
        $userdata = $this->model->obtenerUsuario($user);

        if (empty($userdata)) {
            $this->view->response('El usuario no existe.', 404);
            return;
        }
        //$userdata = [ "name" => $user, "id" => 123, "role" => 'ADMIN' ]; // Llamar a la DB
        if ($user === $userdata->username && password_verify($pass, $userdata->password)) {
            // Usuario es válido
            unset($userdata->password);
            $userdataObject = json_decode(json_encode($userdata), true);
            $token = $this->helper->createToken($userdataObject);
            $this->view->response($token, 200);
        } else {
            $this->view->response('Datos incorrectos.', 401);
            return;
        }
    }

    # Controlar: Actualizar un usuario
    function modificarUsuario()
    {
        try {
            # Validar inicio de sesion, validar token
            $token = $this->helper->currentUser();
            $this->validateToken($token);

            # Si sale todo bien enviamos los datos nuevos
            $modificado = $this->getData();

            # Valida si los campos vinieron con data o no
            if (empty($modificado->username) || empty($modificado->email) || empty($modificado->password)) {
                $this->view->response("Los campos estan incompletos", 400);
                return;
            }

            # Hashear password de nuevo
            $password = $this->hasher($modificado->password);

            # Obtencion del id del usuario

            # Se mandan los datos
            $this->model->actualizarUsuario($token->id, $modificado->username, $modificado->email, $password);
        } catch (\Throwable $th) {
            $this->view->response("Ocurrio un error durante la actualizacion del usuario", 400);
            return;
        }
    }

    # Controlar: Eliminar un usuario
    function borrarUsuario()
    {

        # Obtencion del token
        $token = $this->helper->currentUser();
        # Valida si llega el token, si esta vacio devuelve un error
        $this->validateToken($token);

        # Si sale todo bien borramos el usuario
        $this->model->eliminarUsuario($token->id);
    }

    # Controlar: Obtener todos los usuarios (gestion de usuarios)
    
}