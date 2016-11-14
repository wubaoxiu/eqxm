/*
Navicat MySQL Data Transfer

Source Server         : admin
Source Server Version : 50711
Source Host           : localhost:3306
Source Database       : csw

Target Server Type    : MYSQL
Target Server Version : 50711
File Encoding         : 65001

Date: 2016-11-14 22:09:30
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
INSERT INTO `csw_admin` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '1', '1476002@qq.com', '1478750108', '1', '乒乓球', '这个人很懒...');
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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of csw_node
-- ----------------------------
INSERT INTO `csw_node` VALUES ('1', '浏览普通用户列表', 'User', 'index');
INSERT INTO `csw_node` VALUES ('2', '添加用户', 'User', 'add');
INSERT INTO `csw_node` VALUES ('3', '删除普通用户', 'User', 'del');
INSERT INTO `csw_node` VALUES ('4', '修改普通用户', 'User', 'save');
INSERT INTO `csw_node` VALUES ('5', '浏览后台用户列表', 'AdminUser', 'index');
INSERT INTO `csw_node` VALUES ('6', '删除后台用户', 'AdminUser', 'del');
INSERT INTO `csw_node` VALUES ('7', '修改后台用户', 'AdminUser', 'save');

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
  `node_id` int(11) NOT NULL,
  `dec` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of csw_role_node
-- ----------------------------
INSERT INTO `csw_role_node` VALUES ('1', '1', '浏览普通用户列表');
INSERT INTO `csw_role_node` VALUES ('1', '2', '添加用户');
INSERT INTO `csw_role_node` VALUES ('1', '3', '删除普通用户');
INSERT INTO `csw_role_node` VALUES ('1', '4', '修改普通用户');
INSERT INTO `csw_role_node` VALUES ('1', '5', '浏览后台用户');
INSERT INTO `csw_role_node` VALUES ('1', '6', '删除后台用户');
INSERT INTO `csw_role_node` VALUES ('1', '7', '修改后台用户');
INSERT INTO `csw_role_node` VALUES ('2', '1', '浏览普通用户列表');
INSERT INTO `csw_role_node` VALUES ('2', '2', '添加用户');
INSERT INTO `csw_role_node` VALUES ('2', '3', '删除普通用户');
INSERT INTO `csw_role_node` VALUES ('2', '4', '修改普通用户');
INSERT INTO `csw_role_node` VALUES ('2', '5', '浏览后台用户列表');

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
  `createtime` int(11) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of csw_type
-- ----------------------------
INSERT INTO `csw_type` VALUES ('1', '娱乐明星', '0', '1478750108', '0,');
INSERT INTO `csw_type` VALUES ('2', '爱综艺', '0', '1478750108', '0，');
INSERT INTO `csw_type` VALUES ('3', '追剧狂', '0', '1478750108', '0，');
INSERT INTO `csw_type` VALUES ('4', '内地明星', '1', '1478750108', '0,1,');
INSERT INTO `csw_type` VALUES ('5', '看电影', '0', '1479128633', '0,');
INSERT INTO `csw_type` VALUES ('6', '香港电影', '5', '1479128669', '0,5,');
INSERT INTO `csw_type` VALUES ('7', '体育', '0', '1479129049', '0,');
INSERT INTO `csw_type` VALUES ('8', '足球', '7', '1479129061', '0,7,');
INSERT INTO `csw_type` VALUES ('9', '世界杯', '8', '1479129078', '0,7,8,');

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
  `role_id` int(11) NOT NULL,
  `like` varchar(255) DEFAULT NULL,
  `introduction` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of csw_user
-- ----------------------------
INSERT INTO `csw_user` VALUES ('1', 'a', 'e10adc3949ba59abbe56e057f20f883e', '1', '1476002@qq.com', '1478750108', '4', '乒乓球', '这个人很懒...');
INSERT INTO `csw_user` VALUES ('2', 'b', 'e10adc3949ba59abbe56e057f20f883e', '1', '1', '1478750108', '7', '篮球', '无');
INSERT INTO `csw_user` VALUES ('3', 'c', 'e10adc3949ba59abbe56e057f20f883e', '2', '11', '1478750280', '6', null, null);

-- ----------------------------
-- Table structure for `csw_user_role`
-- ----------------------------
DROP TABLE IF EXISTS `csw_user_role`;
CREATE TABLE `csw_user_role` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `dec` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of csw_user_role
-- ----------------------------
INSERT INTO `csw_user_role` VALUES ('1', '1', 'root');
INSERT INTO `csw_user_role` VALUES ('2', '2', '超管');
INSERT INTO `csw_user_role` VALUES ('3', '2', '超管');
INSERT INTO `csw_user_role` VALUES ('4', '2', '超管');
