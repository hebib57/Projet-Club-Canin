<?php
function hsc($string)
{ //fonction à mettre partout où il y a des echo
    if (is_null($string)) { //return (is_null($string)?"":htmlspecialchars($string));
        return "";
    } else {
        return htmlspecialchars($string);
    }
};


function cleanFilename($str)
{ //fonction qui remplace des caractères par d'autres caractères
    $result = strtolower($str); //pour mettre tout en minuscule

    $charKo = ["à", "â", " ", "'", "\\"]; //dans le cas d'un \ mettre un \\ (car \ annule le caractère suivant)
    $charOk = ["a", "a", "-", "-", ""];
    $result = str_replace($charKo, $charOk, $result);


    return trim($result, "-");
}




function HandleImageUpload() {};
