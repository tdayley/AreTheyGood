<?php include('variables/private_variables.php') ?>
<?php include('includes/sql_connection.php') ?>
<?php include('includes/champion_mastery.php') ?>
<?php include('includes/mastery_view.php') ?>
<?php include('includes/functions.php') ?>

<?php
	if(!isset($_POST["region"])) 
	{
		return Alert(AlertType::Error, "The region was not sent correctly. Refresh and try again.");
		exit;
	}
	if(!isset($_POST["summonerName"])) 
	{
		return Alert(AlertType::Error, "The summoner name was not sent correctly. Refresh and try again.");
		exit;
	}
	
	$region = $_POST["region"];
	$summonerName = str_replace(' ', '', $_POST["summonerName"]);
	
	// Add summoner information if it is not in the database
	if(!SummonerExists($conn, $summonerName, $region))
	{
		$url = GetSummonerInfoUrl($region, $summonerName, $apiKey);

		$apiResults = GetApiResults($url);
		
		$httpCode = $apiResults["httpCode"];
		$json = $apiResults["json"];
		
		if($httpCode != 200)
		{
			if($httpCode == 404)
			{
				return Alert(AlertType::Info, "<span class='status-code'>" . $httpCode . "</span><span class='status-message'>No summoner data found for the given region and name</span>");
			}
			
			return Alert(AlertType::Info, "<span class='status-code'>" . $httpCode . "</span><span class='status-message'>" . $json["status"]["message"] . "</span>");
		}
		
		AddSummonerToDatabase($conn, $json[strtolower($summonerName)], $region);
	}
	
	$summonerInformation = GetSummonerInformation($conn, $summonerName, $region);
	
	$summonerId = $summonerInformation->getId();
	$summonerName = $summonerInformation->getName();
	$masteriesExist = $summonerInformation->getMasteriesExist();
	$lastSearched = $summonerInformation->getLastSearched();

	date_default_timezone_set('America/Los_Angeles');
	$lastSearchedDateTime = new DateTime($lastSearched);
	$lastSearched = $lastSearchedDateTime->format('j M Y g:i A');
	
	$currentDateTime = new DateTime();
	$dateDiff = $currentDateTime->diff($lastSearchedDateTime);
	$hoursSinceSearched = $dateDiff->h;
	$hoursSinceSearched = $hoursSinceSearched + ($dateDiff->days * 24);

	$masteriesUpdated = false;
	
	// Add summoner mastery information if it is not in the database
	if(!$masteriesExist)
	{
		// Only check if have not searched in last hour
		if($hoursSinceSearched > 3)
		{
			$url = GetChampionMasteryUrl($conn, $summonerId, $apiKey);
			
			$apiResults = GetApiResults($url);
			
			$httpCode = $apiResults["httpCode"];
			$json = $apiResults["json"];
			
			if($httpCode != 200)
			{
				if($httpCode == 404)
				{
					return Alert(AlertType::Info, "<span class='status-code'>" . $httpCode . "</span><span class='status-message'>No summoner mastery data found for the given region and name</span>");
					exit;
				}
					
				return Alert(AlertType::Info, "<span class='bold'>" . $httpCode . "</span> " . $json["status"]["message"]);
				exit;
			}
			if(empty($json))
			{
				return Alert(AlertType::Info, "No summoner mastery data found for the given region and name");
				exit;
			}
			
			AddOrUpdateChampionMastery($conn, $json, $summonerId, $currentDateTime, $masteriesExist);
			$masteriesUpdated = true;
		}
	}
	
	$championMastery = GetChampionMastery($conn, $summonerId);
	
	$html = CreateMasteryView($conn, $championMastery);
	
	$masteriesUpdatedHtml = $masteriesUpdated ? "<div class='mastery-header-update'>Masteries were just updated</div>" : 
		"<div class='mastery-header-update'>Masteries were updated $lastSearched</div>";
	
	$html = "<div class='mastery-header'><button type='button' class='js-refresh-mastery refresh-button'>Refresh</button>
		<div class='mastery-header-title'>Champion Mastery for $summonerName</div>" . $masteriesUpdatedHtml . "</div>" . $html;
	
	$data = array(
		'html' => $html,
		'target' => 'update-section',
		'summonerName' => $summonerName,
		'region' => $region
	);
	
	header('Content-type: application/json');
	echo json_encode($data);
?>