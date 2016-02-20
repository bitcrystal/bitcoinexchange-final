SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
CREATE TABLE IF NOT EXISTS `addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `coin1` varchar(200) NOT NULL,
  `coin2` varchar(200) NOT NULL,
  `coin3` varchar(200) NOT NULL,
  `coin4` varchar(200) NOT NULL,
  `coin5` varchar(200) NOT NULL,
  `coin6` varchar(200) NOT NULL,
  `coin7` varchar(200) NOT NULL,
  `coin8` varchar(200) NOT NULL,
  `coin9` varchar(200) NOT NULL,
  `coin10` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;
ALTER TABLE `addresses` ADD `trade_id` varchar(200) NOT NULL;
ALTER TABLE `addresses` ADD `coin11` varchar(200) NOT NULL AFTER `coin10`;
ALTER TABLE `addresses` ADD `coin12` varchar(200) NOT NULL AFTER `coin11`;
ALTER TABLE `addresses` ADD `coin13` varchar(200) NOT NULL AFTER `coin12`;
ALTER TABLE `addresses` ADD `coin14` varchar(200) NOT NULL AFTER `coin13`;
ALTER TABLE `addresses` ADD `coin15` varchar(200) NOT NULL AFTER `coin14`;
ALTER TABLE `addresses` ADD `coin16` varchar(200) NOT NULL AFTER `coin15`;
ALTER TABLE `addresses` ADD `coin17` varchar(200) NOT NULL AFTER `coin16`;
ALTER TABLE `addresses` ADD `coin18` varchar(200) NOT NULL AFTER `coin17`;
ALTER TABLE `addresses` ADD `coin19` varchar(200) NOT NULL AFTER `coin18`;
ALTER TABLE `addresses` ADD `coin20` varchar(200) NOT NULL AFTER `coin19`;
ALTER TABLE `addresses` ADD `coin21` varchar(200) NOT NULL AFTER `coin20`;
ALTER TABLE `addresses` ADD `coin22` varchar(200) NOT NULL AFTER `coin21`;
ALTER TABLE `addresses` ADD `coin23` varchar(200) NOT NULL AFTER `coin22`;
ALTER TABLE `addresses` ADD `coin24` varchar(200) NOT NULL AFTER `coin23`;
ALTER TABLE `addresses` ADD `coin25` varchar(200) NOT NULL AFTER `coin24`;
ALTER TABLE `addresses` ADD `coin26` varchar(200) NOT NULL AFTER `coin25`;
ALTER TABLE `addresses` ADD `coin27` varchar(200) NOT NULL AFTER `coin26`;
ALTER TABLE `addresses` ADD `coin28` varchar(200) NOT NULL AFTER `coin27`;
ALTER TABLE `addresses` ADD `coin29` varchar(200) NOT NULL AFTER `coin28`;
ALTER TABLE `addresses` ADD `coin30` varchar(200) NOT NULL AFTER `coin29`;
ALTER TABLE `addresses` ADD `coin31` varchar(200) NOT NULL AFTER `coin30`;
ALTER TABLE `addresses` ADD `coin32` varchar(200) NOT NULL AFTER `coin31`;
ALTER TABLE `addresses` ADD `coin33` varchar(200) NOT NULL AFTER `coin32`;
ALTER TABLE `addresses` ADD `coin34` varchar(200) NOT NULL AFTER `coin33`;
ALTER TABLE `addresses` ADD `coin35` varchar(200) NOT NULL AFTER `coin34`;
ALTER TABLE `addresses` ADD `coin36` varchar(200) NOT NULL AFTER `coin35`;
ALTER TABLE `addresses` ADD `coin37` varchar(200) NOT NULL AFTER `coin36`;
ALTER TABLE `addresses` ADD `coin38` varchar(200) NOT NULL AFTER `coin37`;
ALTER TABLE `addresses` ADD `coin39` varchar(200) NOT NULL AFTER `coin38`;
ALTER TABLE `addresses` ADD `coin40` varchar(200) NOT NULL AFTER `coin39`;
ALTER TABLE `addresses` ADD `coin41` varchar(200) NOT NULL AFTER `coin40`;
ALTER TABLE `addresses` ADD `coin42` varchar(200) NOT NULL AFTER `coin41`;
ALTER TABLE `addresses` ADD `coin43` varchar(200) NOT NULL AFTER `coin42`;
ALTER TABLE `addresses` ADD `coin44` varchar(200) NOT NULL AFTER `coin43`;
ALTER TABLE `addresses` ADD `coin45` varchar(200) NOT NULL AFTER `coin44`;
ALTER TABLE `addresses` ADD `coin46` varchar(200) NOT NULL AFTER `coin45`;
ALTER TABLE `addresses` ADD `coin47` varchar(200) NOT NULL AFTER `coin46`;
ALTER TABLE `addresses` ADD `coin48` varchar(200) NOT NULL AFTER `coin47`;
ALTER TABLE `addresses` ADD `coin49` varchar(200) NOT NULL AFTER `coin48`;
ALTER TABLE `addresses` ADD `coin50` varchar(200) NOT NULL AFTER `coin49`;
ALTER TABLE `addresses` ADD `coin51` varchar(200) NOT NULL AFTER `coin50`;
ALTER TABLE `addresses` ADD `coin52` varchar(200) NOT NULL AFTER `coin51`;
ALTER TABLE `addresses` ADD `coin53` varchar(200) NOT NULL AFTER `coin52`;
ALTER TABLE `addresses` ADD `coin54` varchar(200) NOT NULL AFTER `coin53`;
ALTER TABLE `addresses` ADD `coin55` varchar(200) NOT NULL AFTER `coin54`;
ALTER TABLE `addresses` ADD `coin56` varchar(200) NOT NULL AFTER `coin55`;
ALTER TABLE `addresses` ADD `coin57` varchar(200) NOT NULL AFTER `coin56`;
ALTER TABLE `addresses` ADD `coin58` varchar(200) NOT NULL AFTER `coin57`;
ALTER TABLE `addresses` ADD `coin59` varchar(200) NOT NULL AFTER `coin58`;
ALTER TABLE `addresses` ADD `coin60` varchar(200) NOT NULL AFTER `coin59`;
ALTER TABLE `addresses` ADD `coin61` varchar(200) NOT NULL AFTER `coin60`;
ALTER TABLE `addresses` ADD `coin62` varchar(200) NOT NULL AFTER `coin61`;
ALTER TABLE `addresses` ADD `coin63` varchar(200) NOT NULL AFTER `coin62`;
ALTER TABLE `addresses` ADD `coin64` varchar(200) NOT NULL AFTER `coin63`;
ALTER TABLE `addresses` ADD `coin65` varchar(200) NOT NULL AFTER `coin64`;
ALTER TABLE `addresses` ADD `coin66` varchar(200) NOT NULL AFTER `coin65`;
ALTER TABLE `addresses` ADD `coin67` varchar(200) NOT NULL AFTER `coin66`;
ALTER TABLE `addresses` ADD `coin68` varchar(200) NOT NULL AFTER `coin67`;
ALTER TABLE `addresses` ADD `coin69` varchar(200) NOT NULL AFTER `coin68`;
ALTER TABLE `addresses` ADD `coin70` varchar(200) NOT NULL AFTER `coin69`;
ALTER TABLE `addresses` ADD `coin71` varchar(200) NOT NULL AFTER `coin70`;
ALTER TABLE `addresses` ADD `coin72` varchar(200) NOT NULL AFTER `coin71`;
ALTER TABLE `addresses` ADD `coin73` varchar(200) NOT NULL AFTER `coin72`;
ALTER TABLE `addresses` ADD `coin74` varchar(200) NOT NULL AFTER `coin73`;
ALTER TABLE `addresses` ADD `coin75` varchar(200) NOT NULL AFTER `coin74`;
ALTER TABLE `addresses` ADD `coin76` varchar(200) NOT NULL AFTER `coin75`;
ALTER TABLE `addresses` ADD `coin77` varchar(200) NOT NULL AFTER `coin76`;
ALTER TABLE `addresses` ADD `coin78` varchar(200) NOT NULL AFTER `coin77`;
ALTER TABLE `addresses` ADD `coin79` varchar(200) NOT NULL AFTER `coin78`;
ALTER TABLE `addresses` ADD `coin80` varchar(200) NOT NULL AFTER `coin79`;
ALTER TABLE `addresses` ADD `coin81` varchar(200) NOT NULL AFTER `coin80`;
ALTER TABLE `addresses` ADD `coin82` varchar(200) NOT NULL AFTER `coin81`;
ALTER TABLE `addresses` ADD `coin83` varchar(200) NOT NULL AFTER `coin82`;
ALTER TABLE `addresses` ADD `coin84` varchar(200) NOT NULL AFTER `coin83`;
ALTER TABLE `addresses` ADD `coin85` varchar(200) NOT NULL AFTER `coin84`;
ALTER TABLE `addresses` ADD `coin86` varchar(200) NOT NULL AFTER `coin85`;
ALTER TABLE `addresses` ADD `coin87` varchar(200) NOT NULL AFTER `coin86`;
ALTER TABLE `addresses` ADD `coin88` varchar(200) NOT NULL AFTER `coin87`;
ALTER TABLE `addresses` ADD `coin89` varchar(200) NOT NULL AFTER `coin88`;
ALTER TABLE `addresses` ADD `coin90` varchar(200) NOT NULL AFTER `coin89`;
ALTER TABLE `addresses` ADD `coin91` varchar(200) NOT NULL AFTER `coin90`;
ALTER TABLE `addresses` ADD `coin92` varchar(200) NOT NULL AFTER `coin91`;
ALTER TABLE `addresses` ADD `coin93` varchar(200) NOT NULL AFTER `coin92`;
ALTER TABLE `addresses` ADD `coin94` varchar(200) NOT NULL AFTER `coin93`;
ALTER TABLE `addresses` ADD `coin95` varchar(200) NOT NULL AFTER `coin94`;
ALTER TABLE `addresses` ADD `coin96` varchar(200) NOT NULL AFTER `coin95`;
ALTER TABLE `addresses` ADD `coin97` varchar(200) NOT NULL AFTER `coin96`;
ALTER TABLE `addresses` ADD `coin98` varchar(200) NOT NULL AFTER `coin97`;
ALTER TABLE `addresses` ADD `coin99` varchar(200) NOT NULL AFTER `coin98`;
ALTER TABLE `addresses` ADD `coin100` varchar(200) NOT NULL AFTER `coin99`;

CREATE TABLE IF NOT EXISTS `apis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `apikey` text NOT NULL,
  `privkey` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
ALTER TABLE `apis` ADD `trade_id` varchar(200) NOT NULL;

CREATE TABLE IF NOT EXISTS `balances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `coin1` varchar(200) NOT NULL,
  `coin2` varchar(200) NOT NULL,
  `coin3` varchar(200) NOT NULL,
  `coin4` varchar(200) NOT NULL,
  `coin5` varchar(200) NOT NULL,
  `coin6` varchar(200) NOT NULL,
  `coin7` varchar(200) NOT NULL,
  `coin8` varchar(200) NOT NULL,
  `coin9` varchar(200) NOT NULL,
  `coin10` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;
ALTER TABLE `balances` ADD `trade_id` varchar(200) NOT NULL;
ALTER TABLE `balances` ADD `coin11` varchar(200) NOT NULL AFTER `coin10`;
ALTER TABLE `balances` ADD `coin12` varchar(200) NOT NULL AFTER `coin11`;
ALTER TABLE `balances` ADD `coin13` varchar(200) NOT NULL AFTER `coin12`;
ALTER TABLE `balances` ADD `coin14` varchar(200) NOT NULL AFTER `coin13`;
ALTER TABLE `balances` ADD `coin15` varchar(200) NOT NULL AFTER `coin14`;
ALTER TABLE `balances` ADD `coin16` varchar(200) NOT NULL AFTER `coin15`;
ALTER TABLE `balances` ADD `coin17` varchar(200) NOT NULL AFTER `coin16`;
ALTER TABLE `balances` ADD `coin18` varchar(200) NOT NULL AFTER `coin17`;
ALTER TABLE `balances` ADD `coin19` varchar(200) NOT NULL AFTER `coin18`;
ALTER TABLE `balances` ADD `coin20` varchar(200) NOT NULL AFTER `coin19`;
ALTER TABLE `balances` ADD `coin21` varchar(200) NOT NULL AFTER `coin20`;
ALTER TABLE `balances` ADD `coin22` varchar(200) NOT NULL AFTER `coin21`;
ALTER TABLE `balances` ADD `coin23` varchar(200) NOT NULL AFTER `coin22`;
ALTER TABLE `balances` ADD `coin24` varchar(200) NOT NULL AFTER `coin23`;
ALTER TABLE `balances` ADD `coin25` varchar(200) NOT NULL AFTER `coin24`;
ALTER TABLE `balances` ADD `coin26` varchar(200) NOT NULL AFTER `coin25`;
ALTER TABLE `balances` ADD `coin27` varchar(200) NOT NULL AFTER `coin26`;
ALTER TABLE `balances` ADD `coin28` varchar(200) NOT NULL AFTER `coin27`;
ALTER TABLE `balances` ADD `coin29` varchar(200) NOT NULL AFTER `coin28`;
ALTER TABLE `balances` ADD `coin30` varchar(200) NOT NULL AFTER `coin29`;
ALTER TABLE `balances` ADD `coin31` varchar(200) NOT NULL AFTER `coin30`;
ALTER TABLE `balances` ADD `coin32` varchar(200) NOT NULL AFTER `coin31`;
ALTER TABLE `balances` ADD `coin33` varchar(200) NOT NULL AFTER `coin32`;
ALTER TABLE `balances` ADD `coin34` varchar(200) NOT NULL AFTER `coin33`;
ALTER TABLE `balances` ADD `coin35` varchar(200) NOT NULL AFTER `coin34`;
ALTER TABLE `balances` ADD `coin36` varchar(200) NOT NULL AFTER `coin35`;
ALTER TABLE `balances` ADD `coin37` varchar(200) NOT NULL AFTER `coin36`;
ALTER TABLE `balances` ADD `coin38` varchar(200) NOT NULL AFTER `coin37`;
ALTER TABLE `balances` ADD `coin39` varchar(200) NOT NULL AFTER `coin38`;
ALTER TABLE `balances` ADD `coin40` varchar(200) NOT NULL AFTER `coin39`;
ALTER TABLE `balances` ADD `coin41` varchar(200) NOT NULL AFTER `coin40`;
ALTER TABLE `balances` ADD `coin42` varchar(200) NOT NULL AFTER `coin41`;
ALTER TABLE `balances` ADD `coin43` varchar(200) NOT NULL AFTER `coin42`;
ALTER TABLE `balances` ADD `coin44` varchar(200) NOT NULL AFTER `coin43`;
ALTER TABLE `balances` ADD `coin45` varchar(200) NOT NULL AFTER `coin44`;
ALTER TABLE `balances` ADD `coin46` varchar(200) NOT NULL AFTER `coin45`;
ALTER TABLE `balances` ADD `coin47` varchar(200) NOT NULL AFTER `coin46`;
ALTER TABLE `balances` ADD `coin48` varchar(200) NOT NULL AFTER `coin47`;
ALTER TABLE `balances` ADD `coin49` varchar(200) NOT NULL AFTER `coin48`;
ALTER TABLE `balances` ADD `coin50` varchar(200) NOT NULL AFTER `coin49`;
ALTER TABLE `balances` ADD `coin51` varchar(200) NOT NULL AFTER `coin50`;
ALTER TABLE `balances` ADD `coin52` varchar(200) NOT NULL AFTER `coin51`;
ALTER TABLE `balances` ADD `coin53` varchar(200) NOT NULL AFTER `coin52`;
ALTER TABLE `balances` ADD `coin54` varchar(200) NOT NULL AFTER `coin53`;
ALTER TABLE `balances` ADD `coin55` varchar(200) NOT NULL AFTER `coin54`;
ALTER TABLE `balances` ADD `coin56` varchar(200) NOT NULL AFTER `coin55`;
ALTER TABLE `balances` ADD `coin57` varchar(200) NOT NULL AFTER `coin56`;
ALTER TABLE `balances` ADD `coin58` varchar(200) NOT NULL AFTER `coin57`;
ALTER TABLE `balances` ADD `coin59` varchar(200) NOT NULL AFTER `coin58`;
ALTER TABLE `balances` ADD `coin60` varchar(200) NOT NULL AFTER `coin59`;
ALTER TABLE `balances` ADD `coin61` varchar(200) NOT NULL AFTER `coin60`;
ALTER TABLE `balances` ADD `coin62` varchar(200) NOT NULL AFTER `coin61`;
ALTER TABLE `balances` ADD `coin63` varchar(200) NOT NULL AFTER `coin62`;
ALTER TABLE `balances` ADD `coin64` varchar(200) NOT NULL AFTER `coin63`;
ALTER TABLE `balances` ADD `coin65` varchar(200) NOT NULL AFTER `coin64`;
ALTER TABLE `balances` ADD `coin66` varchar(200) NOT NULL AFTER `coin65`;
ALTER TABLE `balances` ADD `coin67` varchar(200) NOT NULL AFTER `coin66`;
ALTER TABLE `balances` ADD `coin68` varchar(200) NOT NULL AFTER `coin67`;
ALTER TABLE `balances` ADD `coin69` varchar(200) NOT NULL AFTER `coin68`;
ALTER TABLE `balances` ADD `coin70` varchar(200) NOT NULL AFTER `coin69`;
ALTER TABLE `balances` ADD `coin71` varchar(200) NOT NULL AFTER `coin70`;
ALTER TABLE `balances` ADD `coin72` varchar(200) NOT NULL AFTER `coin71`;
ALTER TABLE `balances` ADD `coin73` varchar(200) NOT NULL AFTER `coin72`;
ALTER TABLE `balances` ADD `coin74` varchar(200) NOT NULL AFTER `coin73`;
ALTER TABLE `balances` ADD `coin75` varchar(200) NOT NULL AFTER `coin74`;
ALTER TABLE `balances` ADD `coin76` varchar(200) NOT NULL AFTER `coin75`;
ALTER TABLE `balances` ADD `coin77` varchar(200) NOT NULL AFTER `coin76`;
ALTER TABLE `balances` ADD `coin78` varchar(200) NOT NULL AFTER `coin77`;
ALTER TABLE `balances` ADD `coin79` varchar(200) NOT NULL AFTER `coin78`;
ALTER TABLE `balances` ADD `coin80` varchar(200) NOT NULL AFTER `coin79`;
ALTER TABLE `balances` ADD `coin81` varchar(200) NOT NULL AFTER `coin80`;
ALTER TABLE `balances` ADD `coin82` varchar(200) NOT NULL AFTER `coin81`;
ALTER TABLE `balances` ADD `coin83` varchar(200) NOT NULL AFTER `coin82`;
ALTER TABLE `balances` ADD `coin84` varchar(200) NOT NULL AFTER `coin83`;
ALTER TABLE `balances` ADD `coin85` varchar(200) NOT NULL AFTER `coin84`;
ALTER TABLE `balances` ADD `coin86` varchar(200) NOT NULL AFTER `coin85`;
ALTER TABLE `balances` ADD `coin87` varchar(200) NOT NULL AFTER `coin86`;
ALTER TABLE `balances` ADD `coin88` varchar(200) NOT NULL AFTER `coin87`;
ALTER TABLE `balances` ADD `coin89` varchar(200) NOT NULL AFTER `coin88`;
ALTER TABLE `balances` ADD `coin90` varchar(200) NOT NULL AFTER `coin89`;
ALTER TABLE `balances` ADD `coin91` varchar(200) NOT NULL AFTER `coin90`;
ALTER TABLE `balances` ADD `coin92` varchar(200) NOT NULL AFTER `coin91`;
ALTER TABLE `balances` ADD `coin93` varchar(200) NOT NULL AFTER `coin92`;
ALTER TABLE `balances` ADD `coin94` varchar(200) NOT NULL AFTER `coin93`;
ALTER TABLE `balances` ADD `coin95` varchar(200) NOT NULL AFTER `coin94`;
ALTER TABLE `balances` ADD `coin96` varchar(200) NOT NULL AFTER `coin95`;
ALTER TABLE `balances` ADD `coin97` varchar(200) NOT NULL AFTER `coin96`;
ALTER TABLE `balances` ADD `coin98` varchar(200) NOT NULL AFTER `coin97`;
ALTER TABLE `balances` ADD `coin99` varchar(200) NOT NULL AFTER `coin98`;
ALTER TABLE `balances` ADD `coin100` varchar(200) NOT NULL AFTER `coin99`;

CREATE TABLE IF NOT EXISTS `buy_orderbook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(300) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `username` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  `want` varchar(100) NOT NULL,
  `initial_amount` varchar(200) NOT NULL,
  `amount` varchar(200) NOT NULL,
  `rate` varchar(200) NOT NULL,
  `processed` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;
ALTER TABLE `buy_orderbook` ADD `trade_id` varchar(200) NOT NULL;
ALTER TABLE `buy_orderbook` ADD `trade_with` varchar(200) NOT NULL;

CREATE TABLE IF NOT EXISTS `ordersfilled` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(100) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `trader` varchar(100) NOT NULL,
  `oid` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  `want` varchar(100) NOT NULL,
  `amount` varchar(200) NOT NULL,
  `rate` varchar(200) NOT NULL,
  `total` varchar(200) NOT NULL,
  `processed` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
ALTER TABLE `ordersfilled` ADD `trade_id` varchar(200) NOT NULL;
ALTER TABLE `ordersfilled` ADD `trade_with` varchar(200) NOT NULL;

CREATE TABLE IF NOT EXISTS `sell_orderbook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(300) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `username` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  `want` varchar(100) NOT NULL,
  `initial_amount` varchar(200) NOT NULL,
  `amount` varchar(200) NOT NULL,
  `rate` varchar(200) NOT NULL,
  `processed` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;
ALTER TABLE `sell_orderbook` ADD `trade_id` varchar(200) NOT NULL;
ALTER TABLE `sell_orderbook` ADD `trade_with` varchar(200) NOT NULL;

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(300) NOT NULL,
  `username` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  `coin` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `txid` text NOT NULL,
  `amount` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;
ALTER TABLE `transactions` ADD `trade_id` varchar(200) NOT NULL;

CREATE TABLE IF NOT EXISTS `who_is_online` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `botname` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `user` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `bot` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `guest` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `countrycode` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip` (`ip`),
  KEY `countrycode` (`countrycode`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;
ALTER TABLE `who_is_online` ADD `trade_id` varchar(200) NOT NULL;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(300) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `username` varchar(65) NOT NULL,
  `password` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;
ALTER TABLE `users` ADD `email` varchar(300) NOT NULL;
ALTER TABLE `users` ADD `trade_id` varchar(200) NOT NULL;
