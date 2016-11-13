/*
Navicat MySQL Data Transfer

Source Server         : admin
Source Server Version : 50711
Source Host           : localhost:3306
Source Database       : csw

Target Server Type    : MYSQL
Target Server Version : 50711
File Encoding         : 65001

Date: 2016-11-13 17:22:53
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `csw_admin`
-- ----------------------------
DROP TABLE IF EXISTS `csw_admin`;
CREATE TABLE `csw_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `sex` char(4) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `createtime` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `like` varchar(255) DEFAULT NULL,
  `introduction` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of csw_admin
-- ----------------------------
INSERT INTO `csw_admin` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '1', '1476002@qq.com', '1478750108', '0', '乒乓球', '这个人很懒...');
INSERT INTO `csw_admin` VALUES ('2', 'wbx', 'e10adc3949ba59abbe56e057f20f883e', '1', '1', '1478750108', '2', '篮球', '无');
INSERT INTO `csw_admin` VALUES ('3', 'yjx', 'e10abc3949ba59abbe56e057f20f883e', '1', '232@qq.com', '1478750108', '2', '乒乓球', '这个人很懒');
INSERT INTO `csw_admin` VALUES ('4', 'xdm', 'e10abc3949ba59abbe56e057f20f883e', '1', '234@qq.com', '1478750108', '2', 'haha', 'ehe');

-- ----------------------------
-- Table structure for `csw_baradmin`
-- ----------------------------
DROP TABLE IF EXISTS `csw_baradmin`;
CREATE TABLE `csw_baradmin` (
  `user_id` int(11) NOT NULL,
  `bar_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of csw_baradmin
-- ----------------------------

-- ----------------------------
-- Table structure for `csw_barinfo`
-- ----------------------------
DROP TABLE IF EXISTS `csw_barinfo`;
CREATE TABLE `csw_barinfo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `createtime` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of csw_barinfo
-- ----------------------------

-- ----------------------------
-- Table structure for `csw_bars`
-- ----------------------------
DROP TABLE IF EXISTS `csw_bars`;
CREATE TABLE `csw_bars` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `bar_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of csw_bars
-- ----------------------------

-- ----------------------------
-- Table structure for `csw_comments`
-- ----------------------------
DROP TABLE IF EXISTS `csw_comments`;
CREATE TABLE `csw_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `floor_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `createtime` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `muser_id` int(11) NOT NULL,
  `note_id` int(11) NOT NULL,
  `bar_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of csw_comments
-- ----------------------------

-- ----------------------------
-- Table structure for `csw_fans`
-- ----------------------------
DROP TABLE IF EXISTS `csw_fans`;
CREATE TABLE `csw_fans` (
  `user_id` int(11) NOT NULL,
  `fuser_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of csw_fans
-- ----------------------------

-- ----------------------------
-- Table structure for `csw_floor`
-- ----------------------------
DROP TABLE IF EXISTS `csw_floor`;
CREATE TABLE `csw_floor` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `note_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `createtime` int(11) NOT NULL,
  `floor` int(11) NOT NULL,
  `bar_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of csw_floor
-- ----------------------------

-- ----------------------------
-- Table structure for `csw_freeze`
-- ----------------------------
DROP TABLE IF EXISTS `csw_freeze`;
CREATE TABLE `csw_freeze` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bar_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `createtime` int(11) NOT NULL,
  `liberate` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of csw_freeze
-- ----------------------------

-- ----------------------------
-- Table structure for `csw_node`
-- ----------------------------
DROP TABLE IF EXISTS `csw_node`;
CREATE TABLE `csw_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node` varchar(255) NOT NULL,
  `cname` varchar(255) DEFAULT NULL,
  `aname` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of csw_node
-- ----------------------------
INSERT INTO `csw_node` VALUES ('1', '浏览用户列表', 'User', 'index');
INSERT INTO `csw_node` VALUES ('2', '添加用户', 'User', 'add');

-- ----------------------------
-- Table structure for `csw_note`
-- ----------------------------
DROP TABLE IF EXISTS `csw_note`;
CREATE TABLE `csw_note` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `createtime` int(11) NOT NULL,
  `bar_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `istop` tinyint(4) DEFAULT '1',
  `isfine` tinyint(4) DEFAULT '1',
  `scan` int(11) DEFAULT '0',
  `reply` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of csw_note
-- ----------------------------

-- ----------------------------
-- Table structure for `csw_role`
-- ----------------------------
DROP TABLE IF EXISTS `csw_role`;
CREATE TABLE `csw_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of csw_role
-- ----------------------------
INSERT INTO `csw_role` VALUES ('1', 'root');
INSERT INTO `csw_role` VALUES ('2', '超级管理员');
INSERT INTO `csw_role` VALUES ('3', '业务员');
INSERT INTO `csw_role` VALUES ('4', '吧主');
INSERT INTO `csw_role` VALUES ('5', '吧管理员');
INSERT INTO `csw_role` VALUES ('6', 'VIP');
INSERT INTO `csw_role` VALUES ('7', '会员');
INSERT INTO `csw_role` VALUES ('8', '游客');

-- ----------------------------
-- Table structure for `csw_role_node`
-- ----------------------------
DROP TABLE IF EXISTS `csw_role_node`;
CREATE TABLE `csw_role_node` (
  `role_id` int(11) NOT NULL,
  `node_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of csw_role_node
-- ----------------------------
INSERT INTO `csw_role_node` VALUES ('1', '1');
INSERT INTO `csw_role_node` VALUES ('1', '2');

-- ----------------------------
-- Table structure for `csw_score`
-- ----------------------------
DROP TABLE IF EXISTS `csw_score`;
CREATE TABLE `csw_score` (
  `id` int(11) NOT NULL,
  `bar_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `integral` int(11) DEFAULT '0',
  `grade` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of csw_score
-- ----------------------------

-- ----------------------------
-- Table structure for `csw_shutup`
-- ----------------------------
DROP TABLE IF EXISTS `csw_shutup`;
CREATE TABLE `csw_shutup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bar_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createtime` int(11) NOT NULL,
  `liberate` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of csw_shutup
-- ----------------------------

-- ----------------------------
-- Table structure for `csw_type`
-- ----------------------------
DROP TABLE IF EXISTS `csw_type`;
CREATE TABLE `csw_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `pid` int(11) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of csw_type
-- ----------------------------

-- ----------------------------
-- Table structure for `csw_user`
-- ----------------------------
DROP TABLE IF EXISTS `csw_user`;
CREATE TABLE `csw_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `sex` char(4) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `createtime` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `like` varchar(255) DEFAULT NULL,
  `introduction` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of csw_user
-- ----------------------------
INSERT INTO `csw_user` VALUES ('1', 'a', 'e10adc3949ba59abbe56e057f20f883e', '1', '1476002@qq.com', '1478750108', '0', '乒乓球', '这个人很懒...');
INSERT INTO `csw_user` VALUES ('2', 'b', 'e10adc3949ba59abbe56e057f20f883e', '1', '1', '1478750108', '0', '篮球', '无');
INSERT INTO `csw_user` VALUES ('3', 'c', 'e10adc3949ba59abbe56e057f20f883e', '2', '11', '1478750280', '0', null, null);

-- ----------------------------
-- Table structure for `csw_user_role`
-- ----------------------------
DROP TABLE IF EXISTS `csw_user_role`;
CREATE TABLE `csw_user_role` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of csw_user_role
-- ----------------------------
INSERT INTO `csw_user_role` VALUES ('1', '1');
INSERT INTO `csw_user_role` VALUES ('2', '2');
INSERT INTO `csw_user_role` VALUES ('3', '2');
INSERT INTO `csw_user_role` VALUES ('4', '2');
