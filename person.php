<!-- person.php vytvoril Jakub Sekula -->
<!-- person.php zobrazuje stranku po kliknuti na konkretniho zamestance -->

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

// pripojeni k databazi
include "connect.php";
// funkce pro praci s casem
include "date.php";
// error hlasky
include "error.php";

/*
    Funkce spocita kompletni dochazku zamestnance ve vsech obdobych
    @param $id - identifikator zamestnance
    @param $conn - pripojeni k databazi
    @return celkovy cas
*/
function countEntireTime( $id, $conn ){

    $sql = "SELECT * FROM DOCHAZKA WHERE id_osoby = $id";
    
    $result = $conn->query( $sql );
    
    $allhours = 0;
    
    if( $result->num_rows != 0 ){
        while( $row = $result->fetch_assoc() ){
            
            $hours = countHours( $row[ 'den' ], $row[ 'denodchod' ], $row[ 'prichod' ], $row[ 'odchod' ], $row[ 'klekari' ], $row[ 'odlekare' ] );
    
            $allhours = $allhours + sumHoursMonthly( $hours );
        }
    }
    
    return $allhours;
    
}

/*
    Funkce vytvori tabulka s profilem zamestnance 
    @param $qresult - vysledek z databaze, informace o zamestnanci
*/
function CreateTable( $qresult ){
 
    while( $row = $qresult->fetch_assoc() ){
        
        $date = convert( $row[ 'zamestnan' ] );
        $birth = convert( $row[ 'dnarozeni' ] );
 
        echo "<table class='person'>";
        echo "<thead>";
            echo "<tr>";
            echo "<th class='tg-4b8u' colspan='2' rowspan='5'>Fotka<br></th>";
            echo "<th class='tg-0lax' colspan='3'>Jméno: ". $row[ 'jmeno' ]. "</th>";
        echo "</tr>";
        echo "<tr>";
            echo "<td class='tg-0lax' colspan='3'>Příjmení: ". $row[ 'prijmeni' ]. " </td>";
        echo "</tr>";
         echo "<tr>";
            echo "<td class='tg-0lax' colspan='3'>Titul: ". $row[ 'titul' ]. " </td>";
        echo "</tr>";
        echo "<tr>";
            echo "<td class='tg-0lax' colspan='3'>Datum narození: ". $birth[ 2 ] . "." . $birth[ 1 ] . "." . $birth[ 0 ] . " </td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td class='tg-0lax' colspan='3'>Zaměstnán od: ". $date[ 2 ] . "." . $date[ 1 ] . "." . $date[ 0 ]  . " <br></td>";
        echo "</tr>";
        echo "</thead>";
        echo "</table>";
        
        echo '<button class="correction" type="button" onclick="pop( \'' . 'person-creds' . '\' )">Upravit údaje</button>';
        echo "<br>";
        
    }
}

// ziskani parametru
$id = $_GET[ 'id' ];
$prichod = $_GET[ 'prichod' ];
$odchod = $_GET[ 'odchod' ];
$month = $_GET[ 'month' ];
$error = $_GET[ 'error' ];
 

if( !preg_match( '/^(\d)*$/', $id ) ){
    header( "Location: person.php?id=$id&error=sqlattack" );
    exit();
}

if( $prichod != "" ){
    if( !preg_match( '/^[1-9]{2}\:[0-9]{2}$/', $prichod ) ){
        header( "Location: person.php?id=$id&error=sqlattack" );
        exit();
    }
}

if( $odchod != "" ){
    if( !preg_match( '/^[1-9]{2}\:[0-9]{2}$$/', $odchod ) ){
        header( "Location: person.php?id=$id&error=sqlattack" );
        exit();
    }
}

$sql = "SELECT * FROM OSOBA WHERE id = $id";

$result = $conn->query( $sql );

if( $result->num_rows != 0 ){
    CreateTable( $result );
} else {
    echo"CHYBA";
}

echo '<button class="doch" type="button" onclick="pop( \'' . 'add-item' . '\' )">Zadejte příchod/odchod</button>';
echo '<button class="doch2" type="button2" onclick="pop( \'' . 'doctor' . '\' )">Zadejte příchod/odchod k lékaři</button>';

$sql = "SELECT * FROM typy";

$result = $conn->query( $sql );

// formular pro prichod odchod
echo "<div id='add-item'>";
    echo '<h4 id="testik">Docházka <span class="cross"><a href="#" id="inner" class="cancel clear" onclick="pop( \'' . 'add-item' . '\' )">&#10060;</a></span></h4>';
    echo "<hr class='hr-line'>";
    echo '<form name="dochazka" action="insertInfo.php" method="post" onsubmit="return validateform( \'' . 'dochazka' . '\' )" class="add-item-form">';
        
        echo "<p class='add-item-label clear'>Datum příchodu:</p>";
        echo "<input type='date' id='date' name='date'>";
        echo "<div class='dateleave'>";
        echo "<p class='add-item-label clear'>Datum odchodu:</p>";
        echo "<input type='date' id='dateleave' name='dateleave'>";
        echo "</div>";
        echo "<br>";
        
        echo "<p class='add-item-label clear' id='action'>Čas příchodu:</p>";
        echo "<input type='text' name='cas' placeholder='mm:hh' class='new-item-input' id='cas'>";
        echo "<div class='timeleave'>";
        echo "<p class='add-item-label clear' id='action2'>Čas odchodu:</p>";
        echo "<input type='text' name='casleave' placeholder='mm:hh' class='new-item-input' id='casleave'>";
        echo "</div>";
        echo "<br>";
        
        echo "<input type='hidden' name='id' value=$id>";
        echo "<input type='hidden' name='type' value=1>";
        echo "<div class = 'wrap'>";
            echo "<button type='submit' value='Submit' class='login-button border' > <span>Zadat</span> </button>";
        echo "</div>";
    echo "</form>";
echo "</div>";

// formular pro prichod odchod k lekari
echo "<div id='doctor'>";
    echo '<h4 id="testik2">U lékaře<span class="cross"><a href="#" id="inner2" class="cancel clear" onclick="pop( \'' . 'doctor' . '\' )">&#10060;</a></span></h4>';
    echo "<hr class='hr-line'>";
    echo '<form name="dochazka2" action="insertInfo.php" method="post" onsubmit="return validateform( \'' . 'dochazka2' . '\' )" class="add-item-form">';
        
        echo "<p class='add-item-label clear'>Datum odchodu:</p>";
        echo "<input type='date' id='date' name='date'>";
        echo "<div class='dateleave'>";
        echo "<p class='add-item-label clear'>Datum příchodu:</p>";
        echo "<input type='date' id='dateleave' name='dateleave'>";
        echo "</div>";
        echo "<br>";
        
        echo "<p class='add-item-label clear' id='action2'>Čas odchodu k lékaři:</p>";
        echo "<input type='text' name='cas' placeholder='mm:hh' class='new-item-input' id='cas'>";
        echo "<div class='timeleave'>";
        echo "<p class='add-item-label clear' id='action3'>Čas příchodu od lékaře:</p>";
        echo "<input type='text' name='casleave' placeholder='mm:hh' class='new-item-input' id='casleave'>";
        echo "</div>";
        echo "<br>";
        
        echo "<input type='hidden' name='id' value=$id>";
        echo "<input type='hidden' name='type' value=2>";
        echo "<div class = 'wrap'>";
            echo "<button type='submit' value='Submit' class='login-button border' > <span>Zadat</span> </button>";
        echo "</div>";
    echo "</form>";
echo "</div>";

echo "<div class='monthpicker'>";
    echo "<form name='person' action='person.php'>";
      echo "<label for='Jobday'>Odpracovanost v měsíci: </label>";
      echo "<input type='hidden' name='id' value=$id>";
      echo "<input id='month' placeholder='Zadejte mm-yyyy' name='month'>";
      echo "<input id='submit' type='submit' onclick='checkForm()' value='Ukázat'>";
    echo "</form>";
echo "</div>";

$alltimehours = countEntireTime( $id, $conn );
    
$alltimehours = convertMinutesToHours( $alltimehours );

echo "<div class='alltime'>";
    echo "<p>Celkový odpracovaný čas ve firmě: $alltimehours</p>";
echo "</div>";

// jestlize z parametru byl ziskan mesic pro ukazani dochazky
if( $month != '' ){
    
    $pieces = convert( $month );
    $month = $pieces[ 0 ];
    $year = $pieces[ 1 ];
    
    $sql = "SELECT * FROM DOCHAZKA WHERE id_osoby = $id AND den LIKE '%$month$year'";
    
    $result = $conn->query( $sql );
    
    $all = array();
    
    $allhours = 0;
    
    // pridani hodin denne do asociativniho pole
    if( $result->num_rows != 0 ){
        while( $row = $result->fetch_assoc() ){
            
            $den = substr( $row[ 'den' ], 0, 2 );
            
            $hours = countHours( $row[ 'den' ], $row[ 'denodchod' ], $row[ 'prichod' ], $row[ 'odchod' ], $row[ 'klekari' ], $row[ 'odlekare' ] );
            
            // 07:00
            if( substr( $den, 0, 1 ) == 0 ){
                $den = substr( $den, 1, 2 );
            }
    
            $allhours = $allhours + sumHoursMonthly( $hours );

            $exp = explode( ":", $hours  );
            
            if( $exp[ 0 ] == "error" ){
                $all[ $den ] = "";
            } else {
                $all[ $den ] = $exp[ 0 ] . " hodin " . $exp[ 1 ] . " minut";
            }
        }
    }
    
    $allhours = convertMinutesToHours( $allhours );
    
    echo "<div class='summonth'>";
        echo "<p>Odpracovany cas za mesic: $allhours</p>";
    echo "</div>";
    
    echo "<table class='personTime'>";
        echo "<thead>";
            echo "<tr>";
            echo "<th class='tg-4b8u'>Den<br></th>";
            echo "<th class='tg-0lax'>Pocet hodin:</th>";
        echo "</tr>";
        $i = 1;
        while( $i <= 31 ){
            echo "<tr>";
                $hours = $all[ $i ];
                echo "<td class='tg-0lax'>$i. </td>";
                echo "<td class='tg-0lax'>$hours</td>";
            echo "</tr>";
            $i++;
        }
        echo "</table>";
}

// vypis chyboveho alertu
if( $error > 0 ){
    $mess = errorMessage( $error );
    echo '<script language="javascript">';
    echo "alert( '$mess' )";
    echo '</script>';
}

$sql = "SELECT * FROM OSOBA WHERE id = $id";

$result = $conn->query( $sql );

$name = "";
$surname = "";
$birth = "";
$empl = "";

if( $result->num_rows != 0 ){
        while( $row = $result->fetch_assoc() ){
            $name = $row[ 'jmeno' ];
            $surname = $row[ 'prijmeni' ];
            $birth = $row[ 'dnarozeni' ];
            $empl = $row[ 'zamestnan' ];
        }
}

$birth = convert( $birth );
$birth = $birth[ 2 ] . "." . $birth[ 1 ] . "." . $birth[ 0 ];

$empl = convert( $empl );
$empl = $empl[ 2 ] . "." . $empl[ 1 ] . "." . $empl[ 0 ];


// formular pro upravu udaju
echo "<div id='person-creds'>";
    echo '<h4>Úprava osoby <span class="cross corr"><a href="#" class="cancel clear" onclick="pop( \'' . 'person-creds' . '\' )">&#10060;</a></span></h4>';
    echo "<hr class='hr-line'>";
    echo "<form action='correction.php' method='post' class='add-item-form'>";
        echo "<p class='add-item-label clear'>Jméno:</p>";
        echo "<input type='text' name='jmeno' value=$name class='new-item-input' id='jmeno'>";
        echo "<br>";
        echo "<p class='add-item-label clear'>Příjmení:</p>";
        echo "<input type='text' name='prijmeni' value=$surname class='new-item-input' id='prijmeni'>";
        echo "<br>";
        echo "<input type='hidden' name='id' value=$id>";
        echo "<p class='add-item-label clear'>Datum narození:</p>";
        echo "<input type='text' name='dnarozeni' value='$birth' onfocus='(this.type=\"date\")' class='new-item-input' id='dnarozeni'>";
        echo "<br>";
        echo "<p class='add-item-label clear'>Zaměstnán od:</p>";
        echo "<input type='text' name='zod' value='$empl' onfocus='(this.type=\"date\")' class='new-item-input' id='zod'>";
        echo "<br>";
        echo "<p class='add-item-label clear'>Titul: </p>";
        echo "<select name='titul' id='titul'>";
          echo "<option value='Ing.'>Ing.</option>";
          echo "<option value='Bc.'>Bc.</option>";
          echo "<option value=''></option>";
        echo "</select>";
        echo "<br>";
        echo "<div class = 'wrap'>";
            echo "<button type='submit' value='Submit' class='login-button border' onclick='pridani()'> <span>Upravit</span> </button>";
        echo "</div>";
    echo "</form>";
echo "</div>";

?>


</body>

</html>