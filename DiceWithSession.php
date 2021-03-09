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
				session_start();

				/* Variablen "disabled" deklareras och initieras till false och används för att avgöra om länkar ska ha bootstrap-klassen "disabled" eller inte. */
				$disabled = false;

				function deleteSession() { // Tar bort sessionskakan och förstör datan kopplad till pågående session.

					session_unset();

					if( ini_get("session.use_cookies") ) {

						$sessionCookieData = session_get_cookie_params();

						$path = $sessionCookieData["path"];
						$domain = $sessionCookieData["domain"];
						$secure = $sessionCookieData["secure"];
						$httponly = $sessionCookieData["httponly"];

						$name = session_name();

						setcookie($name, "", time() - 3600, $path, $domain, $secure, $httponly);
					}

					session_destroy();
				}

				//1
				/* Om query stringen "?linkNewGame" är satt skapas två sessionsvariabler. */
				if(isset($_GET["linkNewGame"]) ) {
					echo("<p>New game!</p>");
					$_SESSION["nbrOfRounds"] = 0;
					$_SESSION["sumOfAllrounds"] = 0;
				}

				//2
				/* Om länken "Exit" trycks och sessionsvariablerna är satta tas körs funktionen deleteSession(). */
				if(isset($_GET["linkExit"]) && isset($_SESSION["nbrOfRounds"]) && isset($_SESSION["sumOfAllrounds"]) ) {
					deleteSession();
				}

				//3
				/* Om man går in på sidan utan att ha någon tidigare session från sidan tas sessionen som skapas bort. */
				if(!isset($_GET["linkExit"]) && !isset($_GET["linkNewGame"]) && !isset($_GET["linkRoll"]) && !isset($_SESSION["nbrOfRounds"]) && !isset($_SESSION["sumOfAllrounds"]) ) {
					session_destroy();
				}

				//4
				/* Om ingen av länkarna är tryckta men sessionsvariablerna är satta körs följande kod. */
				if(!isset($_GET["linkExit"]) && !isset($_GET["linkNewGame"]) && !isset($_GET["linkRoll"]) && isset($_SESSION["nbrOfRounds"]) && isset($_SESSION["sumOfAllrounds"]) ) {
					echo( "<p>Antal: ".$_SESSION["nbrOfRounds"]."</p>"); // Skriver ut värdet i sessionsvariabeln "nbrOfRounds".
					echo ( "<p>Totalsumma: ".$_SESSION["sumOfAllrounds"]."</p>");

					/* Om någon av sessionsvariablerna har värdet 0 körs förljande sats för att undvika division med noll. */
					if($_SESSION["nbrOfRounds"] === 0 || $_SESSION["sumOfAllrounds"] === 0 ) {
						$medel = 0; // $medel tilldelas värdet 0.
					}
					else {
						$medel = $_SESSION["sumOfAllrounds"]/$_SESSION["nbrOfRounds"]; // Medelvärdet av alla tärningskast beräknas och placeras i $medel.
					}
					echo ( "<p>Medel: ".$medel."</p>");
				}

				//5
				/* Om länken "Roll six dices" är trycks och sessionsvariablerna är satta körs följande kod. */
				if(isset($_GET["linkRoll"]) && isset($_SESSION["nbrOfRounds"]) && isset($_SESSION["sumOfAllrounds"]) ) {
					$oSixDices = new SixDices(); // Ett objekt med referensen $oSixDices skapas av klassen "SixDices" och dess konstruktor körs som skapar en vektor av dess attribut "$sixDices".
					$oSixDices->rollDices(); // Funktionen "rollDices()" körs som simulerar 6 tärningskast genom att placera 6 objekt av OneDice-typ i vektorn $sixDices.
					echo( $oSixDices->svgDices() ); // svgDices() returnerar en sträng innehållande en div som illustrerar de sex tärningsobjekten mot en grön bakgrund.

					$nbr = $_SESSION["nbrOfRounds"] + 1; // Antalet gånger "Roll six dices" har tryckts + 1 placeras i $nbr.
					echo( "<p>Antal: ".$nbr."</p>");

					$sum = $_SESSION["sumOfAllrounds"];
					$sum +=$oSixDices->sumDices(); // Summan av alla tidigare tärningskast + värdena i $sixDices[] placeras i $sum.
					echo ( "<p>Totalsumma: ".$sum."</p>");

					$medel = $sum/$nbr;
					echo ( "<p>Medel: ".$medel."</p>");

					// Sessionsvariablerna får sina uppdaterade värden.
					$_SESSION["nbrOfRounds"] = $nbr;
					$_SESSION["sumOfAllrounds"] = $sum;
				}

				//6
				/* Om sessionsvariablerna "nbrOfRounds" och "sumOfAllrounds" inte är satta får blir länkarna "Roll six dices" och "Exit" ej användbara. */
				if(!isset($_SESSION["nbrOfRounds"]) && !isset($_SESSION["sumOfAllrounds"]) ) {
					$disabled = true;
				}

			?>
		</div>

		<a href="<?php echo( $_SERVER["PHP_SELF"] );?>?linkRoll=true" class="btn btn-primary<?php if( $disabled ) { echo( " disabled" ); } ?>">Roll six dices</a>
		<a href="<?php echo( $_SERVER["PHP_SELF"] );?>?linkNewGame=true" class="btn btn-primary">New game</a>
		<a href="<?php echo( $_SERVER["PHP_SELF"] );?>?linkExit=true" class="btn btn-primary<?php if( $disabled ) { echo( " disabled" ); } ?>">Exit</a>
		<!-- $_SERVER["PHP_SELF"] returnerar filnamnet och sökvägen dit.  -->
		<!-- if( $disabled ) { echo( " disabled" ); } gör knappen oklickbar när $disabled är satt till true -->
		<script src="script/animation.js"></script>

	</body>

</html>
