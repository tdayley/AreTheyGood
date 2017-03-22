<?php include('variables/private_variables.php'); ?>
<?php include('includes/sql_connection.php'); ?>
<?php include('includes/champion_image.php'); ?>
<?php include('includes/functions.php') ?>

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
									<input id="summoner-name" type="text" name="summonerName" placeholder="Summoner Name (e.g. Spacetoaster)" class="td-form-control" />
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
						foreach($championInfo["data"] as $champion)
						{
							$sql = "SELECT COUNT(1) FROM champion_information WHERE Id = :id";
							
							$query = $conn->prepare($sql);
							$query->bindValue(":id", $champion["id"]);
							$query->execute();
							$count = $query->fetchColumn();
							
							if($count == 0)
							{
								$sql = "INSERT INTO champion_information(Id, Name, Title, ChampionKey) VALUES (:id, :name, :title, :championKey)";
								
								$query = $conn->prepare($sql);
								$query->bindValue(":id", $champion["id"]);
								$query->bindValue(":name", $champion["name"]);
								$query->bindValue(":title", $champion["title"]);
								$query->bindValue(":championKey", $champion["key"]);
								$query->execute();
							}
						}
					}					
					
					// TODO: Remove hardcoded "na" string and replace with chosen region from dropdown
					$realmUrl = GetStaticInfoUrl("realm", "na", $apiKey);
					$realmInfo = GetApiResults($realmUrl);
	
					$versionUrl = GetStaticInfoUrl("versions", "na", $apiKey);
					$versionInfo = GetApiResults($versionUrl);
					
					$championUrl = GetStaticInfoUrl("champion", "na", $apiKey);
					$championInfo = GetApiResults($championUrl);
					
					$currentVersion = $versionInfo["json"][0];
					$url = $realmInfo["json"]["cdn"];
					
					if(!HaveMostRecentRealmVersion($conn, $currentVersion))
					{
						UpdateVersionInfo($conn, $currentVersion, $url);
					}
					
					UpdateChampionInfo($conn, $championInfo["json"]);
					
					// Get averages
					$sql = "SELECT ChampionId, ROUND(AVG(ChampionPoints), 0) as AveragePoints FROM champion_mastery GROUP BY ChampionId";
					
					$query = $conn->prepare($sql);
					$query->execute();
					$averageData = $query->fetchAll();
					
					$sql = "SELECT a.ChampionId, d.Name as ChampionName, a.ChampionPoints, c.Name as SummonerName, d.ChampionKey 
							FROM champion_mastery a 
							JOIN summoner_information c ON a.PlayerId = c.Id 
							JOIN champion_information d on a.ChampionId = d.Id
							WHERE a.ChampionPoints = (SELECT MAX(b.ChampionPoints) FROM champion_mastery b WHERE b.ChampionId = a.ChampionId) 
							ORDER BY d.Name";
					
					$query = $conn->prepare($sql);
					$query->execute();
					$championData = $query->fetchAll();
					
					echo "<div class='center'>
							<div class='top-summoner-container'>Top Summoners</div>";
					
					foreach($championData as $champion) 
					{
						$championId = $champion["ChampionId"];
						$championName = $champion["ChampionName"];
						$image = $url . "/" . $currentVersion . "/img/champion/" . $champion["ChampionKey"] . ".png";
						$championPoints = $champion["ChampionPoints"];
						$summonerName = $champion["SummonerName"];
						
						$averagePoints = 0;
						foreach($averageData as $averageChamp)
						{
							if (in_array($championId, $averageChamp))
							{
								$averagePoints = $averageChamp["AveragePoints"];
								break;
							}
						}
						
						echo "<div class='champion-id'>
								<div class='image-overlay image-simple'>
									<img class='overlay-img-main' src=\"" . $image . "\" alt='$championName'>
									<div class='image-overlay-box'>
										<div class='image-overlay-data'>
											<span class='image-white'>
												$championName
											</span>
										</div>
									</div>
								</div>
								<div class='top-summoner-name' title='$summonerName'>$summonerName</div>
								<div class='top-summoner-points'>" . number_format($championPoints) . "</div>
								<hr class='top-summoner-break'>
								<div class='top-summoner-points'><strong>Avg</strong> " . number_format($averagePoints) . "</div>
								</div>";
					}
					
					echo "</div>";
	 				?>
				</div>
			</div>
		</div>
	</div>