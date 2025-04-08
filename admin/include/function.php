<?php
function hsc($string)
{ //fonction à mettre partout où il y a des echo
    if (is_null($string)) { //return (is_null($string)?"":htmlspecialchars($string));
        return "";
    } else {
        return htmlspecialchars($string);
    }
};
