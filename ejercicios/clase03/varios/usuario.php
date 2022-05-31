<?php

class Usuario
{
    public $_usuario;
    public $_contrasena;

    public function _construct ($usuario, $contrasena)
    {
        $this->_usuario = $usuario;
        $this->_contrasena = $contrasena;
    }

    public static function MostrarUsuario(Usuario $usuario)
    {
        echo ($usuario->_usuario);
        echo ($usuario->_contrasena);
    }
    
    public function GuardarCSV()
    {
        $archivo = fopen("usuarios.txt", "a");
        $usuarioArray = array($this->_usuario,$this->_contrasena);

        $usuarioCSV = implode(',',$usuarioArray);
        fwrite($archivo,$usuarioCSV);

        fclose($archivo);
    }
}

?>