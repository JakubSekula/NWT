<!-- index.php vytvoril Jakub Sekula -->
<!-- Hlavni a prvotni stranka --> 

<!DOCTYPE html>
<html>

<head>
    <title>
        Docházkový systém
    </title>
    
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="script.js"></script>
</head>

<body>

<div class="header">
    <h1><a href='http://kubanwttest.8u.cz'>Docházkový systém</a></h1>
</div>

<?php

// include zajisti pripojeni k databazi
include "connect.php";

/*
    Funkce vytovri tabulku, ve ktere budou zamestnanci, u kterych je mozne upravovat dochazku
    @param $qresult - vysledek query z databaze
*/
function CreateTable( $qresult ) {
   
    echo "<table>";
    echo "<tr>";
        echo "<th>Jméno</th>";
        echo "<th>Příjmení</th>";
        echo "<th>Titul</th>";
        echo "<th class='del'>Smazat</th>";
    echo "</tr>";
    
    // prochazeni vysledku query
    while( $row = $qresult->fetch_assoc() ){
        echo "<tr>";
            $id = $row[ 'id' ];
            echo "<td><a href='person.php?id=$id'>".$row[ 'jmeno' ]."</td>";
            echo "<td><a href='person.php?id=$id'>".$row[ 'prijmeni' ]."</td>";
            echo "<td>".$row[ 'titul' ]."</td>";
            echo "<td class=del><a href='delete.php?id=$id'>&#10060;</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
}

$sql = "SELECT * FROM OSOBA";
$result = $conn->query( $sql );
if( $result->num_rows != 0 ){
    CreateTable( $result );
} else {
    
}

echo "<div id='add-item'>";
    echo '<h4>Nová osoba <span class="cross big"><a href="#" class="cancel clear" onclick="pop( \'' . 'add-item' . '\' )">&#10060;</a></span></h4>';
    echo "<hr class='hr-line'>";
    echo "<form action='insert.php' method='post' onsubmit='return hasName()' class='add-item-form'>";
        echo "<p class='add-item-label clear'>Jméno:</p>";
        echo "<input type='text' name='jmeno' class='new-item-input' id='jmeno'>";
        echo "<br>";
        echo "<p class='add-item-label clear'>Příjmení:</p>";
        echo "<input type='text' name='prijmeni' class='new-item-input' id='prijmeni'>";
        echo "<br>";
        echo "<p class='add-item-label clear'>Datum narození:</p>";
        echo "<input type='date' name='dnarozeni' class='new-item-input' id='dnarozeni'>";
        echo "<br>";
        echo "<p class='add-item-label clear'>Zaměstnán od:</p>";
        echo "<input type='date' name='zod' class='new-item-input' id='zod'>";
        echo "<br>";
        echo "<p class='add-item-label clear'>Titul: </p>";
        echo "<select name='titul' id='titul'>";
          echo "<option value='Ing.'>Ing.</option>";
          echo "<option value='Bc.'>Bc.</option>";
          echo "<option value=''></option>";
        echo "</select>";
        echo "<br>";
        echo "<div class = 'wrap'>";
            echo "<button type='submit' value='Submit' class='login-button border'> <span>Přidat</span> </button>";
        echo "</div>";
    echo "</form>";
echo "</div>";

echo '<div class="footer">';
    echo '<button class="button button1" onclick="pop( \'' . 'add-item' . '\' )" id="add_person">Přidat osobu</button>';
echo '</div>';

?>

</body>
</html>