<?php

$models = [
  "User",
  "UserRole",
  "Role",
  "Board",
  "Topic",
  "Comment",
  "LikedBy"
];

$custom_user_role = <<<EOT

  public function getRelatedFields(){
    return array(
      "user_id" => array(
        "class_name" => "app\\model\\User",
      ),
      "role_id" => array(
        "class_name" => "app\\model\\Role",
      ),
    );
  }

EOT;

$custom_comment = <<<EOT

  public function getRelatedFields(){
    return array(
      "topic_id" => array(
        "class_name" => "app\\model\\Topic",
      )
    );
  }

EOT;

$custom_topic = <<<EOT

  public function getRelatedFields(){
    return array(
      "board_id" => array(
        "class_name" => "app\\model\\Board",
      ),
      "created_by" => array(
        "class_name" => "app\\model\\User"
      )
    );
  }

EOT;

$custom = array(
  "Topic" => $custom_topic,
  "UserRole" => $custom_user_role,
  "Comment" => $custom_comment
);

$template = file_get_contents(__DIR__ . '/controller_template.txt');
foreach ( $models as $model ){
  $customCode = "";
  if ( isset($custom[$model]) ){
    $customCode = $custom[$model];
  }
  $str = str_replace("[[CLASS_NAME]]", $model, $template);
  $str = str_replace("[[CUSTOM_CODE]]", $customCode, $str);
  $file = __DIR__ . '/app/controller/' . $model . '.php';
  file_put_contents($file, $str);
  chmod($file, 0755);
}
?>
