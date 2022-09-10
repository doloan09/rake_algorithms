<?php

require "src/RakeAlgorithms.php";

use Doloan09\RakeAlgorithms\RakeAlgorithms;

$str = file_get_contents(__DIR__ . '/asset/text_en.txt');
$rake = new RakeAlgorithms($str, 'en_US');
$keyWords = $rake->getKeyword(15);

$result = '';
foreach ($keyWords as $keyWord => $score) {
    $result .= $keyWord . ' ==> ' . $score . "\n\r";
}

print_r($result);
