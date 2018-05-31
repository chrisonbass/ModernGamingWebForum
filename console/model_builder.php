<?php

$models = [
  [
    "class_name" => "User",
    "table" => "user",
    "fields" => [
      "name",
      "email"
    ]
  ],
  [
    "class_name" => "UserRole",
    "table" => "user-role",
    "fields" => [
      "user_id",
      "role_id"
    ]
  ],
  [
    "class_name" => "Role",
    "table" => "role",
    "fields" => [
      "type",
    ]
  ],
  [
    "class_name" => "Board",
    "table" => "board",
    "fields" => [
      "name",
      "description"
    ]
  ],
  [
    "class_name" => "Topic",
    "base_class" => "app\\db\\LikedRecord",
    "table" => "topic",
    "fields" => [
      "board_id",
      "title",
      "body",
    ]
  ],
  [
    "class_name" => "Comment",
    "base_class" => "app\\db\\LikedRecord",
    "table" => "comment",
    "fields" => [
      "topic_id",
      "user_id",
      "body",
    ]
  ],
  [
    "class_name" => "LikedBy",
    "table" => "liked-by",
    "fields" => [
      "user_id",
      "object_model",
      "object_id"
    ]
  ],
];

$template = file_get_contents(__DIR__ . '/model_template.txt');
foreach ( $models as $model ){
  $fields = "public \$" . implode( ";\n  public \$", $model['fields']) . ";";
  $php_file = __DIR__ . "/app/model/" . str_replace("-", "_", $model['class_name']) . ".php";
  $base_class = "app\\db\\ActiveRecord";
  if ( isset($model['base_class']) ){
    $base_class = $model['base_class'];
  }
  $classes = array();
  $classes[] = $base_class;
  $classes = "use " . implode(";\nuse ", $classes) . ";";
  $base_name = preg_split("/\\\\/", $base_class);
  $base_name = end($base_name);
  $str = str_replace("[[CLASS_NAME]]", $model['class_name'], $template);
  $str = str_replace("[[CLASSES_USED]]", $classes, $str);
  $str = str_replace("[[BASE_CLASS]]", $base_name, $str);
  $str = str_replace("[[TABLE_NAME]]", $model['table'], $str);
  $str = str_replace("[[PROPERTIES]]", $fields, $str);
  $str = str_replace("", "", $str);
  file_put_contents($php_file, $str);
  chmod($php_file, 0755);
}
