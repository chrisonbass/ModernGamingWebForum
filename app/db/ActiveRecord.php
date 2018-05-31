<?php
namespace app\db;

use app\io\Csv;
use app\util\Text;
use app\base\Model;
use app\base\Event;

abstract class ActiveRecord extends Model {
  public $id = null;

  const EVENT_BEFORE_SAVE = 'beforeSave';

  const EVENT_AFTER_SAVE = 'afterSave';

  const EVENT_BEFORE_DELETE = 'beforeDelete';

  const EVENT_AFTER_DELETE = 'beforeDelete';

  abstract public function tableName();

  /**
   * PHP Magic constructor method. 
   * @param $id (optional string) the id for 
   *        the object your wish to initialize.  
   */ 
  public function __construct($id = null){
    // When provided an id, search
    // for the record and populate
    // the properties of this object
    // with the data
    if ( !is_null($id) ){
      $table_data = $this->allArray();
      $found = false;
      foreach ($table_data as $row){
        if ( $id == $row[0] ){
          $this->populateFromCsvRow($row);
          $found = true;
          break;
        }
      }
      if ( !$found ){
        $this->id = null;
      }
    }
    parent::__construct();
  }

  /**
   * Creates a unique id for a new entry
   */
  private function createUniqueId(){
    $csv = new Csv();
    $csv->setFile($this->getCsvFileName());
    // list of current ids
    $id_list = array();
    $table_data = $csv->toArray();
    foreach ($table_data as $row ){
      $id_list[] = $row[0];
    }
    // start with a general unique id
    $id = uniqid();
    // 
    while ( in_array($id, $id_list) ){
      $id = uniqid();
    }
    return $id;
  }

  /**
   * Saves a record into the CSV
   * table with all records of this
   * type.  Also, updates an entry
   * if it already existed or creates
   * a new row if it is new
   */
  public function save(){
    $this->trigger(
      new Event(static::EVENT_BEFORE_SAVE, null, $this)
    );
    $is_new = false;
    if ( is_null( $this->id ) ){
      $this->id = $this->createUniqueId();
      $is_new = true;
    }

    // Get all properties of this object
    $fields = $this->getFields();

    // Data to be stored in CSV
    $data = array();
    foreach ( $fields as $field ){
      if ( is_null($this->$field) ){
        $data[] = "";
        continue;
      }
      $data[] = $this->$field;
    }
    // Cleans data by removing replacing
    // quotes with the HTML character code
    // for a quotation mark
    foreach ( $data as &$cell ){
      $cell = str_replace('"', '&quot;', $cell);
    }

    // Create "connection" to database
    // by retrieving the CSV file that
    // corresponds to this table name
    // and it's data
    $csv = new Csv();
    $csv->setFile($this->getCsvFileName());
    $table_data = $csv->toArray();

    // If this isn't a new entry
    // find the record by id, and 
    // replace that record with the 
    // newly created one
    $found_id = null;
    if ( $is_new === false ){
      foreach ( $table_data as $row_id => $row ){
        // The first entry in every table will be
        // the unique id for that row
        if ( $row[0] == $data[0] ){
          $found_id = $row_id;
          break;
        }
      }
    }

    // If new entry, append to current data
    if ( is_null($found_id) ){
      $table_data[] = $data;
    } 
    // else replace the original row with new data
    else {
      $table_data[$found_id] = $data;
    }
    $csv->writeArray($table_data);

    $this->trigger(
      new Event(static::EVENT_AFTER_SAVE, null, $this)
    );
    return $this->id;
  }

  public function delete(){
    $this->trigger(
      new Event(static::EVENT_BEFORE_DELETE, null, $this)
    );
    if ( $this->id ){
      $csv = new Csv();
      $csv->setFile($this->getCsvFileName());
      $table_data = $csv->toArray();
      $found_id = null;
      foreach ( $table_data as $row_id => $row ){
        if ( $row[0] == $this->id ){
          $found_id = $row_id;
        }
      }
      if ( !is_null($found_id) ){
        array_splice($table_data, $found_id, 1);
        $csv->writeArray($table_data);
        $this->trigger(
          new Event(static::EVENT_AFTER_DELETE, null, $this)
        );
        return true;
      }
    }
    return false;
  }

  /**
   * Returns the fields in this "table"
   * The named properties of an object
   * that extends this class are the
   * fields that will be stored in the
   * csv.  This function also makes sure
   * that the id field is listed first
   */
  public function getFields(){
    $fields = array();
    foreach ( $this->getProperties() as $field => $value ){
      $fields[] = $field;
    }
    $id_index = null;
    foreach ( $fields as $index => $field ){
      if ( $field == "id" ){
        $id_index = $index;
        break;
      }
    }
    if ( !is_null($id_index) ){
      array_splice($fields, $id_index, 1);
      array_splice($fields, 0, 0, "id");
    }
    return $fields;
  }

  /**
   * Returns the string name for the
   * CSV that corresponds to this "table"
   */
  public function getCsvFileName(){
    return "assets/db/" . $this->tableName() . ".csv";
  }

  /**
   * Retrieves all records from CSV 
   * in Object form.
   * @param $callable : CALLABLE FUNCTION : that should
   * return true or false.  If true, the record will be 
   * include in the results.  Acts as a way to filter 
   * results
   */
  public function all( $callable = null ){
    $csv_data = $this->allArray();
    $data = array();
    foreach ( $csv_data as $cd ){
      $class_name = get_class($this);
      $record = new $class_name();
      if ( is_subclass_of($record, "app\\db\\ActiveRecord") ){
        $record->populateFromCsvRow($cd);
        if ( is_callable($callable) ){
          if ( call_user_func($callable, $record) ){
            $data[] = $record;
          }
        } else {
          $data[] = $record;
        }
      }
    }
    return $data;
  }

  /**
   * Returns all records in the corresponding
   * CSV table as a simple array
   * @param $callable : CALLABLE function that should
   * return true or false.  If true, the record will be 
   * include in the results.  Acts as a way to filter 
   * results
   */
  public function allArray( $callable = null ){
    $csv = new Csv();
    $csv->setFile($this->getCsvFileName());
    $data = array();
    $csv_data = $csv->toArray();
    foreach ( $csv_data as $cd ){
      if ( $callable && is_callable($callable) ){
        if ( $callable($cd) ){
          $data[] = $cd;
        }
      } else {
        $data[] = $cd;
      }
    }
    return $data;
  }

  /**
   * Populates the fields in this object
   * from a row of data in a CSV file
   */
  public function populateFromCsvRow($row){
    $fields = $this->getFields();
    $index = 0;
    foreach ( $fields as $field ){
      if ( array_key_exists($index, $row) ){
        if ( $row[$index] == "" ){
          $this->$field = null;
        } else {
          $this->$field = $row[$index];
        }
      } else {
        $this->$field = NULL;
      }
      $index++;
    }
  }

  public function getLabel(){
    $label = Text::classNameToName(get_class($this));
    if ( $this->id ){
      $label .= " : " . $this->id;
    }
    return $label;
  }
}
