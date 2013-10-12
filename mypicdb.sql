-- phpMyAdmin SQL Dump
-- version 3.3.7
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 06 月 20 日 20:31
-- 服务器版本: 5.1.50
-- PHP 版本: 5.2.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `mypicdb`
--

-- --------------------------------------------------------

--
-- 表的结构 `xk_admin`
--

CREATE TABLE IF NOT EXISTS `xk_admin` (
  `admin_id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(50) NOT NULL,
  `admin_password` varchar(50) NOT NULL,
  `admin_parmissions` text NOT NULL,
  `admin_state` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `xk_admin`
--

INSERT INTO `xk_admin` (`admin_id`, `admin_name`, `admin_password`, `admin_parmissions`, `admin_state`) VALUES
(1, 'admin', '14e1b600b1fd579f47433b88e8d85291', '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `xk_admin_log`
--

CREATE TABLE IF NOT EXISTS `xk_admin_log` (
  `log_id` int(64) unsigned NOT NULL AUTO_INCREMENT,
  `log_time` int(10) NOT NULL DEFAULT '0',
  `log_info` varchar(255) CHARACTER SET utf8 NOT NULL,
  `log_ip` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `log_agent` varchar(255) CHARACTER SET utf8 NOT NULL,
  `admin_id` int(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `xk_admin_log`
--

INSERT INTO `xk_admin_log` (`log_id`, `log_time`, `log_info`, `log_ip`, `log_agent`, `admin_id`) VALUES
(1, 1371731108, '增加新闻[服务协议]', '127.0.0.1', '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `xk_albums`
--

CREATE TABLE IF NOT EXISTS `xk_albums` (
  `albums_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` mediumint(8) NOT NULL,
  `albums_name` varchar(50) NOT NULL DEFAULT '',
  `albums_parmissions` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`albums_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `xk_albums`
--


-- --------------------------------------------------------

--
-- 表的结构 `xk_friend`
--

CREATE TABLE IF NOT EXISTS `xk_friend` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `fuid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `fusername` varchar(15) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `note` varchar(50) NOT NULL DEFAULT '',
  `num` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fuid` (`fuid`),
  KEY `status` (`uid`,`status`,`num`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `xk_friend`
--


-- --------------------------------------------------------

--
-- 表的结构 `xk_member`
--

CREATE TABLE IF NOT EXISTS `xk_member` (
  `member_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `member_username` varchar(50) NOT NULL,
  `member_password` varchar(255) NOT NULL,
  `member_safecode` varchar(255) NOT NULL,
  `member_mail` varchar(255) NOT NULL,
  `member_nickname` varchar(50) NOT NULL DEFAULT '',
  `member_name` varchar(50) NOT NULL DEFAULT '',
  `member_card` varchar(50) NOT NULL,
  `member_sex` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `member_birthday` int(10) unsigned NOT NULL DEFAULT '0',
  `member_phone` varchar(50) NOT NULL DEFAULT '',
  `member_photo` varchar(50) NOT NULL DEFAULT '',
  `member_from` varchar(255) NOT NULL DEFAULT '',
  `member_other` varchar(255) NOT NULL DEFAULT '',
  `member_join_time` int(10) NOT NULL DEFAULT '0',
  `member_join_ip` varchar(50) NOT NULL,
  `member_last_time` int(10) NOT NULL DEFAULT '0',
  `member_last_ip` varchar(50) NOT NULL,
  `member_validation` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `member_validation_key` varchar(32) NOT NULL,
  `member_state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `group_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `spread_user` varchar(50) NOT NULL,
  `spread_keyword` varchar(50) NOT NULL,
  `account` int(64) NOT NULL,
  PRIMARY KEY (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `xk_member`
--


-- --------------------------------------------------------

--
-- 表的结构 `xk_member_journal`
--

CREATE TABLE IF NOT EXISTS `xk_member_journal` (
  `journal_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(16) NOT NULL,
  `journal_title` varchar(255) NOT NULL,
  `journal_url` varchar(255) NOT NULL DEFAULT '',
  `journal_keywords` varchar(255) NOT NULL DEFAULT '',
  `journal_text` text NOT NULL,
  `journal_comment_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `journal_state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `journal_time` int(10) unsigned NOT NULL DEFAULT '0',
  `comm_num` int(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`journal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `xk_member_journal`
--


-- --------------------------------------------------------

--
-- 表的结构 `xk_member_journal_comment`
--

CREATE TABLE IF NOT EXISTS `xk_member_journal_comment` (
  `comment_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `from_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `journal_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `note` varchar(50) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `xk_member_journal_comment`
--


-- --------------------------------------------------------

--
-- 表的结构 `xk_member_notice`
--

CREATE TABLE IF NOT EXISTS `xk_member_notice` (
  `notice_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `from_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `to_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `note` varchar(50) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`notice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `xk_member_notice`
--


-- --------------------------------------------------------

--
-- 表的结构 `xk_member_status`
--

CREATE TABLE IF NOT EXISTS `xk_member_status` (
  `status_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` mediumint(8) NOT NULL,
  `status_text` text NOT NULL,
  `status_time` int(10) unsigned NOT NULL DEFAULT '0',
  `comm_num` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`status_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `xk_member_status`
--


-- --------------------------------------------------------

--
-- 表的结构 `xk_member_status_comment`
--

CREATE TABLE IF NOT EXISTS `xk_member_status_comment` (
  `comment_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `from_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `status_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `note` varchar(50) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `xk_member_status_comment`
--


-- --------------------------------------------------------

--
-- 表的结构 `xk_menber_message`
--

CREATE TABLE IF NOT EXISTS `xk_menber_message` (
  `message_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `from_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `to_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `info` varchar(50) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`message_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `xk_menber_message`
--


-- --------------------------------------------------------

--
-- 表的结构 `xk_news`
--

CREATE TABLE IF NOT EXISTS `xk_news` (
  `content_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `content_title` varchar(255) NOT NULL,
  `content_url` varchar(255) NOT NULL DEFAULT '',
  `content_keywords` varchar(255) NOT NULL DEFAULT '',
  `content_text` text NOT NULL,
  `content_description` varchar(255) NOT NULL DEFAULT '',
  `content_click_count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `content_comment_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `content_is_best` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `content_is_comment` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `content_state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `content_time` int(16) unsigned NOT NULL DEFAULT '0',
  `channel_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `category_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`content_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `xk_news`
--

INSERT INTO `xk_news` (`content_id`, `content_title`, `content_url`, `content_keywords`, `content_text`, `content_description`, `content_click_count`, `content_comment_count`, `content_is_best`, `content_is_comment`, `content_state`, `content_time`, `channel_id`, `category_id`) VALUES
(5, '关于本站', '', '', '<p style="text-align: center; ">\r\n	<strong>2009级本科生毕业设计</strong></p>\r\n<p style="text-align: center; ">\r\n	<strong>姓名：赵永</strong></p>\r\n<p style="text-align: center; ">\r\n	<strong>学院：理学院</strong></p>\r\n<p style="text-align: center; ">\r\n	<strong>专业：信息与计算科学</strong></p>\r\n<p style="text-align: center; ">\r\n	<strong>学号：09112112</strong></p>\r\n', '', 0, 0, 0, 0, 0, 1371688202, 0, 0),
(2, '测试新闻第一篇', '', '', '测试新闻第一篇', '', 0, 0, 0, 0, 0, 0, 0, 0),
(3, '测试一下小', '', '', '<p>\r\n	测试一下小测试一下小测试一下小</p>\r\n', '', 0, 0, 0, 0, 0, 1371630764, 0, 0),
(6, '服务协议', '', '', '<p>\r\n	会员不得违反法律，发布不良信息。。。。。。。等等</p>\r\n', '', 0, 0, 0, 0, 0, 1371731108, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `xk_pay`
--

CREATE TABLE IF NOT EXISTS `xk_pay` (
  `pay_id` int(4) NOT NULL AUTO_INCREMENT,
  `pay_order_no` varchar(255) DEFAULT NULL,
  `pay_state` tinyint(1) DEFAULT NULL,
  `pay_user` varchar(50) DEFAULT NULL,
  `pay_photo_id` int(4) DEFAULT NULL,
  `pay_phototype_id` int(4) DEFAULT NULL,
  `pay_money` int(4) DEFAULT NULL,
  `pay_time` int(10) DEFAULT NULL,
  `pay_ip` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`pay_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `xk_pay`
--


-- --------------------------------------------------------

--
-- 表的结构 `xk_photo`
--

CREATE TABLE IF NOT EXISTS `xk_photo` (
  `photo_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` mediumint(8) NOT NULL,
  `albums_id` mediumint(8) NOT NULL,
  `photo_name` varchar(64) NOT NULL,
  `photo_depict` varchar(512) DEFAULT NULL,
  `photo_type_id` tinyint(2) NOT NULL,
  `photo_keywords` varchar(128) NOT NULL,
  `photo_thum` varchar(64) NOT NULL,
  `photo_original` varchar(64) NOT NULL,
  `photo_iscut` tinyint(1) NOT NULL DEFAULT '0',
  `photo_cut1` varchar(64) NOT NULL,
  `photo_cut2` varchar(64) NOT NULL,
  `photo_cut3` varchar(64) NOT NULL,
  `photo_price` int(64) NOT NULL DEFAULT '0',
  `photo_state` tinyint(1) NOT NULL DEFAULT '0',
  `photo_is_show` tinyint(1) NOT NULL DEFAULT '1',
  `photo_is_best` tinyint(1) NOT NULL DEFAULT '0',
  `photo_is_hot` tinyint(1) NOT NULL DEFAULT '0',
  `photo_id_focus` tinyint(1) NOT NULL DEFAULT '0',
  `photo_time` varchar(32) NOT NULL,
  PRIMARY KEY (`photo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `xk_photo`
--


-- --------------------------------------------------------

--
-- 表的结构 `xk_photo_comment`
--

CREATE TABLE IF NOT EXISTS `xk_photo_comment` (
  `comment_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `from_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `photo_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `note` varchar(50) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `xk_photo_comment`
--


-- --------------------------------------------------------

--
-- 表的结构 `xk_photo_evaluate`
--

CREATE TABLE IF NOT EXISTS `xk_photo_evaluate` (
  `eva_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `photo_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `evaluate` int(16) NOT NULL DEFAULT '0',
  `eva_num` int(8) NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`eva_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `xk_photo_evaluate`
--


-- --------------------------------------------------------

--
-- 表的结构 `xk_photo_type`
--

CREATE TABLE IF NOT EXISTS `xk_photo_type` (
  `type_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(64) NOT NULL,
  `type_depict` varchar(128) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `xk_photo_type`
--

INSERT INTO `xk_photo_type` (`type_id`, `type_name`, `type_depict`) VALUES
(1, '自然风光', '来自大自然的风光'),
(2, '建筑风景', '建筑风景'),
(3, '人文景象', '人文景象'),
(4, '抽象艺术', '抽象艺术'),
(6, '美食', '美食');
