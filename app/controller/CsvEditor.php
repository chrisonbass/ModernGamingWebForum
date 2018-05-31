<?php

namespace app\controller;

use app\App;
use app\base\Controller;
use app\io\Csv;
use app\view\Table;
use app\view\Input;
use app\view\Alert;
use app\view\Csv as CsvView;

class CsvEditor extends Controller {
  public function getTitle(){
    switch ( $this->action ){
      case "index":
        return "CSV Editor";
      default: 
        return parent::getTitle();
    }
  }

  public function headerIndex(){
    return "";
  }

  private function getDefaultData(){
    return array(
      array( "Tim Johnson", "32", "123 Elm St", "Brooklyn", "NY" ),
      array( "Alex Smith", "21", "1 Acres St", "Huntsville", "AL" ),
      array( "Jerry Wayne", "45", "45 Montrose Ave", "Johnson City", "TN" ),
      array( "Mandy Williams", "30", "7 Bedford Drive", "Lancaster", "PA" ),
      array( "Mark Yander", "28", "3 Highland St", "Brooklyn", "NY" )
    );
  }

  public function actionIndex(){
    $app = App::app();
    $csv = new Csv();
    $csv->setFile("assets/database.csv");
    $rows = array();
    $columns = array(
      "Name", "Age", "Address", "City", "State", ""
    );
    if ( $csv->isFileEmpty() ){
      $rows = $this->getDefaultData();
    } else {
      $rows = $csv->toArray();
    }
    foreach ( $rows as $ri => &$row ){
      foreach ( $row as $ci => &$cell ){
        $cellValue = "" . $cell;
        $cell = new Input(array(
          "name" => "rows[$ri][$ci]",
          "value" => $cell
        ) );
        //$cell = $cell->render();
      }
      $new_cell = new Input(array(
        "type" => "button",
        "button_type" => "submit",
        "bs_class" => "danger",
        "name" => "action",
        "value" => "delete-" . $ri,
        "label" => "Delete"
      ) );
      //$row[] = $new_cell->render();
      $row[] = $new_cell;
    }
    $table = new Table(array(
      "columns" => $columns,
      "rows" => $rows
    ) );
    $alert = "";
    if ( $msg = $app->getMessage() ){
      $alert = new Alert($msg);
    }
    return new CsvView(array(
      "alert" => $alert
    ), $table);
  }

  public function actionUpdate(){
    $app = App::app();
    $data = $app->request->post();
    $rows = $data['rows'];
    // Add Blank Row
    if ( $data['action'] == 'add-row' ){
      $rows[] = array(
        "", "", "", "", ""
      );
      $app->setMessage("Added new Row", "success");
    } else if ( preg_match("/^delete\-/", $data['action']) ){
      $row_id = preg_replace("/[^\d]/", "", $data['action']);
      if ( is_numeric($row_id) && isset($rows[$row_id]) ){
        if ( count($rows) == 5 ){
          $app->setMessage("Error deleting row.  CSV File must contain at least 5 records", "danger");
        } else {
          array_splice($rows, $row_id, 1);
          $app->setMessage("Row deleted", "success");
        }
      } else {
        $app->setMessage("Error deleting Row", "danger");
      }
    } else {
      $app->setMessage("CSV Updated", "success");
    }
    $csv = new Csv();
    $csv->setFile("assets/database.csv");
    $csv->writeArray($rows);
    header("location: index.php?controller=csv-editor");
    exit;
  }
}
