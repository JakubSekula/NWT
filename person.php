<!-- person.php vytvoril Jakub Sekula -->
<!-- person.php zobrazuje stranku po kliknuti na konkretniho zamestance -->

<head>
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
        case 10:
            return "Špatně zadaný čas příchodu, prosím zadejte čas příchodu znova !";
            break;
        case 11:
            return "Špatně zadaný čas odchodu, prosim zadejte čas odchodu znova !";
            break;
    }
}

/*
    Funkce spocita kompletni dochazku zamestnance ve vsech obdobych
    @param $id - identifikator zamestnance
    @param $conn - pripojeni k databazi
    @return celkovy cas
*/
function countEntireTime( $id, $conn ){

    $sql = "SELECT den, prichod, odchod, odlekare, klekari FROM DOCHAZKA WHERE id_osoby = $id";
    
    $result = $conn->query( $sql );
    
    $allhours = 0;
    
    if( $result->num_rows != 0 ){
        while( $row = $result->fetch_assoc() ){
            
            // jestli neni zadany v den prichod nebo odchod, tak den nepocitam
            if( $row[ 'odchod' ] == "" || $row[ 'prichod' ] == "" ){
                continue;
            }
            
            $hours = countHours( $row[ 'prichod' ], $row[ 'odchod' ], $row[ 'klekari' ], $row[ 'odlekare' ] );
    
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
 
        echo "<table class='person' border='1'>";
        echo "<thead>";
            echo "<tr>";
            echo "<th class='tg-4b8u' colspan='2' rowspan='5'>Fotka<br></th>";
            echo "<th class='tg-0lax' colspan='3'>Jmeno: ". $row[ 'jmeno' ]. "</th>";
        echo "</tr>";
        echo "<tr>";
            echo "<td class='tg-0lax' colspan='3'>Prijmeni: ". $row[ 'prijmeni' ]. " </td>";
        echo "</tr>";
         echo "<tr>";
            echo "<td class='tg-0lax' colspan='3'>Titul: ". $row[ 'titul' ]. " </td>";
        echo "</tr>";
        echo "<tr>";
            echo "<td class='tg-0lax' colspan='3'>Datum narozeni: XX.XX.XXXX </td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td class='tg-0lax' colspan='3'>Zamestnan od: XX.XX.XXXX <br></td>";
        echo "</tr>";
        echo "</thead>";
        echo "</table>";
        
    }
}

// ziskani parametru
$id = $_GET[ 'id' ];
$prichod = $_GET[ 'prichod' ];
$odchod = $_GET[ 'odchod' ];
$month = $_GET[ 'month' ];
$error = $_GET[ 'error' ];


$sql = "SELECT * FROM OSOBA WHERE id = $id";

$result = $conn->query( $sql );

if( $result->num_rows != 0 ){
    CreateTable( $result );
} else {
    echo"CHYBA";
}

echo '<button class="buttonw button2" type="button" onclick="pop()">Docházka</button>';

// html kod pro datepicker
echo "<div class='datepicker'>";
    echo "<form action='datepick.php'>";
      echo "<label for='Jobday'>Docházka dne: </label>";
      echo "<input type='hidden' name='id' value=$id>";
      echo "<input type='date' id='date' name='date'>";
      echo "<input id='submit' type='submit' value='Zvolit'>";
    echo "</form>";
echo "</div>";

    echo "<div class='prichododchod'>";
    
    echo "<label for='fname'>Příchod: </label><br>";
    echo "<input type='text' id='fname' name='fname' value=$prichod><br>";

    echo "<div class='odchod'>";
        echo "<label for='fname'>Odchod: </label><br>";
        echo "<input type='text' id='fname' name='fname' value=$odchod><br>";
    echo "</div>";
echo "</div>";

$sql = "SELECT * FROM typy";

$result = $conn->query( $sql );

// formular pro prichod odchod
echo "<div id='add-item'>";
    echo "<h4 id='testik'>Docházka <a href='#' id='inner' class='cancel clear' onclick='pop()'>x</a></h4>";
    echo "<hr class='hr-line'>";
    echo "<form name='dochazka' action='insertInfo.php' method='post' onsubmit='return validateform()' class='add-item-form'>";
        
        echo "<p class='add-item-label clear'>Den:</p>";
        echo "<input type='date' id='date' name='date'>";
        echo "<br>";
        
        echo "<p class='add-item-label clear' id='action'>Čas:</p>";
        echo "<input type='text' name='cas' placeholder='mm:hh' class='new-item-input' id='cas'>";
        echo "<br>";
        
        echo "<p class='add-item-label clear'>Typ:</p>";
        
        if( $result->num_rows != 0 ){
            
            while( $row = $result->fetch_assoc() ){
                $type = $row[ 'typ' ];
                $idcko = $row[ 'id' ];
                echo "<input type='radio' id='type' name='type' value=$idcko>";
                echo "<label for=$type>$type</label>";
            }
            
        } else {
            echo"CHYBA";
        }
        
        echo "<input type='hidden' name='id' value=$id>";
        echo "<div class = 'wrap'>";
            echo "<button type='submit' value='Submit' class='login-button border' > <span>Zadat</span> </button>";
        echo "</div>";
    echo "</form>";
echo "</div>";

echo "<div class='monthpicker'>";
    echo "<form name='person' action='person.php'>";
      echo "<label for='Jobday'>Odpracovanost v měsíci: </label>";
      echo "<input type='hidden' name='id' value=$id>";
      echo "<input type='month' id='month' placeholder='Zadejte mm-yyyy' name='month'>";
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
    
    $sql = "SELECT den,prichod, odchod, odlekare, klekari FROM DOCHAZKA WHERE id_osoby = $id AND den LIKE '%$month%$year'";
    
    $result = $conn->query( $sql );
    
    $all = array();
    
    $allhours = 0;
    
    // pridani hodin denne do asociativniho pole
    if( $result->num_rows != 0 ){
        while( $row = $result->fetch_assoc() ){
            
            $den = substr( $row[ 'den' ], 0, 2 );
            
            $hours = countHours( $row[ 'prichod' ], $row[ 'odchod' ], $row[ 'klekari' ], $row[ 'odlekare' ] );
            
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
    
    echo "<table class='personTime' border='1'>";
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

?>


</body>