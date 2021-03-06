<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=Cp1252">
		
		<title>Contact - Are they good...</title>
	</head>
	<body>
		<div class="header">
			<div class="header-image">
				<img class="logo" src="/images/AreTheyGoodLogo_Small.png" 
					srcset="images/AreTheyGoodLogo_Large.png 1024w, images/AreTheyGoodLogo_Medium.png 640w, images/AreTheyGoodLogo_Small.png 320w" alt="Are they good" />
			</div>
			<div class="header-title">
				<div class="header-links">
					<a href="/">Home</a>
					<a class="margin-left-20" href="/about.php">About</a>
					<a class="margin-left-20" href="/contact.php">Contact</a>
				</div>
				<div class="main-title">Contact</div>
				<div class="sub-title">Are they good...</div>
			</div>
		</div>
		
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div data-ajax-id="alert-notification"></div>
					<div class="margin-bottom-25">
						Email me directly at <a href="mailto:development@timdayley.com">development@timdayley.com</a>
					</div>
					<form class="js-summoner-name-form" action="send_message.php" method="POST">
						<div class="td-form-group">
							<label class="td-form-label">Name (optional)</label>
							<input class="td-form-control" type="text" name="queryName" placeholder="Your name" />
						</div>
						<div class="td-form-group">
							<label class="td-form-label">Email (optional)</label>
							<input class="td-form-control" type="text" name="queryEmail" placeholder="Your email" />
						</div>
						<div class="td-form-group">
							<label class="td-form-label">Message</label>
							<textarea class="td-form-control" name="queryMessage" placeholder="Your message"></textarea>
						</div>
						<button class="td-form-button" type="submit">Send</button>
					</form>
				</div>
			</div>
		</div>
			
		<?php include('includes/apichallenge_footer.php'); ?>
		
		<link href='http://fonts.googleapis.com/css?family=Lato:400,700,400italic' rel='stylesheet' type='text/css'>
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		
		<link rel="stylesheet" type="text/css" href="/stylesheets/apichallenge.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="/stylesheets/circle.css" media="screen" />
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script src="scripts/td-apichallenge.js"></script>
	</body>
</html>