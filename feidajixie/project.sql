/*
Navicat MySQL Data Transfer

Source Server         : 本地链接
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : project

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-08-09 16:52:02
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `qd_admin`
-- ----------------------------
DROP TABLE IF EXISTS `qd_admin`;
CREATE TABLE `qd_admin` (
  `ad_id` int(11) NOT NULL AUTO_INCREMENT,
  `ad_username` varchar(100) NOT NULL COMMENT '用户名',
  `ad_pwdval` varchar(100) NOT NULL COMMENT '密码',
  `ad_time` int(30) NOT NULL DEFAULT '0' COMMENT '写入时间',
  `ad_lasttime` int(30) DEFAULT '0' COMMENT '最后登陆时间',
  `ad_lastip` varchar(100) DEFAULT NULL COMMENT '最后登陆ip',
  `ad_grod` int(11) NOT NULL DEFAULT '0' COMMENT '管理级别',
  `ad_setval` smallint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用',
  PRIMARY KEY (`ad_id`),
  UNIQUE KEY `id` (`ad_id`) USING BTREE,
  UNIQUE KEY `username` (`ad_username`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of qd_admin
-- ----------------------------
INSERT INTO `qd_admin` VALUES ('1', 'admin', '2463a85fa69dc826a6b1b0a980f91f22', '1502200633', '1502261297', '127.0.0.1', '1', '1');
INSERT INTO `qd_admin` VALUES ('2', 'herenshan110', '2463a85fa69dc826a6b1b0a980f91f22', '1502200634', '1502255017', '127.0.0.1', '2', '1');

-- ----------------------------
-- Table structure for `qd_admindata`
-- ----------------------------
DROP TABLE IF EXISTS `qd_admindata`;
CREATE TABLE `qd_admindata` (
  `ada_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '管理员id',
  `ada_name` varchar(50) DEFAULT NULL COMMENT '管理员名称',
  `ada_tel` varchar(20) DEFAULT NULL COMMENT '联系电话',
  `ada_qq` varchar(20) DEFAULT NULL COMMENT 'QQ号码',
  `ada_email` varchar(50) DEFAULT NULL COMMENT '电子邮箱',
  `ada_address` varchar(255) DEFAULT NULL COMMENT '联系地址',
  `ada_cont` mediumtext COMMENT '管理员介绍',
  `ada_imgpic` varchar(255) DEFAULT NULL COMMENT '头像',
  PRIMARY KEY (`ada_id`),
  UNIQUE KEY `ada_id` (`ada_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员资料表';

-- ----------------------------
-- Records of qd_admindata
-- ----------------------------
INSERT INTO `qd_admindata` VALUES ('2', '何仁山', '15010830317', '532623622', '532623622@qq.com', '北京', '技术', '2017_08_08/20170808220539395502.png');
INSERT INTO `qd_admindata` VALUES ('1', '秦东', '15069130853', '532623622', '532623622@qq.com', '北京朝阳区', '我是一名程序', '2017_08_09/20170809081617604156.png');

-- ----------------------------
-- Table structure for `qd_advcont`
-- ----------------------------
DROP TABLE IF EXISTS `qd_advcont`;
CREATE TABLE `qd_advcont` (
  `advc_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `advc_title` varchar(255) DEFAULT NULL COMMENT 'banner标题',
  `advc_type` int(11) NOT NULL DEFAULT '0' COMMENT '所属范围',
  `advc_images` varchar(255) DEFAULT NULL COMMENT '封面图片',
  `advc_url` varchar(255) DEFAULT NULL COMMENT '尾部链接',
  `advc_cont` mediumtext COMMENT '详细介绍',
  `advc_setval` smallint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `advc_time` int(50) unsigned NOT NULL DEFAULT '0' COMMENT '写入时间',
  `advc_uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '写入人',
  PRIMARY KEY (`advc_id`),
  UNIQUE KEY `id` (`advc_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告内容';

-- ----------------------------
-- Records of qd_advcont
-- ----------------------------

-- ----------------------------
-- Table structure for `qd_advtype`
-- ----------------------------
DROP TABLE IF EXISTS `qd_advtype`;
CREATE TABLE `qd_advtype` (
  `advt_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `advt_title` varchar(100) DEFAULT '' COMMENT '位置名称',
  `advt_fanwei` int(11) NOT NULL DEFAULT '0' COMMENT '有效范围',
  `advt_cont` mediumtext COMMENT '描述',
  `advt_setval` smallint(1) NOT NULL DEFAULT '0' COMMENT '是否启用',
  `advt_time` int(50) NOT NULL DEFAULT '0' COMMENT '写入时间',
  `advt_uid` int(11) NOT NULL DEFAULT '0' COMMENT '写入人',
  PRIMARY KEY (`advt_id`),
  UNIQUE KEY `advt_id` (`advt_id`) USING BTREE,
  UNIQUE KEY `advt_title` (`advt_title`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告位置';

-- ----------------------------
-- Records of qd_advtype
-- ----------------------------

-- ----------------------------
-- Table structure for `qd_config`
-- ----------------------------
DROP TABLE IF EXISTS `qd_config`;
CREATE TABLE `qd_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `webname` varchar(100) DEFAULT NULL COMMENT '网站名称',
  `companyname` varchar(100) DEFAULT NULL COMMENT '公司名称',
  `companyadd` varchar(100) DEFAULT NULL COMMENT '公司地址',
  `icpnumber` varchar(50) DEFAULT NULL COMMENT '备案号',
  `keyword` varchar(200) DEFAULT NULL COMMENT '关键词',
  `description` varchar(255) DEFAULT NULL COMMENT '站点描述',
  `Personname` varchar(10) DEFAULT NULL COMMENT '负责人',
  `mobel` int(12) DEFAULT NULL COMMENT '手机号码',
  `telval` varchar(20) DEFAULT NULL COMMENT '固定电话',
  `faxnum` varchar(20) DEFAULT NULL COMMENT '传真',
  `email` varchar(100) DEFAULT NULL COMMENT '电子邮件',
  `qqnumber` varchar(20) DEFAULT NULL COMMENT 'QQ号码',
  `imagesize` int(11) NOT NULL DEFAULT '1' COMMENT '图片上传大小',
  `imagestype` varchar(255) DEFAULT NULL,
  `filesize` int(11) NOT NULL DEFAULT '1' COMMENT '文档上传大小',
  `filetype` varchar(255) DEFAULT NULL COMMENT '文档上传类型',
  `vidosize` int(11) NOT NULL DEFAULT '1' COMMENT '视频上传大小',
  `vidotype` varchar(255) DEFAULT NULL COMMENT '视频上传类型',
  `sandaima` mediumtext COMMENT '第三方代码',
  `userset` int(1) NOT NULL DEFAULT '0' COMMENT '会员设置',
  `webset` int(1) NOT NULL DEFAULT '1' COMMENT '网站是否开启',
  `mobackval` varchar(30) NOT NULL DEFAULT 'default' COMMENT '默认模版风格',
  `databack` varchar(30) NOT NULL DEFAULT 'backbest' COMMENT '数据库默认备份路径',
  `session_time` int(10) DEFAULT '30' COMMENT 'session有效时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qd_config
-- ----------------------------
INSERT INTO `qd_config` VALUES ('1', '我的网站', '崬奋科技', '北京', '京ICP备-123456789', '这事我的网站', '这是我的网站的描述', '秦东', '2147483647', '', '', '532623622@qq.com', '532623622', '500', 'jpg|jpng|gif|png|bmp|ico', '100', 'rar|zip|txt|doc|docx|xls|xlsx', '500', 'swf|flv|mp4|avi', '测试第三方代码', '0', '1', 'default', 'backbest', '30');

-- ----------------------------
-- Table structure for `qd_contents`
-- ----------------------------
DROP TABLE IF EXISTS `qd_contents`;
CREATE TABLE `qd_contents` (
  `cn_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cn_title` varchar(200) DEFAULT '0' COMMENT '文档标题',
  `cn_attr` varchar(30) NOT NULL DEFAULT '0' COMMENT '文档属性',
  `cn_type` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文档分类',
  `cn_keyword` varchar(255) DEFAULT '0' COMMENT '关键词',
  `cn_descr` mediumtext COMMENT '描述',
  `cn_smallimg` varchar(255) DEFAULT '0' COMMENT '封面小图',
  `cn_bigimg` varchar(255) DEFAULT '0' COMMENT '封面大图',
  `cn_sort` smallint(10) unsigned DEFAULT '50' COMMENT '排序',
  `cn_urllinks` varchar(255) DEFAULT '0' COMMENT '外部链接',
  `cn_content` mediumtext COMMENT '详细内容',
  `cn_setval` smallint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `cn_time` int(50) unsigned NOT NULL DEFAULT '0' COMMENT '写入时间',
  `cn_uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '写入人',
  `cn_look` int(10) DEFAULT '0' COMMENT '阅读量',
  `cn_delt` smallint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已删除',
  `cn_imgary` mediumtext COMMENT '图片集',
  `cn_deltime` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  `cn_deituid` int(11) NOT NULL DEFAULT '0' COMMENT '删除人',
  `cn_voidurl` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`cn_id`),
  UNIQUE KEY `id` (`cn_id`) USING BTREE,
  KEY `title` (`cn_title`) USING BTREE,
  KEY `idpx` (`cn_id`,`cn_sort`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qd_contents
-- ----------------------------

-- ----------------------------
-- Table structure for `qd_grades`
-- ----------------------------
DROP TABLE IF EXISTS `qd_grades`;
CREATE TABLE `qd_grades` (
  `grd_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `grd_title` varchar(100) DEFAULT NULL COMMENT '权限名称',
  `grd_cont` mediumtext COMMENT '权限描述',
  `grd_setval` smallint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用',
  `grd_time` int(50) unsigned NOT NULL DEFAULT '0' COMMENT '写入时间',
  `grd_uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '写入人',
  `grd_arycon` mediumtext COMMENT '权限值',
  `grd_val` smallint(10) NOT NULL DEFAULT '0' COMMENT '权重',
  PRIMARY KEY (`grd_id`),
  UNIQUE KEY `grd_id` (`grd_id`) USING BTREE,
  UNIQUE KEY `grd_title` (`grd_title`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='管理员等级';

-- ----------------------------
-- Records of qd_grades
-- ----------------------------
INSERT INTO `qd_grades` VALUES ('1', '超级管理员', '拥有全部管理权限', '1', '1502165794', '0', '1|2|3|4|5|6|7|8|9|10|11|12|13|14|15|16|17|18|19|20|21|22|23|24|25|26|27|28|29|30|31|32|33|34|35|36|37|38|39|40|41|42|43|44|45|46|47|48|49|50|51', '15');
INSERT INTO `qd_grades` VALUES ('2', '管理员', '有部分权限', '1', '1502239186', '0', '1|2|3|4|5|6|7|8|9|10|11|12|13|14|15|16|17|18|19|20|21|22|23|24|25|26|27|28|29|30|31|32|33|34|35|36|37|38|45|46|47|48|49|50|51', '14');

-- ----------------------------
-- Table structure for `qd_link`
-- ----------------------------
DROP TABLE IF EXISTS `qd_link`;
CREATE TABLE `qd_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL COMMENT '链接名称',
  `linkurl` varchar(150) DEFAULT NULL COMMENT '链接地址',
  `images` varchar(255) DEFAULT NULL COMMENT '链接图标',
  `linkcont` mediumtext COMMENT '链接描述',
  `linkset` int(1) NOT NULL DEFAULT '1' COMMENT '是否开启',
  `time` int(50) NOT NULL DEFAULT '0' COMMENT '写入时间',
  `lnk_uid` int(11) NOT NULL DEFAULT '0' COMMENT '写入人',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='友情链接';

-- ----------------------------
-- Records of qd_link
-- ----------------------------
INSERT INTO `qd_link` VALUES ('1', '百度', 'http://www.baidu.com', '2017_08_08/20170808085425480104.png', '百度', '1', '1502153668', '0');

-- ----------------------------
-- Table structure for `qd_pagetype`
-- ----------------------------
DROP TABLE IF EXISTS `qd_pagetype`;
CREATE TABLE `qd_pagetype` (
  `pty_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pty_title` varchar(50) NOT NULL DEFAULT '0' COMMENT '类型名称',
  `pty_fatherid` int(11) DEFAULT '0' COMMENT '所属分类',
  `pty_filetype` int(11) DEFAULT '0' COMMENT '栏目类型',
  `pty_keyword` varchar(255) DEFAULT '0' COMMENT '关键字',
  `pty_destons` varchar(255) DEFAULT '0' COMMENT '栏目描述',
  `pty_images` varchar(200) DEFAULT '0' COMMENT '栏目封面图片',
  `pty_paixu` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `pty_pgsum` smallint(5) unsigned NOT NULL DEFAULT '20' COMMENT '每页显示个数',
  `pty_cont` mediumtext COMMENT '栏目描述',
  `pty_time` int(50) unsigned NOT NULL DEFAULT '0' COMMENT '写入时间',
  `pty_set` smallint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`pty_id`),
  UNIQUE KEY `pty_id` (`pty_id`) USING BTREE,
  KEY `pty_title` (`pty_title`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='栏目类型列表';

-- ----------------------------
-- Records of qd_pagetype
-- ----------------------------

-- ----------------------------
-- Table structure for `qd_types`
-- ----------------------------
DROP TABLE IF EXISTS `qd_types`;
CREATE TABLE `qd_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT '0' COMMENT '栏目类型名称',
  `mobelurl` varchar(255) DEFAULT '0' COMMENT '模版地址',
  `contval` mediumtext COMMENT '描述',
  `setval` int(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  `uid` int(11) unsigned DEFAULT '0' COMMENT '发布人',
  `time` int(50) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  `onetwo` int(1) NOT NULL DEFAULT '1' COMMENT '是否为单页文档',
  `methodse` varchar(100) DEFAULT 'index' COMMENT '处理方法',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`) USING BTREE,
  KEY `title` (`title`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='栏目类型';

-- ----------------------------
-- Records of qd_types
-- ----------------------------
INSERT INTO `qd_types` VALUES ('1', '单页文档', 'index.html', '单页文档，例如公司简介，联系我们等页面。', '1', '1', '1497753394', '0', 'about');
INSERT INTO `qd_types` VALUES ('2', '文章列表文档', 'pagecont.html', '新闻类型的文章', '1', '1', '1497754512', '1', 'newslist');
INSERT INTO `qd_types` VALUES ('3', '图片集文档', 'imagescont.html', '是用于多图片的文档', '1', '1', '1497763584', '1', 'imglist');
INSERT INTO `qd_types` VALUES ('4', '视频文档', 'voidmode.html', '播放视屏的文档类型', '1', '1', '1497765445', '1', 'voidelist');
INSERT INTO `qd_types` VALUES ('5', '下载类型', 'main.html', '带有附件上传的文档', '0', '1', '1497765472', '1', 'downlist');
INSERT INTO `qd_types` VALUES ('6', '招聘文档', 'main.html', '发布招聘信息的文档', '0', '1', '1497765494', '1', 'reclist');
