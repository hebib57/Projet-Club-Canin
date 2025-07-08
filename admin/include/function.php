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




// function HandleImageUpload() {};


function displayPagination($nbPage, $currentPage, $url = "index.php", $param = "page", $limit = 20)
{
    if ($currentPage < 1) {
        $currentPage = 1;
    }
    if ($currentPage > $nbPage) {
        $currentPage = $nbPage;
    }
    if ($nbPage > 1) {
        echo "<ul>";
        echo "<li><a href=\"" . $url . "\">&lt;&lt</a></li>";
        echo "<li><a href=\"" . $url . ($currentPage > 2 ? "?" . $param . "=" . ($currentPage - 1) : "") . "\">&lt</a></li>";

        $displayDots = true;
        for ($i = 1; $i <= $nbPage; $i++) {
            if ($nbPage <= $limit || $i <= 3 || $i >= ($nbPage - 2) || $i == $currentPage || $i == $currentPage - 1 || $i == $currentPage + 1) {
                echo "<li " . ($i == $currentPage ? "class=\"active\"" : "") . "><a href=\"" . $url . ($i > 1 ? "?" . $param . "=" . $i : "") . "\">" . $i . "</a></li>";
                //si $i est égal à currentPage alors(?) condition ok sinon(:) rien
                $displayDots = true;
            } else {
                if ($displayDots) {
                    echo "<li class=\"inactive\">...</li>";
                    $displayDots = false;
                }
            }
        }
        echo "<li><a href=\"" . $url . "?" . $param . "=" . ($currentPage == $nbPage ? $nbPage : $currentPage + 1) . "\">&gt</a></li>";
        echo "<li><a href=\"" . $url . "?" . $param . "=" . $nbPage . "\">&gt;&gt</a></li>";
        echo "</ul>";
    }

    





}
