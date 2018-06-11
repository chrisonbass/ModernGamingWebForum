<?php 
namespace app\io;

class Csv {
  private $file = null;

  public function setFile($file){
    $this->file = __DIR__ . "/../../" . $file;
    if ( file_exists($this->file) ){
      return;
    } 
    // Create a blank file since one doesn't exist
    $this->writeArray(array());
    return;
  }
  
  public function toArray(){
    if ( !$this->isFileEmpty() ){
      return array_map( 'str_getcsv', file($this->file) );
    }
    return array();
  }

  public function isFileEmpty(){
    if ( $this->isFileValid() ){
      return filesize($this->file) ? false : true;
    }
    return true; 
  }

  public function isFileValid(){
    if ( !is_null($this->file) && file_exists($this->file) ){
      return true;
    }
    return false;
  }

  public function writeArray($data){
    if ( !$this->isFileValid() ){
      throw new \Exception("Invalid file for CSV to write: {$this->file}");
    }
    if ( is_array($data) ){
      $file = fopen($this->file, 'w');
      if ( !$file ){
        throw new \Exception("Error opening file");
      }
      foreach ( $data as $line ){
        fputcsv($file, $line);
      }
      fflush($file);
      fclose($file);
      chmod($this->file, 0777);
      clearstatcache();
      return;
    }
    throw new \Exception("Invalid data supplied to writeArray method.  2D array expected");
  }
}

?>
