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
  UNIQUE (`username`),
  UNIQUE (`email`)
); 


CREATE TABLE `list` (
  `lid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT -1,
  `lname` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`lid`),
  CONSTRAINT `list_fk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) 
  ON DELETE SET NULL ON UPDATE CASCADE
); 



CREATE TABLE `items` (
  `iid` int(11) NOT NULL AUTO_INCREMENT,
  `lid` int(11) DEFAULT NULL, -- cannot be not null
  `iname` varchar(100) DEFAULT NULL,
  `add` datetime DEFAULT NULL,
  `goal` datetime DEFAULT NULL,
  `completed` datetime DEFAULT NULL,
  `isdone` tinyint(1) NOT NULL,
  `color` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`iid`),
  CONSTRAINT `items_fk_1` FOREIGN KEY (`lid`) REFERENCES `list` (`lid`)
  ON DELETE SET NULL ON UPDATE CASCADE
);


CREATE TABLE `pref` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT -1,
  `pname` varchar(100) DEFAULT NULL,
  `bgcolor` varchar(100) DEFAULT NULL,
  `fontcolor` varchar(100) DEFAULT NULL,
  `bordercolor` varchar(100) DEFAULT NULL,
  `fontsize` int(3) DEFAULT 16,
  PRIMARY KEY (`pid`),
  CONSTRAINT `pref_fk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`)
  ON DELETE SET NULL ON UPDATE CASCADE
);




-- source triggers.sql;


-- source workingsqls/create_client.sql;
-- source workingsqls/create_log.sql;
-- source workingsqls/create_premiumclient.sql;
-- source workingsqls/create_premiumbuddieswithothers.sql;




-- test
-- insert into client values ("default", "asdfjk");
-- insert into log (startdatetime, isoverallstart, enddatetime, isoverallend) values ("1111-11-11 11:11:11", 1, "1111-11-11 11:11:11", 1);
-- delete from client where username = "default";
