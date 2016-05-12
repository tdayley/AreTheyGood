<?php include('includes/champion_image.php')?>

<?php
function CreateMasteryView($conn, $championMastery) {
	$view = "<div class='center'>";
	
	$template = "<div class='mastery-container'>
		  <div class='inline-block valign-middle'>
		    <div>
		      <img src='data:image/jpeg;base64,@image' />
		    </div>
		  </div>
		
		  <div class='inline-block valign-middle padding-left-20'>
		    <div style='margin: 0;' class='c100 p@fillPercent' @pointsToLevel>
		      <span class='@levelColor'>Level @championLevel</span>
		      <div class='slice'>
		        <div class='bar @levelBorderColor'></div>
		        <div class='fill @levelBorderColor'></div>
		      </div>
		    </div>
		  </div>
		
		  <div class='inline-block valign-middle padding-left-30'>
		    <div class='center margin-bottom-25'>
		      <h4 style='margin: 0;'>Points</h4>
			  <span>@championPoints</span>
		    </div>
		    <div class='center'>
		      <h4 style='margin: 0;'>Last Played</h4>
		      <span>@lastPlayTime</span>
		    </div>
		  </div>
		
		</div>";
	
	foreach ( $championMastery as $mastery ) {
		$html = $template;
		
		$epoch = substr ( $mastery->getLastPlayTime (), 0, - 3 );
		$dt = new DateTime ( "@$epoch" );
		
		$pointsSinceLevel = $mastery->getChampionPointsSinceLastLevel ();
		$pointsNeededToLevel = $pointsSinceLevel + $mastery->getChampionPointsUntilNextLevel ();
		$fillPercent = number_format ( ($pointsSinceLevel / $pointsNeededToLevel) * 100 );
		
		$championLevel = $mastery->getChampionLevel ();
		
		$html = str_replace ( "@image", GetChampionImage ( $conn, $mastery->getChampionId () ), $html );
		// $html = str_replace("@championId", $mastery->getChampionId(), $html);
		$html = str_replace ( "@championLevel", $mastery->getChampionLevel (), $html );
		$html = str_replace ( "@championPoints", number_format ( $mastery->getChampionPoints () ), $html );
		if ($championLevel != 5) {
			$html = str_replace ( "@pointsToLevel", "title='$pointsSinceLevel/$pointsNeededToLevel'", $html );
			$html = str_replace ( "@levelColor", "", $html );
			$html = str_replace ( "@levelBorderColor", "", $html );
		} else {
			$html = str_replace ( "@levelColor", "gold-font", $html );
			$html = str_replace ( "@levelBorderColor", "gold-border", $html );
		}
		// $html = str_replace("@chestGranted", $mastery->getChestGranted(), $html);
		// $html = str_replace("@highestGrade", $mastery->getHighestGrade(), $html);
		$html = str_replace ( "@lastPlayTime", $dt->format ( 'j M Y g:i A' ), $html );
		// $html = str_replace("@playerId", $mastery->getPlayerId(), $html);
		$html = str_replace ( "@fillPercent", $fillPercent, $html );
		
		$view .= $html;
	}
	
	return $view .= "</div>";
}
?>