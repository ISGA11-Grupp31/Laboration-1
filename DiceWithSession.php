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

			$disabled = false;

			function deleteSession() {

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
			if(isset($_GET["linkNewGame"]) ) {
				echo("<p>New game!</p>");
				$_SESSION["nbrOfRounds"] = 0;
				$_SESSION["sumOfAllrounds"] = 0;
				//$disabled = false;
			}

			//2
			if(isset($_GET["linkExit"]) && isset($_SESSION["nbrOfRounds"]) && isset($_SESSION["sumOfAllrounds"]) ) {
				deleteSession();
			}

			//3
			if(!isset($_GET["linkExit"]) && !isset($_GET["linkNewGame"]) && !isset($_GET["linkRoll"]) && !isset($_SESSION["nbrOfRounds"]) && !isset($_SESSION["sumOfAllrounds"]) ) {
				session_destroy();
			}

			//4
			if(!isset($_GET["linkExit"]) && !isset($_GET["linkNewGame"]) && !isset($_GET["linkRoll"]) && isset($_SESSION["nbrOfRounds"]) && isset($_SESSION["sumOfAllrounds"]) ) {
				echo( "<p>Antal: ".$_SESSION["nbrOfRounds"]."</p>");
				echo ( "<p>Totalsumma: ".$_SESSION["sumOfAllrounds"]."</p>");
				$medel = $_SESSION["sumOfAllrounds"]/$_SESSION["nbrOfRounds"];
				echo ( "<p>Medel: ".$medel."</p>");
				//Samma sak här, om cookievärdena är 0 och sidan laddas om blir det division med 0 och Internal Server Error.
			}

			//5
			if(isset($_GET["linkRoll"]) && isset($_SESSION["nbrOfRounds"]) && isset($_SESSION["sumOfAllrounds"]) ) {
				$oSixDices = new SixDices();
				$oSixDices->rollDices();
				echo( $oSixDices->svgDices() );

				$nbr = $_SESSION["nbrOfRounds"];
				$nbr = $nbr+1;
				$_SESSION["nbrOfRounds"] = $nbr;
				echo( "<p>Antal: ".$_SESSION["nbrOfRounds"]."</p>");

				$sum = $_SESSION["sumOfAllrounds"];
				$sum +=$oSixDices->sumDices();
				$_SESSION["sumOfAllrounds"] = $sum;
				echo ( "<p>Totalsumma: ".$_SESSION["sumOfAllrounds"]."</p>");

				$medel = $_SESSION["sumOfAllrounds"]/$_SESSION["nbrOfRounds"];
				echo ( "<p>Medel: ".$medel."</p>");
			}

			//6
			if(!isset($_SESSION["nbrOfRounds"]) && !isset($_SESSION["sumOfAllrounds"]) ) {
				$disabled = true;
			}

			?>
		</div>

		<a href="<?php echo( $_SERVER["PHP_SELF"] );?>?linkRoll=true" class="btn btn-primary<?php if( $disabled ) { echo( " disabled" ); } ?>">Roll six dices</a>
		<a href="<?php echo( $_SERVER["PHP_SELF"] );?>?linkNewGame=true" class="btn btn-primary">New game</a>
		<a href="<?php echo( $_SERVER["PHP_SELF"] );?>?linkExit=true" class="btn btn-primary<?php if( $disabled ) { echo( " disabled" ); } ?>">Exit</a>

		<script src="script/animation.js"></script>

	</body>

</html>
