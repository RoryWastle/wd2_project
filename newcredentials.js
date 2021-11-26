/**************************************************************
 * Name: 	Rory Wastle
 * Date: 	November 15 2021
 * Purpose: Perform checks on credentials
 * ************************************************************/

//  Run when the DOM has loaded.
function load(){
    //let passerror = document.getElementById('pass-error');
    //passerror.style.display = 'none';
    checkPasswords();

    let pass1 = document.getElementById('newpass1');
    pass1.addEventListener("blur", checkPasswords);

    let pass2 = document.getElementById('newpass2');
    pass2.addEventListener("blur", checkPasswords);
}

//  Check that the two passwords are the same.
function checkPasswords(){
    let pass1 = document.getElementById('newpass1').value;
    let pass2 = document.getElementById('newpass2').value;
    let passerror = document.getElementById('pass-error');
    
    if(pass1 === pass2){
        passerror.style.display = 'none';
    }
    else{
        passerror.style.display = 'block';
    }
}

document.addEventListener("DOMContentLoaded", load);