<?php

// Load Composer's autoloader
require 'vendor/autoload.php';


$csv = new ParseCsv\Csv('questions.csv');
$questions = $csv->data;

echo json_encode($questions);

?>
