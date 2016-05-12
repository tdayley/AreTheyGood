<?php
class ChampionMastery {
	private $ChampionId;
	private $ChampionLevel;
	private $ChampionPoints;
	private $ChampionPointsSinceLastLevel;
	private $ChampionPointsUntilNextLevel;
	private $ChestGranted;
	private $HighestGrade;
	private $LastPlayTime;
	private $PlayerId;
	public function __construct($championId, $level, $points, $pointsSinceLastLevel, $pointsUntilNextLevel, $chestGranted, $highestGrade, $lastPlayTime, $playerId) {
		$this->ChampionId = $championId;
		$this->ChampionLevel = $level;
		$this->ChampionPoints = $points;
		$this->ChampionPointsSinceLastLevel = $pointsSinceLastLevel;
		$this->ChampionPointsUntilNextLevel = $pointsUntilNextLevel;
		$this->ChestGranted = $chestGranted;
		$this->HighestGrade = $highestGrade;
		$this->LastPlayTime = $lastPlayTime;
		$this->PlayerId = $playerId;
	}
	public function getChampionId() {
		return $this->ChampionId;
	}
	public function getChampionLevel() {
		return $this->ChampionLevel;
	}
	public function getChampionPoints() {
		return $this->ChampionPoints;
	}
	public function getChampionPointsSinceLastLevel() {
		return $this->ChampionPointsSinceLastLevel;
	}
	public function getChampionPointsUntilNextLevel() {
		return $this->ChampionPointsUntilNextLevel;
	}
	public function getChestGranted() {
		return $this->ChestGranted;
	}
	public function getHighestGrade() {
		return $this->HighestGrade;
	}
	public function getLastPlayTime() {
		return $this->LastPlayTime;
	}
	public function getPlayerId() {
		return $this->PlayerId;
	}
}
?>