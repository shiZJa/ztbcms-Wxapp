-- ----------------------------
-- 小程序的配置信息
-- ----------------------------
DROP TABLE IF EXISTS `cms_wxapp_appinfo`;
CREATE TABLE `cms_wxapp_appinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appid` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'appid',
  `secret` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '小程序秘钥',
  `login_duration` int(11) DEFAULT '30',
  `session_duration` int(11) DEFAULT '2592000' COMMENT 'session存储时长',
  `secret_key` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'appid_qcloud' COMMENT 'open平台的secret_key',
  `ip` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '0.0.0.0',
  `mch_id` varchar(255) DEFAULT NULL COMMENT '微信支付商户号',
  `key` varchar(255) DEFAULT NULL COMMENT '微信支付key',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- 小程序用户相关信息
-- ----------------------------
DROP TABLE IF EXISTS `cms_wxapp_sessioninfo`;
CREATE TABLE `cms_wxapp_sessioninfo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `skey` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_time` datetime NOT NULL,
  `last_visit_time` datetime NOT NULL,
  `open_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `session_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_info` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `appid` varchar(200) NOT NULL DEFAULT '' COMMENT '所属的appid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会话管理用户信息';


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

-- ----------------------------
-- 小程序发送模板消息触发的来源
-- ----------------------------
DROP TABLE IF EXISTS `cms_wxapp_template_from`;
CREATE TABLE `cms_wxapp_template_from` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) NOT NULL DEFAULT '' COMMENT '发送模板的用户openid',
  `form_id` varchar(255) NOT NULL DEFAULT '' COMMENT '来源id',
  `from_type` varchar(64) NOT NULL DEFAULT '' COMMENT '来源类型',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `send_count` tinyint(1) NOT NULL DEFAULT '0' COMMENT '已经发送次数,支付类型可以发送3次',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;