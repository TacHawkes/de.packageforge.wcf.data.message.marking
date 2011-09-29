ALTER TABLE wcf1_user ADD markTeamMessageGroupID int(10) NOT NULL default 0;
ALTER TABLE wcf1_group ADD markAsTeam tinyint(1) NOT NULL default 0;
ALTER TABLE wcf1_group ADD markAsTeamCSS mediumtext;