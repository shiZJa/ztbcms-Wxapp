-- ----------------------------
-- 小程序的配置信息
-- ----------------------------
DROP TABLE IF EXISTS `cms_wxapp_appinfo`;
CREATE TABLE `cms_wxapp_appinfo` (
  `appid` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'appid',
  `secret` varchar(300) COLLATE utf8_unicode_ci NOT NULL COMMENT '小程序秘钥',
  `login_duration` int(11) DEFAULT '30',
  `session_duration` int(11) DEFAULT '2592000' COMMENT '’',
  `qcloud_appid` varchar(300) COLLATE utf8_unicode_ci DEFAULT 'appid_qcloud',
  `ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT '0.0.0.0',
  PRIMARY KEY (`appid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- ----------------------------
-- 小程序用户相关信息
-- ----------------------------
DROP TABLE IF EXISTS `cms_wxapp_sessioninfo`;
CREATE TABLE `cms_wxapp_sessioninfo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `skey` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_time` datetime NOT NULL,
  `last_visit_time` datetime NOT NULL,
  `open_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `session_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_info` varchar(2048) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会话管理用户信息';

-- ----------------------------
-- 聊天室表
-- ----------------------------
DROP TABLE IF EXISTS `cms_wxapp_room`;
CREATE TABLE `cms_wxapp_room` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` varchar(128) DEFAULT NULL COMMENT '创建用户的id',
  `room_name` varchar(64) DEFAULT NULL COMMENT '聊天室名称',
  `conversation_id` varchar(128) DEFAULT NULL COMMENT '会话id',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `master` int(11) DEFAULT '0' COMMENT '是否创建者',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- ----------------------------
-- 用户信息
-- ----------------------------
DROP TABLE IF EXISTS `cms_wxapp_userinfo`;
CREATE TABLE `cms_wxapp_userinfo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `open_id` varchar(128) DEFAULT NULL COMMENT 'openid',
  `nick_name` varchar(64) DEFAULT NULL COMMENT ' 昵称',
  `gender` int(11) DEFAULT NULL COMMENT '性别',
  `language` varchar(64) DEFAULT NULL COMMENT '语言',
  `city` varchar(64) DEFAULT NULL COMMENT '城市',
  `province` varchar(64) DEFAULT NULL COMMENT '省份',
  `country` varchar(64) DEFAULT NULL COMMENT '国家',
  `avatar_url` varchar(255) DEFAULT NULL COMMENT '头像',
  `create_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;