<?php

namespace Doloan09\RakeAlgorithms;

class RakeAlgorithms
{
    protected $lang;
    protected $paragraph;

    public function __construct(string $paragraph, $lang = 'vi_VN'){
        $this->lang = $lang;
        $this->paragraph = $paragraph;
    }
    /// tra ve mang stopword
    public function getStopWord($lang){
        if (!file_exists(__DIR__ . "/../asset/{$lang}.json")){
            throw new \ErrorException("Not found");
        }
        $stopWord = json_decode(file_get_contents(__DIR__ . "/../asset/{$lang}.json", true));
        return $stopWord;
    }

    // tach van ban thanh cac cau (truyen vao doan text)
    public function split_sentences(string $text){
        return preg_split('/[.?!,;\-"\'()\n\r\t]+/u', $text);
    }

    //  lay ra cac cum tu (truyen vao danh sach cac cau)
    public function getPhrase(array $sentences){
        $stopWord = $this->getStopWord($this->lang);
        $phraseArr = [];
        $regex     = '/\b' . implode('\b|\b', $stopWord) . '\b/iu';
        foreach ($sentences as $sentence){
            if (trim($sentence)){
                $phraseItem = preg_replace($regex, "|", trim(mb_strtolower($sentence)));
                $phraseItem = explode("|", $phraseItem);
                foreach ($phraseItem as $item){
                    if (trim($item)){
                        $phraseArr[] = trim($item);
                    }
                }
            }
        }
        return $phraseArr;
    }

    // tinh diem cho tung tu (truyen vao danh sach cac cum tu)
    public function getScore(array $CandidateKey){
        $frequencies = []; // word frequencies
        $degree = []; // degree of word
        $scores = []; // degree score

        foreach ($CandidateKey as $keyPhrase){
            $word = array_filter(explode(' ', $keyPhrase), function ($word){
                return (bool)trim($word);
            });

            foreach ($word as $w){
                $frequencies[$w] = ($frequencies[$w] ?? 0) + 1; // tan xuat xuat hien cua tung tu
                $degree[$w] = ($degree[$w] ?? 0 ) + count($word) - 1; // so cac so tu khac trong cum tu
            }
        }

        // degree of word
        foreach ($frequencies as $key => $value){
            $degree[$key] += $value;
        }

        // tinh degree score = degree of word / word frequencies
        foreach ($frequencies as $key => $value){
            $scores[$key] = round($degree[$key] / $value, 3);
        }

        return $scores;
    }

    public function getKeyword(int $numKeyword = null, $minScore = 0){
        $sentences = $this->split_sentences($this->paragraph); // tra ve 1 mang cac cau don
        $candidateKeyPhrases = $this->getPhrase($sentences); // tra ve mang cac cum tu
        $scores = $this->getScore($candidateKeyPhrases); // tra ve diem cua tung tu don le
        $cumulativeScore = [];

        foreach ($candidateKeyPhrases as $keyPhrase){
            $word = array_filter(explode(' ', $keyPhrase), function ($word){
                return (bool)trim($word);
            });

            $score = 0;
            foreach ($word as $w){
                $score += ($scores[$w] ?? 0);
            }
            $cumulativeScore[$keyPhrase] = $score;
        }

        arsort($cumulativeScore);

        $result = array_filter($cumulativeScore, function ($value) use ($minScore) {
            return $value >= $minScore;
        });

        if ($numKeyword) {
            $result = array_slice($result, 0, $numKeyword);
        }

        return $result;
    }
}
