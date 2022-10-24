<?php

namespace Model;

class Usuarios extends ActiveRecord
{

    protected static $tabla = 'usuarios';

    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    public function validar(): array
    {
        if (!$this->nombre) {
            self::$alertas['error'][] = "El nombre es obligatorio";
        }
        if (!$this->apellido) {
            self::$alertas['error'][] = "El apellido es obligatorio";
        }
        if (!preg_match('/[0-9]{10}/', $this->telefono)) {
            self::$alertas['error'][] = "El telefono debe tener 10 cifras";
        }
        if (!$this->email) {
            self::$alertas['error'][] = "El email es obligatorio";
        }
        if (!$this->password) {
            self::$alertas['error'][] = "La contraseña es obligatoria";
        }
        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = "La contraseña debe tener más de 6 caracteres";
        }

        return self::$alertas;
    }

    public function validarLogin()
    {
        if (!$this->email) {
            self::$alertas['error'][] = "El email es obligatorio";
        }
        if (!$this->password) {
            self::$alertas['error'][] = "La contraseña es obligatoria";
        }

        return self::$alertas;
    }

    public function validarOlvide()
    {
        if (!$this->email) {
            self::$alertas['error'][] = "El email es obligatorio";
        }

        return self::$alertas;
    }

    public function validarRestablecer()
    {
        if (!$this->password) {
            self::$alertas['error'][] = "La contraseña es obligatoria";
        }
        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = "La contraseña debe tener más de 6 caracteres";
        }
        return self::$alertas;
    }

    //Revisa si el usuario ya existe
    public function existeUsuario()
    {
        $query = " SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1 ";

        $resultado = self::$db->query($query);

        return $resultado;
    }

    public function hashPassword()
    {
        //Algoritmo por default para hacer hash
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken()
    {
        //Un id rápido
        $this->token = uniqid();
    }

    public function comprobarDatos(array $resultado): bool
    {
        if (password_verify($this->password, $resultado['password'])) {
            if ($resultado['confirmado'] === '1') {
                return true;
            } else {
                self::setAlerta('error', 'email no confirmado, revise su correo');
                return false;
            }
        } else {
            self::setAlerta('error', 'contraseña incorrecta');
            return false;
        }
    }
}
