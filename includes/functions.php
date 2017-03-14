<?php
	class Summoner
	{
		private $Id;
		private $Name;
		private $Region;
		private $ProfileIconId;
		private $RevisionDate;
		private $SummonerLevel;
		private $MasteriesExist;
		private $LastSearched;
	
		public function __construct($id, $name, $region, $profileIconId, $revisionDate, $summonerLevel, $masteriesExist, $lastSearched)
		{
			$this->Id = $id;
			$this->Name = $name;
			$this->Region = $region;
			$this->ProfileIconId = $profileIconId;
			$this->RevisionDate = $revisionDate;
			$this->SummonerLevel = $summonerLevel;
			$this->MasteriesExist = $masteriesExist;
			$this->LastSearched = $lastSearched;
		}
	
		public function getId()
		{
			return $this->Id;
		}
	
		public function getName()
		{
			return $this->Name;
		}
	
		public function getRegion()
		{
			return $this->Region;
		}
	
		public function getProfileIconId()
		{
			return $this->ProfileIconId;
		}
	
		public function getRevisionDate()
		{
			return $this->RevisionDate;
		}
	
		public function getSummonerLevel()
		{
			return $this->SummonerLevel;
		}
	
		public function getMasteriesExist()
		{
			return $this->MasteriesExist;
		}
	
		public function getLastSearched()
		{
			return $this->LastSearched;
		}
	}
	
	abstract class AlertType {
		const Success = 0;
		const Info = 1;
		const Warning = 2;
		const Error = 3;
	}
	
	function GetApiResults($url)
	{
		$curl = curl_init($url);
	
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	
		$result = curl_exec($curl);
		$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
		curl_close($curl);
	
		$json = json_decode($result, true);
	
		$results = array(
				"httpCode" => $httpCode,
				"json" => $json
		);
	
		return $results;
	}
	
	function GetStaticInfoUrl($type, $region, $apiKey, $id=NULL)
	{
		if(NULL === $id)
		{
			return "https://global.api.pvp.net/api/lol/static-data/" . strToLower($region) . "/v1.2/" . $type . "/" . $id . "?api_key=" . $apiKey;
		}
			
		return "https://global.api.pvp.net/api/lol/static-data/" . strToLower($region) . "/v1.2/" . $type . "?api_key=" . $apiKey;
	}
	
	function GetSummonerInfoUrl($region, $summonerName, $apiKey)
	{
		$regionLower = strToLower($region);
		return "https://$regionLower.api.pvp.net/api/lol/$regionLower/v1.4/summoner/by-name/$summonerName?api_key=$apiKey";
	}
	
	function GetChampionMasteryUrl($conn, $summonerId, $apiKey)
	{
		$query = "SELECT summoner_information.Region, region_information.PlatformId FROM summoner_information
				JOIN region_information ON summoner_information.Region = region_information.Acronym
				WHERE summoner_information.Id = :id";
	
		$summonerInformation = $conn->prepare($query);
		$summonerInformation->bindValue(":id", $summonerId);
	
		$summonerInformation->execute();
	
		$result = $summonerInformation->fetch();
	
		$region = $result["Region"];
		$regionPlatformId = $result["PlatformId"];
	
		return "https://$region.api.pvp.net/championmastery/location/$regionPlatformId/player/$summonerId/champions?api_key=$apiKey";
	}
	
	function SummonerExists($conn, $summonerName, $region)
	{
		$sql = "SELECT COUNT(1) FROM summoner_information WHERE LCASE(REPLACE(Name, ' ', '')) = :summonerName AND Region = :region";
			
		$query = $conn->prepare($sql);
		$query->bindValue(":summonerName", $summonerName);
		$query->bindValue(":region", $region);
		$query->execute();
		$count = $query->fetchColumn();
			
		return $count != 0;
	}
	
	function AddSummonerToDatabase($conn, $summonerInformation, $region)
	{
		$query = "INSERT INTO summoner_information(Id, Name, Region, ProfileIconId, RevisionDate, SummonerLevel) VALUES (:id, :name, :region, :profileIconId, :revisionDate, :summonerLevel)";
	
		$summonerResults = $conn->prepare($query);
		$summonerResults->bindValue(":id", $summonerInformation["id"]);
		$summonerResults->bindValue(":name", $summonerInformation["name"]);
		$summonerResults->bindValue(":profileIconId", $summonerInformation["profileIconId"]);
		$summonerResults->bindValue(":revisionDate", $summonerInformation["revisionDate"]);
		$summonerResults->bindValue(":summonerLevel", $summonerInformation["summonerLevel"]);
		$summonerResults->bindValue(":region", $region);
	
		$summonerResults->execute();
	}
	
	function AddOrUpdateChampionMastery($conn, $championMastery, $summonerId, $currentDateTime, $masteriesExist)
	{
		if($masteriesExist)
		{
			$sql = "DELETE FROM champion_mastery WHERE PlayerId = :summonerId";
			
			$query = $conn->prepare($sql);
			$query->bindValue(":summonerId", $summonerId);
			
			$query->execute();
		}
		
		foreach($championMastery as $mastery)
		{
			$columns = array();
			$placeHolders = array();
			$columnValues = array();
			foreach(array_keys($mastery) as $columnName)
			{
				array_push($columns, $columnName);
	
				array_push($placeHolders, ":$columnName");
	
				array_push($columnValues, $mastery[$columnName]);
			}
				
			$query = "INSERT INTO champion_mastery(" . rtrim(implode(',', $columns), ',') . ") VALUES (" . rtrim(implode(',', $placeHolders), ',') . ")";
				
			$masteryResults = $conn->prepare($query);
				
			$count = 0;
			foreach($placeHolders as $holder)
			{
				$masteryResults->bindValue($holder, $columnValues[$count++]);
			}
				
			$masteryResults->execute();
		}
	
		$sql = "UPDATE summoner_information SET LastSearched = :dateTime, MasteriesExist = TRUE WHERE Id = :summonerId";
	
		$query = $conn->prepare($sql);
		$query->bindValue(":summonerId", $summonerId);
		$query->bindValue(":dateTime", $currentDateTime->format('Y-m-d H:i:s'));
	
		$query->execute();
	}
	
	function GetSummonerInformation($conn, $summonerName, $region)
	{
		$query = "SELECT * FROM summoner_information WHERE LCASE(REPLACE(Name, ' ', '')) = :summonerName AND Region = :region";
	
		$summonerInformation = $conn->prepare($query);
		$summonerInformation->bindValue(":summonerName", $summonerName);
		$summonerInformation->bindValue(":region", $region);
	
		$summonerInformation->execute();
	
		$result = $summonerInformation->fetch();
	
		$summoner = new Summoner($result["Id"], $result["Name"], $result["Region"], $result["ProfileIconId"], $result["RevisionDate"], $result["SummonerLevel"],
				$result["MasteriesExist"], $result["LastSearched"]);
	
		return $summoner;
	}
	
	function GetChampionMastery($conn, $summonerId)
	{
		$query = "SELECT a.*, b.Name AS ChampionName, b.ChampionKey 
				FROM champion_mastery a 
				JOIN champion_information b ON a.ChampionId=b.Id 
				WHERE PlayerId = :id ORDER BY ChampionPoints DESC";
	
		$masteryInformation = $conn->prepare($query);
		$masteryInformation->bindValue(":id", $summonerId);
	
		$masteryInformation->execute();
	
		$championMastery = array();
		foreach($masteryInformation->fetchAll() as $row) {
			array_push($championMastery, new ChampionMastery($row["ChampionId"], $row["ChampionName"], $row["ChampionKey"], $row["ChampionLevel"], $row["ChampionPoints"], $row["ChampionPointsSinceLastLevel"],
					$row["ChampionPointsUntilNextLevel"], $row["ChestGranted"], $row["HighestGrade"], $row["LastPlayTime"], $row["PlayerId"]));
		}
	
		return $championMastery;
	}
	
	function Alert($alertType, $message)
	{
		switch($alertType)
		{
			case 0:
				$htmlFragment = sprintf("<div data-ajax-id='alert-notification' class='alert alert-success text-center' role='alert'>%s</div>", $message);
				break;
			case 1:
				$htmlFragment = sprintf("<div data-ajax-id='alert-notification' class='alert alert-info text-center' role='alert'>%s</div>", $message);
				break;
			case 2:
				$htmlFragment = sprintf("<div data-ajax-id='alert-notification' class='alert alert-warning text-center' role='alert'>%s</div>", $message);
				break;
			case 3:
				$htmlFragment = sprintf("<div data-ajax-id='alert-notification' class='alert alert-danger text-center' role='alert'>%s</div>", $message);
				break;
			default:
				$htmlFragment = sprintf("<div data-ajax-id='alert-notification' class='alert alert-info text-center' role='alert'>%s</div>", $message);
				break;
		}
	
		$data = array(
				'html' => $htmlFragment,
				'target' => 'alert-notification'
		);
		header('Content-type: application/json');
		echo json_encode($data);
	}
	
	function CleanString($string) {
		return preg_replace('/[^A-Za-z0-9]/', '', $string);
	}
?>