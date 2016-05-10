<?php
	function GetChampionImage($conn, $championId)
	{
		$sql = "SELECT champion_image.Id, champion_image.Image, champion_information.ChampionKey FROM champion_image 
			JOIN champion_information ON champion_image.Id = champion_information.Id 
			WHERE champion_information.Id = :id";
			
		$query = $conn->prepare($sql);
		$query->bindValue(":id", $championId);
		
		$query->execute();
		
		if($query->rowCount() == 0)
		{
			$realmUrl = GetStaticInfoUrl("realm", "na", $apiKey);
			$realmInfo = GetApiResults($realmUrl);
				
			$versionUrl = GetStaticInfoUrl("versions", "na", $apiKey);
			$versionInfo = GetApiResults($versionUrl);
				
			$currentVersion = $versionInfo[0];
			$url = $realmInfo->{"cdn"};
				
			$urlForImages = $url . "/" . $currentVersion . "/img/champion/";
				
			$imageUrl = $urlForImages . $key . ".png";
			
			return AddChampionImage($conn, $championId, $imageUrl);
		}

		$imageData = $query->fetchAll();
// 		print_r($imageData);
		return base64_encode($imageData[0]["Image"]);
	}
	
	function AddChampionImage($conn, $id, $imageUrl)
	{
		$imageContents = file_get_contents($imageUrl);
		$sql = "INSERT INTO champion_image(Id, Image) VALUES (:id, :image)";
			
		$query = $conn->prepare($sql);
		$query->bindValue(":id", $id);
		$query->bindValue(":image", $imageContents);
		$query->execute();
	
		return $imageContents;
	}
?>