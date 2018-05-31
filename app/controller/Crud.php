<?php

namespace app\controller;

use app\App;
use app\base\Controller;
use app\util\Text;
use app\util\ArrayHelper;
use app\db\ActiveRecord;
use app\view\Table;
use app\view\Home;
use app\view\Input;
use app\view\SummaryPage;
use app\view\Link;

/**
 * Extending this class with a controller
 * whose model extends app\db\ActiveRecord
 * will create default CRUD pages
 * for that model
 */
abstract class Crud extends Controller {
  abstract function modelClass();

  /**
   * The title that appears below
   * the navigation bar
   * Override to customize the return value
   */
  public function getTitle(){
    $app = App::app();
    $req = $app->request;
    $model_class = $this->modelClass();
    switch ( $this->action ){
      case "Index":
        return Text::classNameToName($this->modelClass()) ." List";
      case "Create":
        return "New " . Text::classNameToName($this->modelClass());
      case "Edit":
        return "Edit " . Text::classNameToName($this->modelClass());
      case "Delete":
        return "Delete " . Text::classNameToName($this->modelClass());
      case "View":
        $data = $req->get();
        if ( isset($data['id']) ){
          $model = new $model_class($data['id']);
          if ( $model && $model->id ){
            return $model->getLabel();
          }
        }
        return Text::classNameToName($this->modelClass());
      default: 
        return parent::getTitle();
    }
  }

  /**
   * Returns an associative array with the 
   * field names as indexes.  The array
   * contains information that links one
   * table to another.  For example, the 
   * user_id field in the app\model\UserRole
   * class it related to the app\model\User
   * model.  This method must be overriden
   * @return array
   */
  public function getRelatedFields(){
    return array();
  }

  /**
   * Default list page
   */
  public function actionIndex(){
    $app = App::app();
    $model_name = $this->modelClass();
    $model = new $model_name();
    $controller = Text::classToModelName($this->modelClass());
    $rows = $model->allArray();
    $fields = $model->getFields();
    $related = $this->getRelatedFields();
    foreach ( $rows as &$row ){
      $id = $row[0];
      //array_splice($row, 0,1);
      $delete_button = new Link( array(
        "label" => "Delete",
        "url" => "?controller=" . $controller . "&action=delete&id=" . $id,
        "class" => "btn btn-danger"
      ) );
      $row[] = $delete_button;

      foreach ( $fields as $index => $field ){
        if ( $index > 0 && isset($related[$field]) ){
          $rel = $related[$field];
          if ( isset($rel['class_name']) && class_exists($rel['class_name']) ){
            $class_name = $rel['class_name'];
            $field_value = $row[$index];
            $object = new $class_name($field_value);
            if ( $object && $object->id && $object instanceof ActiveRecord ){
              $row[$index] = $object->getLabel();
            }
          }
        } else if ( $index == 0 ){
          $row[$index] = new Link(array(
            "url" => "index.php?controller={$controller}&action=view&id={$row[$index]}",
            "label" => $row[$index]
          ) );
        }
      }
    }
    $columns = $model->getFields();
    //array_splice($columns, 0,1);
    $columns[] = "Action";
    foreach ( $columns as &$c ){
      $c = Text::modelFieldToLabel($c);
    }
    $table = new Table(array(
      "columns" => $columns,
      "rows" => $rows
    ) );

    $children = "<p>" . new Link(array(
      "class" => "btn btn-primary",
      "url" => "?controller={$controller}&action=create",
      "label" => "Create New"
    ) ) . "</p>";
    $children .= $table;
    return new Home(array(
      "title" => $this->getTitle(),
    ), $children);
  }
  /**
   * Default create page
   */
  public function actionCreate(){
    $controller = Text::classToModelName($this->modelClass());
    $name = Text::classNameToName($this->modelClass());
    $app = App::app();
    $model_name = $this->modelClass();
    $model = new $model_name();
    if ( $app->request->isPost() ){
      $data = $app->request->post();
      if ( isset($data['submit']) ){
        switch ( $data['submit'] ){
          case "submit":
            foreach ( $model->getFields() as $field ){
              if ( isset($data[$field]) ){
                $model->$field = $data[$field];
              }
            }
            $model->save();
            $app->setMessage("New " . $name . " Created", "success");
            break;
          case "cancel":
            break;
        }
        header("location: index.php?controller=" . $controller);
        exit;
      }
    }
    $form = $this->getModelForm($model);
    return new Home(array(
      "title" => "Create new " . Text::classNameToName($this->modelClass()),
      "links" => array(
        new Link(array(
          "url" => "index.php?controller={$controller}",
          "label" => "{$name} List"
        ) )
      )
    ), $form);
  }

  /**
   * Default edit page
   */
  public function actionEdit(){
    $controller = Text::classToModelName($this->modelClass());
    $name = Text::classNameToName($this->modelClass());
    $app = App::app();
    $model_name = $this->modelClass();
    $model = new $model_name($app->id);
    if ( $app->request->isPost() ){
      $data = $app->request->post();
      if ( isset($data['submit']) ){
        switch ( $data['submit'] ){
          case "submit":
            $model = new $model_name($data['id']);
            if ( $model && $model->id ){
              foreach ( $model->getFields() as $field ){
                if ( isset($data[$field]) ){
                  $model->$field = $data[$field];
                }
              }
              $model->save();
              $app->setMessage($name . " Updated", "success");
            } else {
              $app->setMessage("An error while attempting to update the {$name}", "danger");
            }
            break;
          case "cancel":
            break;
        }
        header("location: index.php?controller=" . $controller);
        exit;
      }
    }
    $form = $this->getModelForm($model);
    return new Home(array(
      "title" => "Edit " . Text::classNameToName($this->modelClass())
    ), $form);
  }

  /**
   * Default view page
   */
  public function actionView(){
    $controller = Text::classToModelName($this->modelClass());
    $name = Text::classNameToName($this->modelClass());
    $app = App::app();
    $model_name = $this->modelClass();
    $model = new $model_name($app->id);
    if ( $model && $model->id ){
      $body = null;
      $properties = array();
      foreach ( $model->getFields() as $field ){
        $properties[] = array(
          "label" => Text::modelFieldToLabel($field),
          "value" => $model->$field
        );
      }
      $body = new SummaryPage(array(
        "properties" => $properties
      ) );
      return new Home(array(
        "title" => $model->getLabel(),
        "links" => array(
          new Link(array(
            "url" => "index.php?controller={$controller}",
            "label" => "{$name} List"
          ) ),
          new Link(array(
            "url" => "index.php?controller={$controller}&action=delete&id={$app->id}",
            "class" => "btn btn-danger",
            "label" => "Delete"
          ) ),
        )
      ), $body );
    }
    throw new \Exception("Invalid Model");
  }

  /**
   * Default delete page
   */
  public function actionDelete(){
    $app = App::app();
    $model_name = $this->modelClass();
    $name = Text::classNameToName($model_name);
    if ( $app->request->isPost() ){
      $data = $app->request->post();
      if ( isset($data['delete']) ){
        switch ( $data['delete'] ){
          case "yes":
            $model = new $model_name($data['id']);
            $app->setMessage("Error deleting " . $name, "danger");
            if ( $model && $model->id ){
              $app->setMessage($name . " Deleted", "success");
              $model->delete();
            }
            break;

          case "no":
            $app->setMessage("Cancelled " . $name . " Deletion", "success");
            break;
        }
        header("location: index.php?controller=" . Text::classToModelName($this->modelClass()));
        exit;
      }
    }
    $id = $app->id;
    $model = new $model_name($id);  
    $body = "<p>Are you sure you want to delete this object?</p>";
    foreach ( $model->getFields() as $field ){
      $lbl = Text::modelFieldToLabel($field);
      $body .= "<div><strong>$lbl</strong>: {$model->$field}</div>";
    }
    $controller = Text::classToModelName($this->modelClass());
    $body .= "<form action='?controller={$controller}&action=delete' method='post'>";
    $body .= new Input(array(
      "type" => "hidden",
      "name" => "id",
      "value" => $model->id
    ));
    $body .= new Input(array(
      "type" => "button",
      "name" => "delete",
      "value" => "yes",
      "bs_class" => "danger",
      "label" => "Yes"
    ));
    $body .= new Input(array(
      "type" => "button",
      "name" => "delete",
      "value" => "no",
      "bs_class" => "info",
      "label" => "No"
    ));
    $body .= "</form>";
    return new Home(array(
      "title" => "Delete {$name}: " . $model->id
    ), $body); 
  }

  public function getModelForm($model){
    $controller = Text::classToModelName($this->modelClass());
    $type = "create";
    if ( $model->id ){
      $type = "edit";
    }
    $action = "?controller={$controller}&action=" . $type;
    $inputs = array();
    $related = $this->getRelatedFields();
    foreach ( $model->getFields() as $field ){
      $def = array(
        "type" => "text",
        "name" => $field,
        "value" => $model->$field
      );
      if ( $field == "id" ){
        if ( $model->id ){
          $def['type'] = "hidden";
        } else {
          continue;
        }
      }
      if ( isset($related[$field]) ){
        $rel = $related[$field];
        if ( isset($rel['class_name']) && class_exists($rel['class_name']) ){
          $def['type'] = "select";
          $relClass = $rel['class_name'];
          $relObj = new $relClass();
          $options = array();
          $filter = null;
          if ( isset($rel['filter']) && is_callable($rel['filter']) ){
            $filter = $rel['filter'];
          }
          foreach ( $relObj->all($filter) as $obj ){
            $options[] = array(
              "value" => $obj->id,
              "label" => $obj->getLabel()
            );
          }
          $def['options'] = $options;
        }
      }
      $field_label = Text::modelFieldToLabel($def['name']);
      if ( $def['type'] == "hidden" ){
        $inputs[] = new Input($def);
      } else {
        $inputs[] = "<div class='form-group'>" . 
          "<label for='{$field}'>{$field_label}</label>" . 
          new Input($def) .
          "</div>";
      }
    }
    $btn = array(
      "type" => "button",
      "name" => "submit",
      "value" => "submit",
      "label" => "Submit"
    );
    $btn_label = "Create";
    if ( $this->action == "Edit" ){
      $btn_label = "Update";
    }
    $sub = ArrayHelper::merge($btn, array(
      "bs_class" => "primary",
      "label" => $btn_label
    ) );
    $can = ArrayHelper::merge($btn, array(
      "bs_class" => "info",
      "value" => "cancel",
      "label" => "Cancel"
    ) );
    $inputs[] = "<div class='form-group'>" . 
      new Input($sub) .
      new Input($can) .
      "</div>";
    return "<form class='form' action='{$action}' method='post'>\n" . implode("\n", $inputs) . "\n</form>";
  }
}
