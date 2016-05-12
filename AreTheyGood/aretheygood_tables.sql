CREATE TABLE IF NOT EXISTS `champion_image` (
  `Id` int(11) NOT NULL,
  `Image` blob NOT NULL,
  KEY `Id` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `champion_information` (
  `Id` int(11) NOT NULL,
  `Name` varchar(128) NOT NULL,
  `Title` varchar(256) NOT NULL,
  `ChampionKey` varchar(128) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `champion_mastery` (
  `PlayerId` bigint(20) NOT NULL,
  `ChampionId` bigint(20) NOT NULL,
  `ChampionLevel` int(11) NOT NULL,
  `ChampionPoints` int(11) NOT NULL,
  `LastPlayTime` bigint(20) NOT NULL,
  `ChampionPointsSinceLastLevel` bigint(20) NOT NULL,
  `ChampionPointsUntilNextLevel` bigint(20) NOT NULL,
  `ChestGranted` tinyint(1) NOT NULL,
  `HighestGrade` varchar(16) NOT NULL,
  KEY `PlayerId` (`PlayerId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `realm_version` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Version` varchar(16) NOT NULL,
  `Url` varchar(256) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `Id` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `region_information` (
  `Id` int(11) NOT NULL,
  `Region` varchar(32) NOT NULL,
  `Acronym` varchar(8) NOT NULL,
  `PlatformId` varchar(8) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `summoner_information` (
  `Id` bigint(20) NOT NULL,
  `Name` varchar(256) NOT NULL,
  `Region` varchar(8) NOT NULL,
  `ProfileIconId` int(11) NOT NULL,
  `RevisionDate` bigint(20) NOT NULL,
  `SummonerLevel` int(11) NOT NULL,
  `MasteriesExist` tinyint(1) NOT NULL,
  `LastSearched` datetime NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



ALTER TABLE `champion_image`
  ADD CONSTRAINT `champion_id` FOREIGN KEY (`Id`) REFERENCES `champion_information` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `champion_mastery`
  ADD CONSTRAINT `player_id` FOREIGN KEY (`PlayerId`) REFERENCES `summoner_information` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;