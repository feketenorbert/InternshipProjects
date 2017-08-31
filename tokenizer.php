<?php

function tokenize($stringName, $type)
{
    $function = get_tokenizer($type);

    return $function($stringName);
}

function get_tokenizer($type)
{
    $functionName = 'tokenize' . $type;

    if (function_exists($functionName)) {

        return function ($stringName) use ($functionName) {

            return $functionName($stringName);
        };
    } else {
        echo "Nu exista functia " . $functionName . PHP_EOL;
    }
}

function tokenizeWhitespace($stringName)
{
    $line = 0;
    if (is_array($stringName)) {
        foreach ($stringName as $value) {
            $tokenizedString[$line] = preg_split("/\s/", $value);
            $line++;
        }
    } else {
        $tokenizedString = preg_split("/\s/ ", $stringName);
    }

    return $tokenizedString;
}

function tokenizeStandard($stringName)
{
    $line = 0;
    if (is_array($stringName)) {
        foreach ($stringName as &$value) {
            $tokenizedString[$line] = preg_split('/[,.!?:\s]+/', $value);
            $line++;
        }
    } else {
        $tokenizedString = preg_split('/[,.!?:\s]+/', $stringName);
    }

    return $tokenizedString;
}