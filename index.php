<!DOCTYPE html>
<html lang="pl">
<head>
    <title>Zaloguj się do Banku!</title>
    <link rel="icon" type="image/x-icon" href="images\icon.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Raleway|Gruppo|Cinzel Decorative">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        body, html {height: 100%}
    </style>
</head>
<body>

            
    <script defer src="jquery.min.js" type = "text/javascript"></script>
        <script defer>
            function logInFunction(){
                

                var form = document.getElementById("form1");
                var data = new FormData(form);

                var login = data.get("login");
                var password = data.get("password");

                jQuery.ajax({
                    type: "POST",
                    url: 'databaseManager.php',
                    dataType: 'json',
                    data: {functionname: 'checkLoginPassword', arguments: [login, password]},

                    success: function(obj, textstatus){
                        if( !('error' in obj)){
                            loginPasswordMatch = obj.correct;

                            if(loginPasswordMatch){
                                customerID = obj.customerID;
                                sessionStorage.setItem("customerID", customerID);

                                location.href = 'main.php';
                            } else {
                                alert("Niepoprawne dane logowania");
                            }

                        } else {
                            console.log(obj.error);
                            alert(obj.error);
                        }
                    }
                })

            }

            function registerFunction(){
                var name = document.getElementById("name").value;
                var surname = document.getElementById("surname").value;
                var login = document.getElementById("login").value;
                var password = document.getElementById("password").value;
                var rpassword = document.getElementById("rpassword").value;

                if(password != rpassword){
                    alert("Hasła nie są takie same");
                    return;
                }

                jQuery.ajax({
                    type: "POST",
                    url: 'databaseManager.php',
                    dataType: 'json',
                    data: {functionname: 'createAccount', arguments: [name, surname, login, password]},

                    success: function(obj, textstatus){
                        if( !('error' in obj)){
                            alert('Konto pomyślnie założone!');

                        } else {
                            console.log(obj.error);
                            alert(obj.error);
                        }
                    }
                })

                

            }

        </script>



    <div class="bgimg w3-display-container w3-animate-opacity ">
  
        <div class="w3-display-topmiddle w3-large w3-text-white modern">
            <p> Stworzony dla Ciebie. Bank&#8482  <img src="images/icon.png" alt="icon" width="30" height="30"> </p>
        </div>  

        <div class="w3-display-middle w3-card-2 w3-padding-16 w3-round w3-white w3-quarter">
            <form method="post" action="javascript:logInFunction()" class="w3-container" id="form1">
                <label class="" ><b>Login:</b></label>
                <input name="login" class="w3-input w3-border w3-light-grey" type="text">
                <p></p>
                <label class=""><b>Hasło:</b></label>
                <input name="password" class="w3-input w3-border w3-light-grey" type="password">
                <p></p>
                <div class="w3-container w3-right" style="padding-right:0%">
                <div class="w3-bar">
                    
                <button type="submit" class="w3-button w3-teal w3-hover-teal w3-text-white w3-hover-text-black" form="form1" value="Submit">Zaloguj</button>
                <button type="button" class="w3-button w3-teal w3-hover-teal w3-text-white w3-hover-text-black" onclick="document.getElementById('Register').style.display='block'">Załóż konto!</button>
                    <!-- <button type="button" class="w3-button w3-teal w3-hover-teal w3-text-white w3-hover-text-black" onclick="loginInFunction()">Zaloguj się!</button> -->
                </div></div>



            </form> 
            <!-- do tego forma to action jakiś w js albo php idk  -->
            

        </div>

        	<!-- Modal Rejestracja -->
        	<!-- Modal Rejestracja -->

        
        

	    <div id="Register" class="w3-modal w3-animate-opacity " >
		    <div class="w3-modal-content w3-card-4 w3-display-middle" style="max-width:680px">
		        <header class="w3-container w3-teal"> 
			        <span onclick="document.getElementById('Register').style.display='none'" class="w3-button w3-display-topright w3-hover-text-black w3-hover-teal">&times;</span>
			    <h2>Rejestracja</h2>
		        </header>
                <div class="w3-container">
                    <div class="w3-row w3-section">
                        <label class="" >Imię:</label>
                        <input id="name" class="w3-input w3-border" type="text">
                    </div>
                    <div class="w3-row w3-section">
                        <label class="" >Nazwisko:</label>
                        <input id="surname" class="w3-input w3-border" type="text">
                    </div>
                    <div class="w3-row w3-section">
                        <label class="" >Login:</label>
                        <input id="login" class="w3-input w3-border" type="text">
                    </div>
                    <div class="w3-row w3-section">
                        <label class="">Hasło:</label>
                        <input id="password" class="w3-input w3-border" type="password">
                    </div>
                    <div class="w3-row w3-section">
                        <label class="">Powtórz hasło:</label>
                        <input id="rpassword" class="w3-input w3-border" type="password">
                    </div>
                    <!-- TO DO sprawdzenie czy hasło się zgadza w obu -->
                    <button onclick="registerFunction();" class="w3-btn w3-section w3-right w3-teal w3-hover-text-black">Stwórz konto bankowe</button>
                </div>
			    <!-- TO DO funkcja rejestracji -->
		    </div>
	    </div>

    </div>
<script src="script.js"></script>
</body>
</html>

