<?php

class FilesManager
{
    public static function UploadFotoPedido($file, $pedido, $mesa)
    {
        try {

            $nombreArchivo = $pedido->codigo_pedido . "_" . $mesa->codigo_mesa . "_" . $pedido->cliente;

            $destino = "./fotos/";
            if (!file_exists($destino)) {
                mkdir($destino, 0777, true);
            }

            $nombreAnterior = $file['archivo']->getClientFilename();
            $extension = explode(".", $nombreAnterior);
            $extension = array_reverse($extension);

            $file['archivo']->moveTo($destino . $nombreArchivo . "." . $extension[0]);

            $foto = $nombreArchivo . "." . $extension[0];

            Pedido::ActualizarFoto($pedido->id_pedido, $foto);

            return true;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>