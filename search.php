<?php

include_once "functii.php";
include_once "tokenizer.php";
include_once "fiter.php";

$params = getParams();
$numeFisier = $params["input"];
$query = $params["query"];
validateFile($numeFisier);
$inputFile = readFromFile($numeFisier);
$tokenizedFile = tokenize($inputFile, $params["input-tokenizer"]);
$tokenizedQuery = tokenize($query, $params["query-tokenizer"]);
$filteredFile = filter($tokenizedFile, $params["input-filters"]);
$filteredQuery = filter($tokenizedQuery, $params["query-filters"]);
//var_dump($filteredQuery);
//var_dump($filteredFile);
searchQuery($filteredFile, $filteredQuery,$tokenizedFile);

