// funkce po zvoleni na element, ktery ji vola bud ukaze nebo zavre div add-item
function pop( element ){
    var add = document.getElementById( element );
    
    if( add.style.display === "block" ){
        add.style.display = "none";
    } else {
        add.style.display = "block"
    }
    
}

function hasName(){
    
    var name = document.getElementById( "jmeno" ).value;
    var surname = document.getElementById( "prijmeni" ).value;
    
    if( !name.match( /^[a-zA-Z]+$/ ) ){
        alert( "Prosím zadejte jméno" )
        return false;
    }
    
    if( !surname.match( /^[a-zA-Z]+$/ ) ){
        alert( "Prosím zadejte příjmení" )
        return false;
    }
    
}

// funkce kontroluje spravnost udaju zadanych ve formu
function validateform( param ){  

    var time = document.getElementsByName( param )[ 0 ].cas.value;
    var time2 = document.getElementsByName( param )[ 0 ].casleave.value;
    
    var date = document.getElementsByName( param )[ 0 ].date.value;
    var date2 = document.getElementsByName( param )[ 0 ].dateleave.value;

    if( date === null || date === "" ){
        alert( "Prosím zadejte datum" )
        return false;
    }

    if( date2 === null || date2 === "" ){
        alert( "Prosím zadejte datum" )
        return false;
    }

    if( !time.match( /^[0][1-9]\:[0-5][0-9]$/ ) ){
        if( !time.match( /^[1-2][0-9]\:[0-5][0-9]$/ ) ){
            alert( "Prosím zadejte korektní čas" );
            return false;
        }
    }
    
    if( !time2.match( /^[0][1-9]\:[0-5][0-9]$/ ) ){
        if( !time2.match( /^[1-2][0-9]\:[0-5][0-9]$/ ) ){
            alert( "Prosím zadejte korektní čas" );
            return false;
        }
    }

    if( time === null || time === "" ){  
        alert( "Prosím zadejte cas" );  
        return false;  
    } else if ( time.length < 5 ){  
        alert( "Prosím zadejte čas ve formátu mm:hh. Pro příklad 07:00." );  
        return false;
    }  
    
    if( time2 === null || time2 === "" ){  
        alert( "Prosím zadejte cas" );  
        return false;  
    } else if ( time2.length < 5 ){  
        alert( "Prosím zadejte čas ve formátu mm:hh. Pro příklad 07:00." );  
        return false;
    }  
}  

// funkce kontroluje format mesice a roku ve formu
function checkForm(){
    
    var month = document.person.month.value;
    
    if( !month.match( /^(\d){2}\-(\d){4}/ ) ){
        alert( "Prosím zadejte měsíc a rok ve formatu mm-yyyy. Například 07-2020." );  
        return false;
    }
    
}