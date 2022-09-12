<?php

require './vendor/autoload.php';

use Doloan09\RakeAlgorithms\RakeAlgorithms;

$str = file_get_contents(__DIR__ . '/asset/text_en.txt');

$rake = new RakeAlgorithms();
$keyWords = $rake->getStopWord('en_US')->getParagraph($str)->setMinScore(0)->setNumKeyWord(15)->getKeyword();

$result = '';
foreach ($keyWords as $keyWord => $score) {
    $result .= $keyWord . ' ==> ' . $score . "\n\r";
}

print_r($result);
