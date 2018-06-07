-- ----------------------------
-- 小程序的配置信息
-- ----------------------------
DROP TABLE IF EXISTS `cms_wxapp_appinfo`;
CREATE TABLE `cms_wxapp_appinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appid` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'appid',
  `secret` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '小程序秘钥',
  `login_duration` int(11) NOT NULL DEFAULT '7200',
  `session_duration` int(11) NOT NULL DEFAULT '2592000' COMMENT 'session存储时长',
  `secret_key` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'open平台的secret_key',
  `ip` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0.0.0.0',
  `mch_id` varchar(255) NOT NULL DEFAULT '' COMMENT '微信支付商户号',
  `key` varchar(255) NOT NULL DEFAULT '' COMMENT '微信支付key',
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否默认',
  `nick_name` varchar(32) NOT NULL DEFAULT '' COMMENT '小程序名称',
  `head_img` varchar(256) NOT NULL DEFAULT '' COMMENT '小程序头像',
  `principal_name` varchar(32) NOT NULL DEFAULT '' COMMENT '主体信息',
  `access_token` varchar(256) DEFAULT '',
  `expires_in` int(11) DEFAULT '0',
  `get_access_token_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `appid` varchar(200) DEFAULT NULL COMMENT '所属appid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4;


-- ----------------------------
-- 小程序提交代码记录
-- ----------------------------
DROP TABLE IF EXISTS `cms_wxapp_commit`;
CREATE TABLE `cms_wxapp_commit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `appid` varchar(200) NOT NULL DEFAULT '' COMMENT '所属的公众appid',
  `template_id` int(11) NOT NULL,
  `user_version` varchar(255) NOT NULL DEFAULT '',
  `user_desc` varchar(255) NOT NULL DEFAULT '',
  `ext_json` text NOT NULL,
  `create_time` int(11) NOT NULL COMMENT '提交时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ----------------------------
-- 小程序提交审核记录
-- ----------------------------
DROP TABLE IF EXISTS `cms_wxapp_audit`;
CREATE TABLE `cms_wxapp_audit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `appid` varchar(200) NOT NULL DEFAULT '' COMMENT '所属公众号appid',
  `auditid` int(11) NOT NULL COMMENT '提交审核id',
  `create_time` int(11) NOT NULL COMMENT '提交时间',
  `status` int(11) NOT NULL COMMENT '审核状态，其中0为审核成功，1为审核失败，2为审核中',
  `reason` varchar(255) DEFAULT NULL COMMENT '当status=1，审核被拒绝时，返回的拒绝原因',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `is_release` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发布',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- 微信支付记录
-- ----------------------------
DROP TABLE IF EXISTS `cms_wxapp_pay_order`;
CREATE TABLE `cms_wxapp_pay_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `return_code` varchar(255) DEFAULT NULL COMMENT '调用结果',
  `return_msg` varchar(255) DEFAULT NULL COMMENT '调用信息',
  `appid` varchar(128) DEFAULT NULL COMMENT 'app_id',
  `mch_id` varchar(128) DEFAULT NULL COMMENT '商户id',
  `nonce_str` varchar(32) DEFAULT NULL COMMENT '随机码',
  `sign` varchar(255) DEFAULT NULL COMMENT '签名',
  `result_code` varchar(255) DEFAULT NULL COMMENT '业务代码',
  `openid` varchar(255) DEFAULT NULL COMMENT '用户openid',
  `is_subscribe` varchar(16) DEFAULT NULL COMMENT '是否关注',
  `trade_type` varchar(32) DEFAULT NULL COMMENT '交易类型',
  `bank_type` varchar(32) DEFAULT NULL COMMENT '银行',
  `total_fee` int(11) DEFAULT NULL COMMENT '交易总额',
  `fee_type` varchar(255) DEFAULT NULL COMMENT '钱币类型',
  `transaction_id` varchar(255) DEFAULT NULL COMMENT '流水号',
  `out_trade_no` varchar(255) DEFAULT NULL COMMENT '订单号',
  `attach` varchar(255) DEFAULT NULL COMMENT '附加值',
  `time_end` varchar(128) DEFAULT NULL COMMENT '结束时间',
  `trade_state` varchar(255) DEFAULT NULL COMMENT '交易状态',
  `trade_state_desc` varchar(255) DEFAULT NULL COMMENT '交易解释',
  `cash_fee` int(11) DEFAULT NULL COMMENT '现金金额',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- 微信支付退款记录
-- ----------------------------
DROP TABLE IF EXISTS `cms_wxapp_pay_refund`;
CREATE TABLE `cms_wxapp_pay_refund` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `appid` varchar(255) DEFAULT '',
  `cash_fee` int(11) NOT NULL DEFAULT '0',
  `cash_refund_fee` int(11) NOT NULL DEFAULT '0',
  `coupon_refund_count` int(11) NOT NULL DEFAULT '0',
  `coupon_refund_fee` int(11) NOT NULL DEFAULT '0',
  `mch_id` int(11) NOT NULL DEFAULT '0',
  `nonce_str` varchar(255) DEFAULT '',
  `out_refund_no` varchar(255) DEFAULT '',
  `out_trade_no` varchar(255) DEFAULT '',
  `refund_channel` varchar(255) DEFAULT '',
  `refund_fee` int(11) NOT NULL DEFAULT '0',
  `refund_id` varchar(255) DEFAULT '',
  `result_code` varchar(32) DEFAULT '',
  `return_code` varchar(32) DEFAULT '',
  `return_msg` varchar(255) DEFAULT '',
  `sign` varchar(255) DEFAULT '',
  `total_fee` int(11) NOT NULL DEFAULT '0',
  `transaction_id` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;