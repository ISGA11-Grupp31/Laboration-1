<!doctype html>
<html lang="en" >

	<head>
		<meta charset="utf-8" />
		<title>Roll the dice...</title>
		<link href="style/style.css" rel="stylesheet" />
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	</head>

	<body>

		<div>
			<?php
				include( "include/OneDice.php" );
				include( "include/SixDices.php" );

				/* Variablen "disabled" deklareras och initieras till true och används för att avgöra om knappar i formuläret ska ha attributet disabled eller inte. */
				$disabled = true;

				//1
				/* Nedanstående if-sats exekveras när sumbitknappen "btnNewGame" trycks och skapar två kakor med livslängd på en timme, eller om de redan finns, ändrar dess parametrar. */
				if(isset($_POST["btnNewGame"]) ) {
					echo("<p>New game!</p>"); //Skriver ut strängen med html-kod.
					setcookie("nbrOfRounds", "0", time() + 3600); // Kaka som används för att räkna antalet gånger tärningar rullats.
					setcookie("sumOfAllrounds", "0", time() + 3600);// Kaka för att summera den totala summan av alla tärningskast.
					$disabled = false; // Gör knapparna "btnRoll" och "btnExit" i formuläret användbara.
				}

				//2
				/* Om sidan laddas utan att någon av formulär-knapparna är tryckta men kakorna finns på klienten körs följande kod */
				if(!isset($_POST["btnRoll"]) && !isset($_POST["btnNewGame"]) && !isset($_POST["btnExit"]) && isset($_COOKIE["nbrOfRounds"]) && isset($_COOKIE["sumOfAllrounds"]) ) {
					echo( "<p>Antal: ".$_COOKIE["nbrOfRounds"]."</p>"); // Skriver ut värdet i kakan "nbrOfRounds".
					echo ( "<p>Totalsumma: ".$_COOKIE["sumOfAllrounds"]."</p>");

					/* Om någon av kakorna har värdet "0" körs förljande sats för att undvika division med noll. */
					if($_COOKIE["nbrOfRounds"] === "0" || $_COOKIE["sumOfAllrounds"] === "0" ) {
						$medel = 0; // $medel tilldelas värdet 0.
					}
					else {
						$medel = $_COOKIE["sumOfAllrounds"]/$_COOKIE["nbrOfRounds"]; // Medelvärdet av alla tärningskast beräknas och placeras i $medel.
					}
					echo ( "<p>Medel: ".$medel."</p>");
					$disabled = false;

				}

				//3
				/* Om knappen "btnRoll" trycks och kakorna är satta körs följande kod. */
				if( isset($_POST["btnRoll"]) && isset($_COOKIE["nbrOfRounds"]) && isset($_COOKIE["sumOfAllrounds"]) ) {
					$oSixDices = new SixDices(); // Ett objekt med referensen $oSixDices skapas av klassen "SixDices" och dess konstruktor körs som skapar en vektor av dess attribut "$sixDices".
					$oSixDices->rollDices(); // Funktionen "rollDices()" körs som simulerar 6 tärningskast genom att placera 6 objekt av OneDice-typ i vektorn $sixDices.
					echo( $oSixDices->svgDices() ); // svgDices() returnerar en sträng innehållande en div som illustrerar de sex tärningsobjekten mot en grön bakgrund.

					$nbr = $_COOKIE["nbrOfRounds"] + 1; // Antalet gånger "btnRoll" har tryckts + 1 placeras i $nbr.
					echo( "<p>Antal: ".$nbr."</p>");

					$sum = $_COOKIE["sumOfAllrounds"];
					$sum +=$oSixDices->sumDices(); // Summan av alla tidigare tärningskast + värdena i $sixDices[] placeras i $sum.
					echo ( "<p>Totalsumma: ".$sum."</p>");

					$medel = $sum/$nbr;
					echo ( "<p>Medel: ".$medel."</p>");

					// Kakorna värden tilldelas $nbr respektive $sum och deras livslängd ställs om.
					setcookie("nbrOfRounds", $nbr, time() + 3600);
					setcookie("sumOfAllrounds", $sum, time() + 3600);

					$disabled = false;
				}

				//4
				/* Om "btnExit" trycks och kakorna finns på servern tas de bort. */
				if( isset($_POST["btnExit"]) && isset($_COOKIE["nbrOfRounds"]) && isset($_COOKIE["sumOfAllrounds"]) ) {
					setcookie("nbrOfRounds", "", time() - 3600);
					setcookie("sumOfAllrounds", "", time() - 3600);

				}

				//5
				/*if( !isset($_COOKIE["nbrOfRounds"]) && !isset($_COOKIE["sumOfAllrounds"])) {
					$disabled = true;
				}*/
			?>
		</div>

		<form action="<?php echo( $_SERVER["PHP_SELF"] ); ?>" method="post">
		 <!-- $_SERVER["PHP_SELF"] returnerar filnamnet och sökvägen dit.  -->
			<input type="submit" name="btnRoll" class="btn btn-primary" value="Roll six dices" <?php if( $disabled ) { echo( "disabled" ); } ?>/>
			<input type="submit" name="btnNewGame" class="btn btn-primary" value="New Game" />
			<input type="submit" name="btnExit" class="btn btn-primary" value="Exit" <?php if( $disabled ) { echo( "disabled" ); } ?>/>
			<!-- if( $disabled ) { echo( "disabled" ); } gör knappen oklickbar när $disabled är satt till true -->
		</form>

		<script src="script/animation.js"></script>
	</body>

</html>
