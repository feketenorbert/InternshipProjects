<?php

include_once "functii.php";

function filter($tokenizedFile, $filterType)
{
    $listOfFilters = preg_split("/,/", $filterType);
    foreach ($listOfFilters as $filter) {
        $function = getFilter($filter);
        $tokenizedFile = $function($tokenizedFile);
    }

    return $tokenizedFile;
}

function getFilter($filterType)
{
    $functionName = 'filter' . $filterType;

    if (function_exists($functionName)) {

        return function ($tokenizedString) use ($functionName) {

            return $functionName($tokenizedString);
        };
    } else {
        echo "Nu exista functia" . $functionName . PHP_EOL;
    }
}

function filterLowerCase($tokenizedString)
{
    foreach ($tokenizedString as &$value) {
        if (is_array($value)) {
            $value = arrayLower($value);
        } else {
            $value = strtolower($value);
        }
    }

    return $tokenizedString;
}

function filterNormalize($tokenizedString)
{
    $patterns = array();
    $patterns[0] = '/á|â|à|å|ä|ă/';
    $patterns[1] = '/ð|é|ê|è|ë/';
    $patterns[2] = '/í|î|ì|ï/';
    $patterns[3] = '/ó|ô|ò|ø|õ|ö/';
    $patterns[4] = '/ú|û|ù|ü/';
    $patterns[5] = '/š|ș|ś/';
    $patterns[6] = '/ț/';

    $replacement = array();
    $replacement[0] = 'a';
    $replacement[1] = 'e';
    $replacement[2] = 'i';
    $replacement[3] = 'o';
    $replacement[4] = 'u';
    $replacement[5] = 's';
    $replacement[6] = 't';

    foreach ($tokenizedString as &$value) {
        if (is_array($value)) {
            foreach ($value as &$string) {
                $string = preg_replace($patterns, $replacement, $string);
            }
        } else {
            $value = preg_replace($patterns, $replacement, $value);
        }
    }

    return $tokenizedString;
}

function filterPatternCapture($tokenizedString)
{

}

function filterSynonyms($tokenizedString)
{
    $handler = fopen("/var/www/Search/Search.csv", "r");
    $listOfSynonyms = array();
    while (($data = fgetcsv($handler)) != 0) {
        array_push($listOfSynonyms, $data);
    }
    foreach ($listOfSynonyms as &$synonym) {
        $synonym = array_filter($synonym);                //fgetcsv imi umplea cu stringuri goale liniile care nu aveau suficiente cuvinte
    }
    var_dump($listOfSynonyms);

    foreach ($tokenizedString as &$value) {
        if (is_array($value)) {
            foreach ($value as &$string) {
                $string = findSynonyms($string, $listOfSynonyms);
            }
        }
        $value = findSynonyms($value, $listOfSynonyms);
    }

    return $tokenizedString;
}

function filterStemming($tokenizedString)
{
    $listOfSuffix = getParams()["stemming"];
    $listOfSuffix = preg_split("/,/", $listOfSuffix);
    $sizeListOfSuffix = sizeof($listOfSuffix);

    foreach ($tokenizedString as &$value) {
        if (is_array($value)) {
            foreach ($value as &$string) {
                $string = cutSuffix($string, $sizeListOfSuffix, $listOfSuffix);
            }
        } else {
            $value = cutSuffix($value, $sizeListOfSuffix, $listOfSuffix);
        }
    }

    return $tokenizedString;
}

function arrayLower($value)
{
    foreach ($value as &$str) {
        $str = strtolower($str);
    }

    return $value;
}

function findSynonyms($string, $listOfSynonyms)
{
    foreach ($listOfSynonyms as $index) {
        foreach ($index as $synonyms) {
            if (preg_match("/^$string$/", $synonyms) == 1) {

                return $index;
            }
        }
    }

    return $string;
}

function cutSuffix($string, $sizeListOfSuffix, $listOfSuffix)
{
    $index = 0;
    while ($index < $sizeListOfSuffix) {
        if ((preg_match("/$listOfSuffix[$index]$/", $string) == 1) && (strlen($string) - strlen($listOfSuffix[$index]) > 3)) {
            $string = substr($string, 0, strlen($string) - strlen($listOfSuffix[$index]));
        }
        $index++;
    }
    return $string;
}
