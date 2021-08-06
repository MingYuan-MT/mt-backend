CREATE TABLE `room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `floor` int(2) NOT NULL COMMENT '楼层',
  `building_id` int(2) NOT NULL COMMENT '楼栋id',
  `region_id` int(5) NOT NULL COMMENT '区域id',
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '会议室名称',
  `capacity` int(4) NOT NULL COMMENT '可容纳人数',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '会议室状态：0 可预定 1已预定 2封闭(不可用或者其他原因不可预定)',
  `projection_mode` tinyint(1) NOT NULL DEFAULT '0' COMMENT '投屏设备：0无 1电视 2投影 3电子屏',
  `remark` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '会议室备注',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modified_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='会议室表';

CREATE TABLE `building` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT ' ',
  `region_id` int(11) NOT NULL COMMENT '地域id',
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '楼栋名称',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modified_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='办公楼栋信息表';

CREATE TABLE `coordinate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '坐标编码',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '坐标名称',
  `roomId` int(11) DEFAULT NULL COMMENT '会议室ID',
  `floor` int(11) DEFAULT NULL COMMENT '会议室楼层',
  `type` int(11) DEFAULT NULL COMMENT '类型：1-会议室坐标;2-信号基站坐标',
  `x_Axis` decimal(18,4) DEFAULT NULL COMMENT 'X轴坐标值',
  `y_Axis` decimal(18,4) DEFAULT NULL COMMENT 'Y轴坐标值',
  `env_factors` decimal(18,4) DEFAULT NULL COMMENT '环境因子',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='坐标表';

CREATE TABLE `joiner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `metting_id` int(11) NOT NULL COMMENT '会议id',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `user_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '姓名',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户类型：1主持人 2参会者',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '参会结果：0未参加 1参加',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modified_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='会议参加人员表';

CREATE TABLE `metting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '会议主题',
  `moderator` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '会议主持人',
  `metting_strat_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '会议开始时间',
  `metting_end_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '会议结束时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '会议状态：0未开始 1进行中 2已结束 3取消',
  `is_deleted` tinyint(1) NOT NULL,
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modified_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modified_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='会议表';

CREATE TABLE `notice_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '通知人id',
  `receiver_user_id` int(11) NOT NULL COMMENT '被通知人',
  `message` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '通知内容文案',
  `type` tinyint(1) NOT NULL COMMENT '通知类型：1 参加会议通知 2会议结束提醒',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modified_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='消息通知记录表';

CREATE TABLE `region` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `parent_id` int(10) NOT NULL DEFAULT '0' COMMENT '父id',
  `tier` tinyint(1) NOT NULL DEFAULT '0' COMMENT '层级',
  `level` tinyint(1) NOT NULL DEFAULT '0' COMMENT '层级',
  `area_name` varchar(50) COLLATE utf8mb4_bin NOT NULL COMMENT '地区名称',
  `spell` varchar(50) COLLATE utf8mb4_bin NOT NULL DEFAULT '' COMMENT '地区拼音',
  `area_code` varchar(50) COLLATE utf8mb4_bin NOT NULL DEFAULT '' COMMENT '地区编码',
  `postal_code` char(6) COLLATE utf8mb4_bin NOT NULL DEFAULT '' COMMENT '邮政编码',
  `baidu_code` char(32) COLLATE utf8mb4_bin NOT NULL DEFAULT '' COMMENT '百度地址编码',
  `jingdong_code` char(32) COLLATE utf8mb4_bin NOT NULL DEFAULT '' COMMENT '京东地址编码',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `created_by` varchar(40) COLLATE utf8mb4_bin DEFAULT '' COMMENT '创建人',
  `modified_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改人',
  `modified_by` varchar(40) COLLATE utf8mb4_bin DEFAULT '' COMMENT '修改人',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除(1-已删除 0-未删除)',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_parent` (`parent_id`) USING BTREE,
  KEY `idx_level` (`area_code`,`baidu_code`,`jingdong_code`) USING BTREE,
  KEY `cl_area_area_name_index` (`area_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=56215 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin ROW_FORMAT=COMPACT COMMENT='地区列表';

CREATE TABLE `reserve_record` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '预定人id',
  `user_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '预定人姓名',
  `date` datetime NOT NULL COMMENT '预定时间',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '预定模式：0预定 1抢占',
  `room_id` int(11) NOT NULL COMMENT '会议室id',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modified_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='会议室预定记录表';

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '姓名',
  `id_number` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '工号',
  `mobile` int(11) DEFAULT NULL COMMENT '号码',
  `seat_number` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unionid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '企业微信用户唯一标识id',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modified_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_by` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户表';