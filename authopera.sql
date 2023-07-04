-- Adminer 4.6.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `group`;
CREATE TABLE `group` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name_group` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `rules` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `group` (`id`, `name_group`, `description`, `rules`) VALUES
(1,	'3df',	NULL,	'a:3:{i:1;s:5:\"block\";i:2;s:5:\"block\";i:4;s:5:\"block\";}'),
(2,	'Account Operators',	'Netbios Domain Users to manipulate users accounts',	'a:3:{i:1;s:5:\"block\";i:2;s:5:\"block\";i:4;s:5:\"block\";}'),
(3,	'Administrators',	NULL,	'a:3:{i:1;s:5:\"block\";i:2;s:5:\"block\";i:4;s:5:\"block\";}'),
(4,	'Backup Operators',	'Netbios Domain Members can bypass file security to back up files',	NULL),
(5,	'buh',	NULL,	NULL),
(6,	'cam-server',	NULL,	NULL),
(7,	'direction',	NULL,	NULL),
(8,	'Domain Admins',	'Netbios Domain Administrators',	NULL),
(9,	'Domain Computers',	'Netbios Domain Computers accounts',	NULL),
(10,	'Domain Guests',	'Netbios Domain Guests Users',	NULL),
(11,	'Domain Users',	'Netbios Domain Users',	NULL),
(12,	'hr',	NULL,	NULL),
(13,	'it-dept',	NULL,	NULL),
(14,	'kassa',	NULL,	NULL),
(15,	'KLAdmins',	'Администраторы Kaspersky Security Center',	NULL),
(16,	'KLOperators',	'Операторы Kaspersky Security Center',	NULL),
(17,	'manuals',	NULL,	NULL),
(18,	'media',	NULL,	NULL),
(19,	'opt',	NULL,	NULL),
(20,	'pbxadmin',	NULL,	NULL),
(21,	'Print Operators',	NULL,	NULL),
(22,	'qa',	NULL,	NULL),
(23,	'recorder',	NULL,	NULL),
(24,	'Replicators',	'Netbios Domain Supports file replication in a sambaDomainName',	NULL),
(25,	'service',	NULL,	NULL),
(26,	'tr',	NULL,	NULL),
(27,	'wifi',	NULL,	NULL),
(28,	'yurist',	NULL,	NULL),
(29,	'zakupki',	NULL,	NULL);

DROP TABLE IF EXISTS `page`;
CREATE TABLE `page` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `parent` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `sort` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `cat` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `name_button` varchar(255) NOT NULL DEFAULT 'NULL',
  `src_page` varchar(255) NOT NULL DEFAULT 'NULL',
  `title` varchar(255) NOT NULL DEFAULT 'NULL',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `page` (`id`, `parent`, `sort`, `cat`, `name_button`, `src_page`, `title`) VALUES
(1,	3,	1,	0,	'Главная страница',	'/',	'Управление IP-АТС'),
(2,	3,	3,	0,	'авторизация',	'/login',	'Авторизация'),
(3,	3,	99,	0,	'IT',	'/it',	'Админка IT отдела'),
(4,	0,	4,	0,	'Пользователю',	'Cat',	'Пользователю'),
(5,	0,	5,	0,	'Call-центр',	'Cat',	'Call-центр'),
(6,	0,	6,	0,	'Дополнительно',	'Cat',	'Дополнительно'),
(7,	0,	7,	0,	'Управление',	'Cat',	'Управление'),
(8,	4,	1,	1,	'ТЕЛЕФОННЫЙ СПРАВОЧНИК КОМПАНИИ',	'/phones',	'ТЕЛЕФОННЫЙ СПРАВОЧНИК КОМПАНИИ'),
(9,	4,	2,	1,	'ARI - интерфейс пользователя АТС (нужен пароль голосовой почты!)',	'/ari',	'ARI - интерфейс пользователя АТС (нужен пароль голосовой почты!)'),
(10,	5,	1,	1,	'Управление Call-центром',	'/management-call-center',	'Плеер.ру: Администрирование ЦОВ'),
(11,	5,	2,	1,	'Монитор супервизора',	'/monitor',	'Монитор супервизора'),
(12,	5,	3,	1,	'Пропущенные за ночь',	'/night-missed',	'Пропущенные за ночь'),
(13,	5,	4,	1,	'Пропущенные звонки',	'/missed',	'Пропущенные звонки'),
(14,	6,	1,	1,	'Прямой доступ к файлам записей',	'/file-records',	'Прямой доступ к файлам записей'),
(15,	6,	2,	1,	'Статистика отдела закупщиков',	'/statistic-zakup',	'Статистика отдела закупщиков'),
(16,	6,	3,	1,	'Статистика оптового отдела',	'/statistic-opt',	'Статистика оптового отдела'),
(17,	6,	4,	1,	'Статистика Call-центра',	'/statistic-call-center',	'Статистика Call-центра'),
(18,	7,	1,	1,	'Управление СУБД',	'/phpmyadmin/index.php',	'Управление СУБД'),
(19,	7,	2,	1,	'Контроль Сервера',	'/management-server',	'Контроль Сервера'),
(20,	10,	1,	0,	'Call-центр',	'/call-center',	'Плеер.ру: Администрирование ЦОВ'),
(21,	10,	2,	0,	'Очереди',	'/queue',	'Плеер.ру: Администрирование ЦОВ'),
(22,	10,	3,	0,	'Операторы',	'/management-call-center',	'Плеер.ру: Администрирование ЦОВ'),
(23,	10,	5,	0,	'Список',	'/operators-visible',	'Плеер.ру: Администрирование ЦОВ'),
(24,	10,	6,	0,	'Показать скрытых',	'/operators-hidden',	'Плеер.ру: Администрирование ЦОВ'),
(25,	10,	7,	0,	'Записи',	'/play-records',	'Плеер.ру: Администрирование ЦОВ'),
(26,	10,	4,	0,	'Рабочее время',	'/working-hours',	'Статистика'),
(27,	10,	8,	0,	'Входящие',	'/play-records',	'Плеер.ру: Администрирование ЦОВ'),
(28,	10,	9,	0,	'Исходящие',	'/outgoing',	'Плеер.ру: Администрирование ЦОВ'),
(29,	10,	10,	0,	'Курьеры',	'/couriers',	'Плеер.ру: Администрирование ЦОВ'),
(30,	10,	11,	0,	'Входящие ОПТ',	'/incoming-opt',	'Плеер.ру: Администрирование ЦОВ'),
(31,	10,	12,	0,	'Поиск звонков',	'/call-search',	'Плеер.ру: Администрирование ЦОВ');

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `rules` text,
  `group` varchar(255) DEFAULT NULL,
  `auth_key` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user` (`id`, `uid`, `username`, `rules`, `group`, `auth_key`, `password`) VALUES
(1,	'test',	'test',	'a:31:{i:1;s:4:\"read\";i:2;s:5:\"block\";i:4;s:4:\"read\";i:5;s:4:\"read\";i:6;s:4:\"read\";i:7;s:4:\"read\";i:8;s:4:\"read\";i:9;s:4:\"read\";i:3;s:5:\"block\";i:20;s:4:\"read\";i:10;s:4:\"read\";i:11;s:4:\"read\";i:12;s:4:\"read\";i:13;s:4:\"read\";i:14;s:4:\"read\";i:15;s:4:\"read\";i:16;s:4:\"read\";i:17;s:4:\"read\";i:18;s:4:\"read\";i:19;s:4:\"read\";i:21;s:4:\"read\";i:22;s:4:\"read\";i:23;s:4:\"read\";i:24;s:4:\"read\";i:25;s:4:\"read\";i:26;s:4:\"read\";i:27;s:4:\"read\";i:28;s:4:\"read\";i:29;s:4:\"read\";i:30;s:4:\"read\";i:31;s:4:\"read\";}',	'Domain Users',	'z6ODGJFYwTXSzbK1Ls-iAfvmHDOFy1jw',	NULL),
(2,	'it-dept',	'it-dept',	'a:31:{i:1;s:4:\"read\";i:2;s:4:\"read\";i:3;s:4:\"read\";i:6;s:4:\"read\";i:7;s:4:\"read\";i:8;s:4:\"read\";i:9;s:4:\"read\";i:5;s:4:\"read\";i:4;s:4:\"read\";i:10;s:4:\"read\";i:20;s:4:\"read\";i:11;s:4:\"read\";i:12;s:4:\"read\";i:13;s:4:\"read\";i:14;s:4:\"read\";i:15;s:4:\"read\";i:16;s:4:\"read\";i:17;s:4:\"read\";i:18;s:4:\"read\";i:19;s:4:\"read\";i:21;s:4:\"read\";i:22;s:4:\"read\";i:23;s:4:\"read\";i:24;s:4:\"read\";i:25;s:4:\"read\";i:26;s:4:\"read\";i:27;s:4:\"read\";i:28;s:4:\"read\";i:29;s:4:\"read\";i:30;s:4:\"read\";i:31;s:4:\"read\";}',	'it-dept',	'PZ2Q6DVVmP9DXq1DOOb0Dml5qwno33A1',	'$2y$10$U1OQ1KAe0jLP/iPGrUF2bedbLpOpuYMvEA1N6LkdRPs2KexGUdtqK');

-- 2020-06-29 09:10:46
