/*
SQLyog Ultimate v11.24 (32 bit)
MySQL - 5.5.25 : Database - minigame
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`minigame` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `minigame`;

/*Table structure for table `tb_bill` */

DROP TABLE IF EXISTS `tb_bill`;

CREATE TABLE `tb_bill` (
  `orderId` varchar(255) NOT NULL COMMENT '订单id',
  `uuid` varchar(255) DEFAULT NULL COMMENT '玩家标示',
  `data` text COMMENT 'googleApp返回的信息',
  `signature` text COMMENT '签名',
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`orderId`),
  UNIQUE KEY `orederId` (`orderId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `tb_user` */

DROP TABLE IF EXISTS `tb_user`;

CREATE TABLE `tb_user` (
  `uuid` varchar(255) NOT NULL,
  `pwd` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '名字',
  `gold` int(11) DEFAULT '0' COMMENT '金币',
  `jewel` int(11) DEFAULT '0' COMMENT '钻石',
  `lv` int(11) DEFAULT '0' COMMENT '等级',
  `exp` int(11) DEFAULT '0' COMMENT '经验',
  PRIMARY KEY (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
