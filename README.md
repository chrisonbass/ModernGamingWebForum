# Modern Gaming Web Forum
This is a fictitious site created as part of my capstone course while acquiring my Associates in Web Development

## Languages
 - PHP
 - HTML
 - JavaScript

## Frameworks
 - jQuery
 - Bootstrap

## Backend Design
I created a simple MVC pattern to handle requests.  Data is stored in CSV files, I created a base class that
implements the ActiveRecord design to handle CRUD operations on the CSV files.  See app\db\ActiveRecord.php

## Testing
On Windows you can use the provide php-test BAT file to test our code.  All test classes are stored in the tests folder.  This file assumes you have php in your $PATH

## Running locally
On Windows you can use the provide server BAT file to run the code locally.  This file assumes you have php in your $PATH
