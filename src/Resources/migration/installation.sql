CREATE TABLE `__prefix__AcademicYear` (
                                                    `id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
                                                    `name` varchar(9) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                    `status` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Upcoming',
                                                    `firstDay` date DEFAULT NULL COMMENT '(DC2Type:date_immutable)',
                                                    `lastDay` date DEFAULT NULL COMMENT '(DC2Type:date_immutable)',
                                                    `sequenceNumber` int(3) DEFAULT NULL,
                                                    PRIMARY KEY (`id`),
                                                    UNIQUE KEY `name` (`name`) USING BTREE,
                                                    UNIQUE KEY `sequence` (`sequenceNumber`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE  `__prefix__AcademicYearSpecialDay` (
                                                              `id` int(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
                                                              `type` varchar(14) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                              `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                              `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                                                              `date` date NOT NULL,
                                                              `schoolOpen` time DEFAULT NULL COMMENT '(DC2Type:time_immutable)',
                                                              `schoolStart` time DEFAULT NULL COMMENT '(DC2Type:time_immutable)',
                                                              `schoolEnd` time DEFAULT NULL COMMENT '(DC2Type:time_immutable)',
                                                              `schoolClose` time DEFAULT NULL COMMENT '(DC2Type:time_immutable)',
                                                              `academic_year` int(3) UNSIGNED ZEROFILL DEFAULT NULL,
                                                              PRIMARY KEY (`id`),
                                                              UNIQUE KEY `date` (`date`),
                                                              KEY `academicYear` (`academic_year`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `__prefix__AcademicYearTerm` (
                                                        `id` int(5) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
                                                        `sequenceNumber` int(5) DEFAULT NULL,
                                                        `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                        `nameShort` varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                        `firstDay` date DEFAULT NULL COMMENT '(DC2Type:date_immutable)',
                                                        `lastDay` date DEFAULT NULL COMMENT '(DC2Type:date_immutable)',
                                                        `academic_year` int(3) UNSIGNED ZEROFILL DEFAULT NULL,
                                                        PRIMARY KEY (`id`),
                                                        UNIQUE KEY `name` (`academic_year`,`name`) USING BTREE,
                                                        UNIQUE KEY `abbr` (`academic_year`,`nameShort`),
                                                        UNIQUE KEY `sequenceNumber` (`academic_year`,`sequenceNumber`) USING BTREE,
                                                        KEY `academicYear` (`academic_year`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `__prefix__AlertLevel` (
                                                  `id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
                                                  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                  `nameShort` varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                  `colour` varchar(7) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'RGB Hex',
                                                  `colour_bg` varchar(7) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'RGB Hex',
                                                  `description` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                  `sequenceNumber` int(3) DEFAULT NULL,
                                                  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `__prefix__AttendanceCode` (
                                                      `id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
                                                      `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                      `code` varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                      `type` varchar(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                      `direction` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                      `scope` varchar(14) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                      `active` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                      `reportable` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                      `future` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                      `role_list` varchar(90) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:simple_array)',
                                                      `sequence` int(3) DEFAULT NULL,
                                                      PRIMARY KEY (`id`),
                                                      UNIQUE KEY `name` (`name`),
                                                      UNIQUE KEY `code` (`code`) USING BTREE,
                                                      UNIQUE KEY `sequence` (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `__prefix__AttendanceLogCourseClass` (
                                                                `id` int(14) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
                                                                `date` date DEFAULT NULL,
                                                                `timestampTaken` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                                                `course_class` int(8) UNSIGNED ZEROFILL DEFAULT NULL,
                                                                `taker` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
                                                                PRIMARY KEY (`id`),
                                                                KEY `course_class` (`course_class`) USING BTREE,
                                                                KEY `taker` (`taker`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `__prefix__AttendanceLogPerson` (
                                                           `id` int(14) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
                                                           `direction` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                           `type` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                           `reason` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                           `context` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                                                           `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                           `date` date DEFAULT NULL,
                                                           `timestampTaken` datetime DEFAULT NULL,
                                                           `attendance_code` int(3) UNSIGNED ZEROFILL DEFAULT NULL,
                                                           `person` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
                                                           `taker` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
                                                           `course_class` int(8) UNSIGNED ZEROFILL DEFAULT NULL,
                                                           PRIMARY KEY (`id`),
                                                           UNIQUE KEY `dateContextPersonClass` (`date`,`context`,`person`,`course_class`),
                                                           KEY `date` (`date`),
                                                           KEY `dateAndPerson` (`date`,`person`),
                                                           KEY `attendanceCode` (`attendance_code`) USING BTREE,
                                                           KEY `taker` (`taker`) USING BTREE,
                                                           KEY `courseClass` (`course_class`) USING BTREE,
                                                           KEY `person` (`person`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `__prefix__AttendanceLogRollGroup` (
                                                              `id` int(14) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
                                                              `date` date DEFAULT NULL,
                                                              `timestampTaken` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                                              `roll_group` int(5) UNSIGNED ZEROFILL DEFAULT NULL,
                                                              `taker` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
                                                              PRIMARY KEY (`id`),
                                                              KEY `roll_group` (`roll_group`) USING BTREE,
                                                              KEY `taker` (`taker`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `__prefix__DaysOfWeek` (
                                                  `id` int(2) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
                                                  `name` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                  `nameShort` varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                  `sequenceNumber` int(2) DEFAULT NULL,
                                                  `schoolDay` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y',
                                                  `schoolOpen` time DEFAULT NULL COMMENT '(DC2Type:time_immutable)',
                                                  `schoolStart` time DEFAULT NULL COMMENT '(DC2Type:time_immutable)',
                                                  `schoolEnd` time DEFAULT NULL COMMENT '(DC2Type:time_immutable)',
                                                  `schoolClose` time DEFAULT NULL COMMENT '(DC2Type:time_immutable)',
                                                  PRIMARY KEY (`id`),
                                                  UNIQUE KEY `name` (`name`,`nameShort`),
                                                  UNIQUE KEY `nameShort` (`nameShort`),
                                                  UNIQUE KEY `sequenceNumber` (`sequenceNumber`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `__prefix__ExternalAssessment` (
                                                          `id` int(4) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
                                                          `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                          `nameShort` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                          `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                          `website` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                                                          `active` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                          `allowFileUpload` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
                                                          PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `__prefix__ExternalAssessmentField` (
                                                               `id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
                                                               `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                               `category` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                               `sort_order` int(4) DEFAULT NULL,
                                                               `year_group_list` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(DC2Type:simple_array)',
                                                               `external_assessment` int(4) UNSIGNED ZEROFILL DEFAULT NULL,
                                                               `scale` int(5) UNSIGNED ZEROFILL DEFAULT NULL,
                                                               PRIMARY KEY (`id`),
                                                               UNIQUE KEY `name_category` (`name`,`category`) USING BTREE,
                                                               UNIQUE KEY `category_order_scale` (`category`,`sort_order`,`scale`),
                                                               KEY `external_assessment` (`external_assessment`) USING BTREE,
                                                               KEY `scale` (`scale`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `__prefix__ExternalAssessmentStudent` (
                                                                 `id` int(12) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
                                                                 `date` date NOT NULL,
                                                                 `attachment` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                                 `external_assessment` int(4) UNSIGNED ZEROFILL DEFAULT NULL,
                                                                 `person` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
                                                                 PRIMARY KEY (`id`),
                                                                 KEY `person` (`person`),
                                                                 KEY `external_assessment` (`external_assessment`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `__prefix__ExternalAssessmentStudentEntry` (
                                                                      `id` int(14) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
                                                                      `external_assessment_student` int(12) UNSIGNED ZEROFILL DEFAULT NULL,
                                                                      `external_assessment_field` int(6) UNSIGNED ZEROFILL DEFAULT NULL,
                                                                      `scale_grade` int(7) UNSIGNED ZEROFILL DEFAULT NULL,
                                                                      PRIMARY KEY (`id`),
                                                                      KEY `external_assessment_student` (`external_assessment_student`) USING BTREE,
                                                                      KEY `external_assessment_field` (`external_assessment_field`) USING BTREE,
                                                                      KEY `scale_grade` (`scale_grade`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `__prefix__Facility` (
                                                `id` int(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
                                                `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                `type` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                `capacity` int(5) DEFAULT NULL,
                                                `computer` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                `computerStudent` int(3) DEFAULT NULL,
                                                `projector` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                `tv` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                `dvd` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                `hifi` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                `speakers` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                `iwb` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                `phoneInternal` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                                                `phoneExternal` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                                                `comment` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                                                PRIMARY KEY (`id`),
                                                UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `__prefix__FacilityPerson` (
                                                `id` bigint(12) UNSIGNED ZEROFILL AUTO_INCREMENT NOT NULL,
                                                `facility` int(10) UNSIGNED ZEROFILL NOT NULL,
                                                `person` int(10) UNSIGNED ZEROFILL NOT NULL,
                                                `usage_type` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                                                PRIMARY KEY (`id`),
                                                KEY `facility` (`facility`),
                                                KEY `person` (`person`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `__prefix__FileExtension` (
                                                     `id` int(4) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
                                                     `type` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Other',
                                                     `extension` varchar(7) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                     `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                     PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `__prefix__House` (
                                             `id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
                                             `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                             `nameShort` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                             `logo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                                             PRIMARY KEY (`id`),
                                             UNIQUE KEY `name` (`name`) USING BTREE,
                                             UNIQUE KEY `nameShort` (`nameShort`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `__prefix__INArchive` (
                                                 `id` int(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
                                                 `strategies` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                 `targets` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                 `notes` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                 `archiveTitle` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                 `archiveTimestamp` datetime DEFAULT NULL,
                                                 `person` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
                                                 PRIMARY KEY (`id`),
                                                 KEY `person` (`person`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `__prefix__INAssistant` (
                                                   `id` int(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
                                                   `comment` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                   `student` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
                                                   `assistant` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
                                                   PRIMARY KEY (`id`),
                                                   KEY `student` (`student`) USING BTREE,
                                                   KEY `assistant` (`assistant`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `__prefix__INDescriptor` (
                                                    `id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
                                                    `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                    `nameShort` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                    `description` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                                                    `sequenceNumber` int(3) DEFAULT NULL,
                                                    PRIMARY KEY (`id`),
                                                    UNIQUE KEY `name` (`name`),
                                                    UNIQUE KEY `abbr` (`nameShort`),
                                                    UNIQUE KEY `sequence` (`sequenceNumber`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `__prefix__INArchiveDescriptors` (
                                                            `in_descriptor` int(3) NOT NULL,
                                                            `in_archive` int(10) NOT NULL,
                                                            UNIQUE KEY `in_archive_descriptor` (`in_archive`,`in_descriptor`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `__prefix__IN` (
                                          `id` int(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
                                          `strategies` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                          `targets` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                          `notes` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                          `person` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
                                          PRIMARY KEY (`id`),
                                          UNIQUE KEY `person` (`person`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `__prefix__INPersonDescriptor` (
                                                          `id` int(12) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
                                                          `person` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
                                                          `in_descriptor` int(3) UNSIGNED ZEROFILL DEFAULT NULL,
                                                          `alert_level` int(3) UNSIGNED ZEROFILL DEFAULT NULL,
                                                          PRIMARY KEY (`id`),
                                                          KEY `person` (`person`) USING BTREE,
                                                          KEY `alert_level` (`alert_level`) USING BTREE,
                                                          KEY `in_descriptor` (`in_descriptor`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `__prefix__Scale` (
                                             `id` int(5) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
                                             `name` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                             `nameShort` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                             `usage_info` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                             `lowestAcceptable` int(7) UNSIGNED ZEROFILL DEFAULT NULL,
                                             `active` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y',
                                             `is_numeric` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
                                             PRIMARY KEY (`id`),
                                             KEY `lowestAcceptable` (`lowestAcceptable`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `__prefix__ScaleGrade` (
                                                  `id` int(7) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
                                                  `value` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                  `descriptor` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                  `sequenceNumber` int(5) DEFAULT NULL,
                                                  `isDefault` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
                                                  `scale` int(5) UNSIGNED ZEROFILL DEFAULT NULL,
                                                  PRIMARY KEY (`id`),
                                                  UNIQUE KEY `id` (`id`,`value`),
                                                  UNIQUE KEY `scaleValue` (`scale`,`value`) USING BTREE,
                                                  UNIQUE KEY `scaleSequence` (`sequenceNumber`,`scale`),
                                                  KEY `scale` (`scale`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `__prefix__YearGroup` (
                                                 `id` int(3) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
                                                 `name` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                 `nameShort` varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                                 `sequenceNumber` int(3) UNSIGNED NOT NULL,
                                                 `head_of_year` int(10) UNSIGNED ZEROFILL DEFAULT NULL,
                                                 PRIMARY KEY (`id`),
                                                 UNIQUE KEY `name` (`name`),
                                                 UNIQUE KEY `nameShort` (`nameShort`),
                                                 UNIQUE KEY `sequenceNumber` (`sequenceNumber`),
                                                 KEY `headOfYear` (`head_of_year`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
