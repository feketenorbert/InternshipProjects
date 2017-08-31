<?php

function getParams()
{
    $longopts = file("/var/www/Search/options.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $options = getopt("", $longopts);

    return $options;
}

function validateFile($filename)
{
    if (!file_exists($filename)) {
        echo "Fisierul nu exista";
    }
}

function readFromFile($filename)
{

    $inputFile = file($filename, FILE_IGNORE_NEW_LINES);
    return $inputFile;
}

/**
 * Aceasta functie parcurge atat fisierul cat si query-ul care au fost deja filtrate si cauta sa gaseasca un match de 100%
 * Dupa ce a gasit cel putin un match se apeleaza functia de afisare;
 * @param $filteredFile -fisierul filtrat
 * @param $filteredQuery -query filtrat
 * @param $tokenizedFile - parametru care pastreaza fisierul original tokenizat; folosit pentru afisare
 */
function searchQuery($filteredFile, $filteredQuery, $tokenizedFile)
{

    for ($i = 0, $n = sizeof($filteredFile); $i < $n; $i++) {
        for ($j = 0, $size = sizeof($filteredFile[$i]); $j < $size; $j++) {
            $ok = true;
            $k = $j;
            for ($t = 0; $t < sizeof($filteredQuery); $t++) {
                if (strcmp($filteredQuery[$t], $filteredFile[$i][$k]) == 0) {
                    $k++;
                } else {
                    $ok = false;
                }
            }

            if ($ok) {
                echo $i . " ";
                echo afisare($tokenizedFile[$i], $k, $j - 3) . PHP_EOL;
            }
        }
    }
}

/**
 * Aceasta functie are rolul de a afisa 3 cuvinte inainte urmate de Query si 3 cuvinte dupa Query;
 * In cazul in care se ajunge la sfarsitul liniei inainte de a se gasi 3 cuvinte nu se trece pe linia urmatoare;
 * @param $linieDinFisier -linia din fisier pe care a fost gasit match-ul; este un array;
 * @param $pozitieSfarsit -pozitia urmatoare match-ului
 * @param $pozitieInceput -pozitia din fisier de la care incepe afisarea, in cazul de fata 3 cuvinte inainte de pozitia de la care incepe match-ul
 */
function afisare($linieDinFisier, $pozitieSfarsit, $pozitieInceput)
{
    $countWords = 0;

    while ($countWords < 3) {
        if ($pozitieInceput > 0) {
            echo $linieDinFisier[$pozitieInceput] . " ";
        }
        $pozitieInceput++;
        $countWords++;
    }
    for($i=$pozitieInceput ;$i<$pozitieSfarsit;$i++) {
        echo $linieDinFisier[$i] . " ";
    }

    $countWords = 0;

    while (($countWords < 3)) {
        if ($pozitieSfarsit < sizeof($linieDinFisier)) {
            echo $linieDinFisier[$pozitieSfarsit] . " ";
        }
        $countWords++;
        $pozitieSfarsit++;
    }
}
