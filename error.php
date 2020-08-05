<!-- error.php vytvoril Jakub Sekula -->
<!-- error.php zajistuje chybove hlasky -->

<?php

/*
    Funkce vypisuje po prijeti chyby alert na obrazovku
    @param $error - kod chybove hlasky
    $return chybova zprava pro vypis v alertu
*/
function errorMessage( $error ){
    switch ( $error ){
        case 1:
            return "Chyba! Prosím zadejte i typ( příchod/odchod )";
            break;
        case 2:
            return "Chyba! Nelze zadat práci trvající déle než 24 hodin";
            break;
        case 10:
            return "Špatně zadaný čas příchodu, prosím zadejte čas příchodu znova !";
            break;
        case 11:
            return "Špatně zadaný čas odchodu, prosim zadejte čas odchodu znova !";
            break;
    }
}

?>