<?php

require "src/RakeAlgorithms.php";

use Doloan09\RakeAlgorithms\RakeAlgorithms;

$str = file_get_contents(__DIR__ . '/../asset/text_vi.txt');

$rake = new RakeAlgorithms();
$keyWords = $rake->getStopWord('vi_VN')->getParagraph($str)->getKeyword();

$result = '';
foreach ($keyWords as $keyWord => $score) {
    $result .= $keyWord . ' ==> ' . $score . "\n\r";
}

print_r($result);
