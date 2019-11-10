<?php

use Pheanstalk\Pheanstalk;

// Load Composer's autoloader
require 'vendor/autoload.php';


$pheanstalk = Pheanstalk::create('127.0.0.1');
$collegeName = 'Sambhram Institute of Technology';

$csv = new ParseCsv\Csv('data.csv');
$contestants = $csv->data;

foreach ($contestants as $contestant) {
    $contestant['college_name'] = $collegeName;
    $pheanstalk
        ->useTube('mcq_mails')
        ->put(json_encode($contestant));
}