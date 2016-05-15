<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=Cp1252">
		
		<title>About - Are they good...</title>
	</head>
	<body>
		<div class="header">
			<div class="header-image">
				<img alt="Logo" src="/images/AreTheyGoodLogo.png" class="logo" />
			</div>
			<div class="header-title">
				<div class="header-links">
					<a href="/">Home</a>
					<a class="margin-left-20" href="/about.php">About</a>
					<a class="margin-left-20" href="/contact.php">Contact</a>
				</div>
				<div class="main-title">About</div>
				<div class="sub-title">Are they good...</div>
			</div>
		</div>
		
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="content-section">
						<div class="content-title">What does it do?</div>
						<div class="content-text">
						 	You can find the project and files at <a href="https://github.com/tdayley/AreTheyGood" target="about:blank">https://github.com/tdayley/AreTheyGood</a>
						 	<br>
						 	<br>
							Are they good... is a web application that uses the League of Legends API (Application Programming Interface) to display champion 
							mastery information for a desired summoner. All that you need to know is the summoner name and the region you are playing on! 
							The application will search the live database to see if it already has any information on the summoner in question. If no data exists, 
							a call will be made to the API and the information displayed.
							
							There is also an option to refresh the mastery information if you believe it to be outdated, or just want the most recent information. 
							A summoners mastery information is limited to be refreshed every 3 hours as to avoid constant calls to the API.
						</div>
					</div>
					<div class="content-section">
						<div class="content-title">Why did you make it?</div>
						<div class="content-text">
							When I start a new match of League of Legends, I usually go to some site that tells me my opponents recent history and thir play styles. 
							From that information I can decide whether they are playing a role they commonly play or not. Most of the time that information is enough, 
							and I can determine whether my opponent is skilled with the champion or not.
							
							With the champion mastery information, you can see exactly how much time a summoner has put into the champion they are playing. For example,
							you may be laning against an Ashe with the summoner name NumberOneSummoner, and upon looking up his or her champion mastery information, you 
							see that this will probably be an easy lane for you as a master ADC. But if you're unlucky...they are playing Master Yi, and you are about to 
							get Penta killed.
							<img src="/images/SampleSummonerInfo.png" alt="Sample Summoner Information" class="sample-summoner-info" />
					</div>
					</div>
						<div class="content-section">
						<div class="content-title">Future plans</div>
						<div class="content-text">
							While this is a fully functioning web application, I definitely have some improvements that I would like to make. The following is a list of 
							these improvements in no particular order. If you have any ideas for me, or ways to improve this site, feel free to send me an email with your 
							suggestions.
							<ul>
								<li>Add a sorting mechanism to be able to find a particular champion for the summoner in question.</li>
								<li>When a summoner is searched, also search the summoners of the last game they played against in order to have the database grow faster.</li>
								<li>Convert the last played and last updated dates to a more friendly name (i.e. 1 day ago, 1 week ago, etc.).</li>
								<li>Add autocomplete to the summoner search input with a list of summoners already contained in the database.</li>
								<li>Include details of last game played with each champion that the summoner has mastery data on.</li>
							</ul>
						</div>
					</div>
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