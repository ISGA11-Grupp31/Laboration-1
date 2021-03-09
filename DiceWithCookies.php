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

				$disabled = false;

				//1
				if(isset($_POST["btnNewGame"]) ) {
					echo("<p>New game!</p>");
					setcookie("nbrOfRounds", 0, time() + 3600);
					setcookie("sumOfAllrounds", 0, time() + 3600);
					$disabled = false;
				}

				//2
				if(!isset($_POST["btnRoll"]) && !isset($_POST["btnNewGame"]) && !isset($_POST["btnExit"]) && isset($_COOKIE["nbrOfRounds"]) && isset($_COOKIE["sumOfAllrounds"])) {
					echo( "<p>Antal: ".$_COOKIE["nbrOfRounds"]."</p>");
					echo ( "<p>Totalsumma: ".$_COOKIE["sumOfAllrounds"]."</p>");
					$medel = $_COOKIE["sumOfAllrounds"]/$_COOKIE["nbrOfRounds"];
					echo ( "<p>Medel: ".$medel."</p>");
					//När cookievärdena är 0 och sidan laddas om blir det division med 0 och Internal Server Error.
				}

				//3
				if( isset($_POST["btnRoll"]) && isset($_COOKIE["nbrOfRounds"]) && isset($_COOKIE["sumOfAllrounds"]) ) {
					$oSixDices = new SixDices();
					$oSixDices->rollDices();
					echo( $oSixDices->svgDices() );

					$nbr = $_COOKIE["nbrOfRounds"] + 1;
					echo( "<p>Antal: ".$nbr."</p>");

					$sum = $_COOKIE["sumOfAllrounds"];
					$sum +=$oSixDices->sumDices();
					echo ( "<p>Totalsumma: ".$sum."</p>");

					$medel = $sum/$nbr;
					echo ( "<p>Medel: ".$medel."</p>");

					setcookie("nbrOfRounds", $nbr, time() + 3600);
					setcookie("sumOfAllrounds", $sum, time() + 3600);
				}

				//4
				if( isset($_POST["btnExit"]) && isset($_COOKIE["nbrOfRounds"]) && isset($_COOKIE["sumOfAllrounds"]) ) {
					setcookie("nbrOfRounds", "", time() - 3600);
					setcookie("sumOfAllrounds", "", time() - 3600);
					$disabled = true;
				}

				//5
				if( !isset($_COOKIE["nbrOfRounds"]) && !isset($_COOKIE["sumOfAllrounds"])) {
					$disabled = true;
				}
				//Var uppmärksam på att PHP-tolken används på ett flertal ställen i filen!
			?>
		</div>

		<form action="<?php echo( $_SERVER["PHP_SELF"] ); ?>" method="post">
		 <!-- $_SERVER["PHP_SELF"] returnerar filnamnet och sökvägen dit...  -->
			<input type="submit" name="btnRoll" class="btn btn-primary" value="Roll six dices" <?php if( $disabled ) { echo( "disabled" ); } ?>/>
			<input type="submit" name="btnNewGame" class="btn btn-primary" value="New Game" />
			<input type="submit" name="btnExit" class="btn btn-primary" value="Exit" <?php if( $disabled ) { echo( "disabled" ); } ?>/>
		</form>
		<!-- if( $disabled ) { echo( "disabled" ); } gör knappen oklickbar när $disabled är satt till true -->

		<script src="script/animation.js"></script>
	</body>

</html>
