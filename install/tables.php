<?

$table['admin'] = "CREATE TABLE `admin` (
  `username` varchar(255) character set utf8 NOT NULL default '',
  `password` varchar(255) character set utf8 NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`username`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Ratuus - Virtual Admins';";

$table['alias'] = "CREATE TABLE `alias` (
  `address` varchar(255) NOT NULL default '',
  `goto` text NOT NULL,
  `domain` varchar(255) NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`address`),
  KEY `address` (`address`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Ratuus - Virtual Aliases';";

$table['config'] = "CREATE TABLE `config` (
  `config_opt` varchar(255) NOT NULL default '',
  `config_value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`config_opt`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Ratuus - Configuration';";

$table['domain'] = "CREATE TABLE `domain` (
  `domain` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `aliases` int(10) NOT NULL default '0',
  `mailboxes` int(10) NOT NULL default '0',
  `maxquota` int(10) NOT NULL default '0',
  `transport` varchar(255) default NULL,
  `backupmx` tinyint(1) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`domain`),
  KEY `domain` (`domain`) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Ratuus - Virtual Domains';";

$table['domain_admins'] = "CREATE TABLE `domain_admins` (
  `username` varchar(255) NOT NULL default '',
  `domain` varchar(255) NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `active` tinyint(1) NOT NULL default '1',
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Ratuus - Domain Admins';";

$table['log'] = "CREATE TABLE `log` (
  `timestamp` datetime NOT NULL default '0000-00-00 00:00:00',
  `username` varchar(255) NOT NULL default '',
  `domain` varchar(255) NOT NULL default '',
  `action` varchar(255) NOT NULL default '',
  `data` varchar(255) NOT NULL default '',
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Ratuus - Log';";

$table['mailbox'] = "CREATE TABLE `mailbox` (
  `username` varchar(255) NOT NULL default '',
  `password` varchar(255) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `maildir` varchar(255) NOT NULL default '',
  `quota` int(10) NOT NULL default '0',
  `domain` varchar(255) NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`username`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Ratuus - Virtual Mailboxes';";

$create['config'] = "INSERT INTO `config` VALUES ('aliases','20'),('mailboxes','10'),('transport','virtual:'),('user\_quota','10000'),('domain\_quota','20000000'),('limit','5');";

?>
