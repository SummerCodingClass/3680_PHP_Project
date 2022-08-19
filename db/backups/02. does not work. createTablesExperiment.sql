DROP TABLE IF EXISTS `items`;
DROP TABLE IF EXISTS `list`;
DROP TABLE IF EXISTS `pref`;
DROP TABLE IF EXISTS `user`;


CREATE TABLE `user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; 


 CREATE TABLE `list` (
  `lid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT -1,
  `lname` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`lid`),
  KEY `list_fk_1` (`uid`),
  CONSTRAINT `list_fk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



 CREATE TABLE `pref` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT -1,
  `pname` varchar(100) DEFAULT NULL,
  `bgcolor` varchar(100) DEFAULT NULL,
  `fontcolor` varchar(100) DEFAULT NULL,
  `bordercolor` varchar(100) DEFAULT NULL,
  `fontsize` int(3) DEFAULT 16,
  PRIMARY KEY (`pid`),
  KEY `pref_fk_1` (`uid`),
  CONSTRAINT `pref_fk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



 CREATE TABLE `items` (
  `iid` int(11) NOT NULL AUTO_INCREMENT,
  `lid` int(11) NOT NULL,
  `iname` varchar(100) DEFAULT NULL,
  `add` datetime DEFAULT NULL,
  `goal` datetime DEFAULT NULL,
  `completed` datetime DEFAULT NULL,
  `isdone` tinyint(1) NOT NULL,
  `color` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`iid`),
  KEY `items_fk_1` (`lid`),
  CONSTRAINT `items_fk_1` FOREIGN KEY (`lid`) REFERENCES `list` (`lid`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `items` (
  `iid` int(11) NOT NULL AUTO_INCREMENT,
  `lid` int(11) NOT NULL, -- this was causing problem. needed to be default null instead... somehow
  `iname` varchar(100) DEFAULT NULL,
  `add` datetime DEFAULT NULL,
  `goal` datetime DEFAULT NULL,
  `completed` datetime DEFAULT NULL,
  `isdone` tinyint(1) NOT NULL,
  `color` varchar(100) DEFAULT NULL,
  -- PRIMARY KEY (`lid`, `iid`),
  PRIMARY KEY (`iid`),
  CONSTRAINT `items_fk_1` FOREIGN KEY (`lid`) REFERENCES `list` (`lid`)
  ON DELETE SET NULL ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
