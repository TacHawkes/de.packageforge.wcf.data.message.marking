ALTER TABLE wcf1_user ADD defaultMessageMarkingID int(10) NOT NULL default 0;
ALTER TABLE wcf1_group ADD messageMarkingID int(10) NOT NULL default 0;
ALTER TABLE wcf1_group ADD messageMarkingPriority int(10) NOT NULL default 0;

DROP TABLE IF EXISTS wcf1_message_marking;
CREATE TABLE wcf1_message_marking (
  markingID int(10) NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL DEFAULT '',
  css text NOT NULL,
  disabled tinyint(1) NOT NULL DEFAULT '0',  
  PRIMARY KEY (markingID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO wcf1_message_marking (markingID, title, css, disabled) VALUES
(1, 'wcf.message.marking.example', '.messageContent {\n   border-left: solid orange 10px;\n   background-color: orange;\n}', 0);

DROP TABLE IF EXISTS wcf1_message_marking_to_group;
CREATE TABLE wcf1_message_marking_to_group (
  markingID int(10) NOT NULL,
  groupID int(10) NOT NULL,
  PRIMARY KEY (markingID,groupID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO wcf1_message_marking_to_group (markingID, groupID) VALUES
(1, 4);