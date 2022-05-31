<?php

/* 

Sinnott Segura Gonzalo

*/

class Upload{
    private $_DIR_TO_SAVE = 'uploads/';
    private $_fileName;
    private $_fileExtension;
    private $_newFileName;
    private $_pathToSaveImage;

    public function __construct($array)
    {
        if (!file_exists($this->_DIR_TO_SAVE)) {
            mkdir($this->_DIR_TO_SAVE, 0777, true);
        }
        $this->saveFileIntoDir($array);
    }
    
    
    public function setFileName($filename){
        $this->_fileName = $filename;
    }

   
    public function setFileExtension($fileExtension){
        $this->_fileExtension = $fileExtension;
    }

    public function setNewFileName($newFileNameArray){
        $this->_newFileName = $newFileNameArray[0] . '_' . date('Y_m_d__H_i_s', time()) . '.' . $this->getFileExtension();
    }

    public function setPathToSaveImage(){
        $this->_pathToSaveImage = $this->_DIR_TO_SAVE. $this->getNewFileName();
    }
    
    public function getFileName(){
        return $this->_fileName;
    }

    public function getFileExtension(){
        return $this->_fileExtension;
    }

    public function getNewFileName(){
        return $this->_newFileName;
    }

    public function getPathToSaveImage(){
        return $this->_pathToSaveImage;
    }

    public function saveFileIntoDir($array):bool{
        $success = false;
        $newFileNameArray = explode('.', $array['image']['name']);
        try {
            $this->setFileName($array['image']['name']);
            $this->setFileExtension(end($newFileNameArray));
            $this->setNewFileName($newFileNameArray);
            $this->setPathToSaveImage();
            if ($this->moveUploadedFile($array['image']['tmp_name'])) {
                $success = true;
            }
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }finally{
            return $success;
        }
    }

    public function moveUploadedFile($tmpFileName){
        return move_uploaded_file($tmpFileName, $this->getPathToSaveImage());
    }
}

?>