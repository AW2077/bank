<!DOCTYPE html>
<html lang="pl">
<head>
	<script>
		window.onload = function() {
						if(!window.location.hash) {
							window.location = window.location + '#loaded';
							window.location.reload();
						}
					}
	</script>

    <title>Strona główna Banku</title>
    <link rel="icon" type="image/x-icon" href="images\icon.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Raleway|Gruppo|Cinzel Decorative">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
    </style>
</head>
<body>
	
    <div class="w3-top w3-teal">
        <div class="w3-bar w3-card bgmain">
			<a href="index.php" class="w3-bar-item w3-button w3-hover-none w3-text-white w3-hover-text-black w3-display-topright">Wyloguj</a>
			<div class="w3-container">
				<p id="customer"></p> 
				
				<script src="jquery.min.js"></script>
				<script>

					customerID = sessionStorage.getItem("customerID");
					
					jQuery.ajax({
						type: "POST",
                                url: 'databaseManager.php',
                                dataType: 'json',
                                data: {functionname: 'getCustomerName', arguments: [Number(customerID)]},

                                success: function(obj, textstatus){
                                    if( !('error' in obj)){
										surname = obj.name[0];
                                        name = obj.name[1];
										balance = obj.balance;

										document.getElementById("customer").innerHTML = "Zalogowano "+name+" "+surname;
										document.getElementById("balance").innerHTML = "Dostępne środki: "+balance;
										document.getElementById("customerID").innerHTML = "Numer konta: "+customerID;
                                    } else {
                                        console.log(obj.error);
                                        alert(obj.error);
                                    }
                                }
                            })

					
				</script>

				<!-- wstawić kod na wyświetlanie imienia i nazwiska usera - TO DO FUNKCJA -->
				<p class=" w3-large w3-display-topmiddle">Witaj w Banku&#8482  <img src="images/icon.png" alt="icon" width="30" height="30"> </p>
			</div>
            <button class="w3-bar-item w3-button w3-hover-none w3-text-white w3-hover-text-black tablink" onclick="openTab('Konto')">Konto</button>
            <button class="w3-bar-item w3-button w3-hover-none w3-text-white w3-hover-text-black tablink"
			onclick="document.getElementById('Kredyt').style.display='block'">Kredyt</button>
            <button class="w3-bar-item w3-button w3-hover-none w3-text-white w3-hover-text-black tablink"
			onclick="document.getElementById('Przelew').style.display='block'">Przelew</button>
            <!-- przełączanie między kartami - DONE? kod do tab Konto nieużywany na razie -->
        </div>
    </div>
    <br><br><br><br>
	<!-- celowy <br> nie usuwać -->
	<!-- Tab Konto -->
    <div id="Konto" class="w3-display-container tab">
		<div class="w3-container  modern" style="padding-bottom: 0px;">
			<div class="w3-half w3-padding-large">
				<h2 class="modern" style="margin-bottom: 0%; padding-bottom: 0%;">Osobiste konto bankowe</h2>
				<p class="modern" style="margin-top: 0%; font-size: large; line-height: 0%;" id="customerID" ></p>
				<!-- wyświetlanie id konta/numer konta - TO DO FUNKCJA -->
			</div>
			<div class="w3-half">
				<p class="w3-xxlarge w3-left w3-margin-right"></p>
				<p class="w3-xxxlarge " style="margin-top: 26px;" id="balance"></p>
				<!-- wyświetlanie środków - TO DO FUNKCJA -->
			</div>
		</div>
	

		<div class="w3-container w3-light-grey">
		    <h2 style="padding-left: 32px;" >Historia konta</h2>
		</div>


        <table class="w3-table-all w3-hoverable">
			<thead id="transfersTableHead" >
				<tr class="w3-hover-none">
					<th style="padding-left: 64px;">
						Tytuł</th>
					<th>Rodzaj</th>
					<th>Kwota</th>
					<th>Kontrahent</th>
					<th>Data</th>
				</tr>	
			</thead>
			<tbody id="transfersTable" ></tbody>	
		</table>
		  <br>

		
		<script>
			var customerID = sessionStorage.getItem("customerID");

			jQuery.ajax({
				type: "POST",
						url: 'databaseManager.php',
						dataType: 'json',
						data: {functionname: 'getHistoryData', arguments: [Number(customerID)]},

						success: function(obj, textstatus){
							if( !('error' in obj)){

								const table =document.getElementById("transfersTable");

								for (let x = 0; x < obj.numRows; x++){
									
									let row = table.insertRow();
									
									let title = row.insertCell(0);
									title.innerHTML = obj.title[x];
										
									let type = row.insertCell(1);
									(obj.source[x] == customerID) ? type.innerHTML = "Wychodzący" : type.innerHTML = "Przychodzący";
									
									let	amount = row.insertCell(2);
									amount.innerHTML = obj.amount[x];
									
									let kontrahent = row.insertCell(3);
									let kontrahentID = -1;
									(obj.source[x] ==customerID) ? kontrahentID = obj.destination[x] : kontrahentID = obj.source[x];
									kontrahent.innerHTML = obj["'"+kontrahentID+"'"];
									// kontrahent.innerHTML = kontrahentNameArray[0];
									
									let date = row.insertCell(4);
									date.innerHTML = obj.date[x];
									
									
								}	
									
							} else {
								console.log(obj.error);
								alert(obj.error);
							}
							}
						
						})
	

		</script>
		
	<!-- footer -->
		<div class="w3-container w3-teal w3-center w3-small">
			<p>Autorzy projektu: Agnieszka Włodawiec, Bartłomiej Maślak, Kacper Lang, Stanisław Goździkowski</p>
		</div>
    </div>

	<script type="text/javascript">
		function loanFunction(){
			var customerID =sessionStorage.getItem("customerID");
			var amount =document.getElementById("loanAmount").value;

			jQuery.ajax({
						type: "POST",
                                url: 'databaseManager.php',
                                dataType: 'json',
                                data: {functionname: 'getLoan', arguments: [Number(amount), customerID]},

                                success: function(obj, textstatus){
                                    if( !('error' in obj)){
										alert("Twoje pieniądze wkrótce pojawią się na koncie");
										window.location.reload();
                                    } else {
                                        console.log(obj.error);
                                        alert(obj.error);
                                    }
                                }
                            })
		}
	</script>
	<!-- Modal Kredyt -->
	<div id="Kredyt" class="w3-modal w3-animate-opacity" >
		<div class="w3-modal-content w3-animate-opacity w3-card-4" style="max-width:600px">
		  <header class="w3-container w3-teal"> 
			<span onclick="document.getElementById('Kredyt').style.display='none'" 
			class="w3-button w3-display-topright w3-hover-text-black w3-hover-teal">&times;</span>
			<h2>Zaciągnij kredyt</h2>
		  </header>
		  <div class="w3-container">
			<div class="w3-row">
				<div class="w3-col w3-padding" style="width:50px"><p>PLN:</p></div>
				<div class="w3-col w3-quarter"><p><input id="loanAmount" class="w3-input w3-border" type="number" min="0.01" step="any" oninput="validity.valid||(value='');" placeholder="Wpisz kwotę"></div></p>
			</div>
				<button onclick="loanFunction();" class="w3-button w3-section w3-right w3-teal w3-hover-teal w3-hover-shadow">Wykonaj</div>
				<!-- TO DO funkcja kredytu -->
		  </div>
		</div>
	  </div>
	</div>

	<!-- Modal Przelew -->
	<script type="text/javascript" >
		function transferFunction(){
			var transfDestID =document.getElementById("transfDestID").value;
			var transfAmount =document.getElementById("transfAmount").value;
			var transfTitle =document.getElementById("transfTitle").value;
			var customerID =sessionStorage.getItem("customerID");
			var balance =sessionStorage.getItem("balance");

			jQuery.ajax({
						type: "POST",
                                url: 'databaseManager.php',
                                dataType: 'json',
                                data: {functionname: 'transferMoney', arguments: [customerID, transfDestID, Number(transfAmount), transfTitle]},

                                success: function(obj, textstatus){
                                    if( !('error' in obj)){
										alert("Przelew wysłany");
										window.location.reload();
                                    } else {
                                        console.log(obj.error);
                                        alert(obj.error);
                                    }
                                }
                            })
		}


	</script>
	<!-- TO DO funkcja przelewu -->
	<div id="Przelew" class="w3-modal w3-animate-opacity">
		<div class="w3-modal-content w3-animate-opacity w3-card-4"  style="max-width:600px">
		  <header class="w3-container w3-teal"> 
			<span onclick="document.getElementById('Przelew').style.display='none'" 
			class="w3-button w3-display-topright w3-hover-text-black w3-hover-teal">&times;</span>
			<h2>Wykonaj przelew</h2>
		  </header>
		  <div class="w3-container">
			<div class="w3-row w3-section">
				<label class="" >Odbiorca:</label>
				<input id="transfDestName" class="w3-input w3-border" type="text" placeholder="Wpisz nazwę odbiorcy">
			</div>
			<div class="w3-row w3-section">
				<label class="" >Na rachunek:</label>
				<input id="transfDestID" class="w3-input w3-border" type="number" min="0" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" placeholder="Wpisz numer rachunku">
			</div>
			<div class="w3-row w3-section">
				<label class="" >Kwota:</label>
				<input id="transfAmount" class="w3-input w3-border" type="number" min="0.01" step="any" oninput="validity.valid||(value='');" placeholder="Wpisz kwotę przelewu">
			</div>
			<div class="w3-row w3-section">
				<label class="" >Tytuł:</label>
				<input id="transfTitle" class="w3-input w3-border" type="text" placeholder="Wpisz tytuł przelewu">
			</div>
			<button onclick="transferFunction();" class="w3-button w3-section w3-right w3-teal w3-hover-teal w3-hover-shadow">Wykonaj</div>
		  </div>
		</div>
	  </div>
	</div>

    <script src="script.js"></script>
</body>
</html>

