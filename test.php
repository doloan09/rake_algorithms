<?php

require "src/RakeAlgorithms.php";

use Doloan09\RakeAlgorithms\RakeAlgorithms;

$str = file_get_contents(__DIR__ . '/asset/text_vi.txt');
$rake = new RakeAlgorithms($str, 'vi_VN');
$keyWords = $rake->getKeyword(15);

$result = '';
foreach ($keyWords as $keyWord => $score) {
    $result .= $keyWord . ' ==> ' . $score . "\n\r";
}

print_r($result);
