<?php include('variables/private_variables.php'); ?>
<?php include('includes/sql_connection.php'); ?>
<?php include('includes/champion_image.php'); ?>

	<div class="container-fluid">
		<?php 
			class Region
			{
				private $Name;
				private $Acronym;
					
				public function __construct($name, $acronym)
				{
					$this->Name = $name;
					$this->Acronym = $acronym;
				}
					
				public function getName()
				{
					return $this->Name;
				}
					
				public function getAcronym()
				{
					return $this->Acronym;
				}
			}
			
			$regions = array();
			
			$region = new Region("Brazil", "BR");
			array_push($regions, $region);
			
			$region = new Region("North America", "NA");
			array_push($regions, $region);
			
			$region = new Region("Russia", "RU");
			array_push($regions, $region);
			
			$region = new Region("Europe North East", "EUNE");
			array_push($regions, $region);
			
			$region = new Region("Europe West", "EUW");
			array_push($regions, $region);
			
			$region = new Region("Japan", "JP");
			array_push($regions, $region);
			
			$region = new Region("Korea", "KR");
			array_push($regions, $region);
			
			$region = new Region("Latin America North", "LAN");
			array_push($regions, $region);
			
			$region = new Region("Latin America South", "LAS");
			array_push($regions, $region);
			
			$region = new Region("Oceanic", "OCE");
			array_push($regions, $region);
			
			$region = new Region("Turkey", "TR");
			array_push($regions, $region);
		?>
		<div class="row margin-bottom-50">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<form class="js-summoner-name-form" action="summoner_information.php" method="POST">
					<div class="form-group summoner-name-input">
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 wrap-margin">
							<select name="region" class="td-form-control">
								<?php 
									foreach($regions as $region)
									{
										if($region->getAcronym() == "NA")
										{
											echo "<option selected='selected' value='" . $region->getAcronym() ."' title='" . $region->getName() . "'>" . $region->getAcronym() . 
												"</option>";
										}
										else
										{
											echo "<option value='" . $region->getAcronym() ."' title='" . $region->getName() . "'>" . $region->getAcronym() . "</option>";
										}
									}
								?>
							</select>
						</div>
						<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
							<div class="row">
								<div class="col-lg-10 col-md-10 col-sm-10 col-xs-9">
									<input id="summoner-name" type="text" name="summonerName" placeholder="Summoner Name" class="td-form-control" />
								</div>
								<div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
									<button type="submit" name="SubmitButton" class="td-form-control">Search</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div data-ajax-id="alert-notification"></div>
				<div data-ajax-id="update-section">
					<?php 
					class Champion
					{
						private $Id;
						private $Name;
						private $ChampionKey;
						private $Title;
						
						public function getId()
						{
							return $this->Id;
						}
						
						public function getName()
						{
							return $this->Name;
						}
						
						public function getChampionKey()
						{
							return $this->ChampionKey;
						}
						
						public function getTitle()
						{
							return $this->Title;
						}
					}
					
					function GetApiResults($url) 
					{
						$response = file_get_contents($url);
						
						$response = json_decode($response);
						
						return $response;
					}
					
					function HaveMostRecentRealmVersion($conn, $version)
					{
						$query = "SELECT Version FROM realm_version WHERE Id = (SELECT MAX(Id) FROM realm_version)";
						
						$realmVersion = $conn->prepare($query);
						$realmVersion->execute();
						
						$result = $realmVersion->fetch(PDO::FETCH_ASSOC);
						
						return $result["Version"] == $version;
					}
					
					function UpdateVersionInfo($conn, $version, $url)
					{
						$query = "INSERT INTO realm_version(Version, Url) VALUES (:version, :url)";
						
						$realmVersion = $conn->prepare($query);
						$realmVersion->bindValue(":version", $version);
						$realmVersion->bindValue(":url", $url);
						$realmVersion->execute();
					}
					
					function UpdateChampionInfo($conn, $championInfo)
					{
						foreach($championInfo->{"data"} as $champion)
						{
							$sql = "SELECT COUNT(1) FROM champion_information WHERE Id = :id";
							
							$query = $conn->prepare($sql);
							$query->bindValue(":id", $champion->{"id"});
							$query->execute();
							$count = $query->fetchColumn();
							
							if($count == 0)
							{
								$sql = "INSERT INTO champion_information(Id, Name, Title, ChampionKey) VALUES (:id, :name, :title, :championKey)";
								
								$query = $conn->prepare($sql);
								$query->bindValue(":id", $champion->{"id"});
								$query->bindValue(":name", $champion->{"name"});
								$query->bindValue(":title", $champion->{"title"});
								$query->bindValue(":championKey", $champion->{"key"});
								$query->execute();
							}
						}
					}
					
					function GetStaticInfoUrl($type, $region, $apiKey, $id=NULL)
					{
						if(NULL === $id)
						{
							return "https://global.api.pvp.net/api/lol/static-data/" . strToLower($region) . "/v1.2/" . $type . "/" . $id . "?api_key=" . $apiKey;
						}
						
						return "https://global.api.pvp.net/api/lol/static-data/" . strToLower($region) . "/v1.2/" . $type . "?api_key=" . $apiKey;
					}
					
					
					
					
					
					// TODO: Remove hardcoded "na" string and replace with chosen region from dropdown
					$realmUrl = GetStaticInfoUrl("realm", "na", $apiKey);
					$realmInfo = GetApiResults($realmUrl);
	
					$versionUrl = GetStaticInfoUrl("versions", "na", $apiKey);
					$versionInfo = GetApiResults($versionUrl);
					
					$championUrl = GetStaticInfoUrl("champion", "na", $apiKey);
					$championInfo = GetApiResults($championUrl);
					
					$currentVersion = $versionInfo[0];
					$url = $realmInfo->{"cdn"};
					
					if(!HaveMostRecentRealmVersion($conn, $currentVersion))
					{
						UpdateVersionInfo($conn, $currentVersion, $url);
					}
					
					UpdateChampionInfo($conn, $championInfo);
	
					$urlForImages = $url . "/" . $versionInfo[0] . "/img/champion/";
					
					$sql = "SELECT * FROM champion_information";
					
					$query = $conn->prepare($sql);
					$query->execute();
					$championData = $query->fetchAll(PDO::FETCH_CLASS, "Champion");
					
					$stack = array();
					foreach($championData as $champion) 
					{
						array_push($stack, $champion);
					}
					
					function cmp($a, $b)
					{
					    return strcmp($a->getName(), $b->getName());
					}
					
					usort($stack, "cmp");
					
					$sql = "SELECT Id, Image FROM champion_image";
					
					$query = $conn->prepare($sql);
					$query->execute();
					
					$imageData = $query->fetchAll(PDO::FETCH_KEY_PAIR);
					
					foreach($stack as $champion) 
					{
						
						$id = $champion->getId();
						$name = $champion->getName();
						$key = $champion->getChampionKey();
						$title = $champion->getTitle();
						
						if(!array_key_exists($id, $imageData))
						{
							$imageUrl = $urlForImages . $key . ".png";
							$imageData[$id] = AddChampionImage($conn, $id, $imageUrl);
						}
						
						echo "<div class='champion-id' title=\"" . $name . ", " . $title . "\">
								<img width='60px' alt='" . $name . "' src=\"data:image/jpeg;base64," . base64_encode($imageData[$id]) . "\" /></div>";
					}
	 				?>
				</div>
			</div>
		</div>
	</div>