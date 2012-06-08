CREATE TABLE `mod_glpi_api` (
`glpi_url` VARCHAR( 255 ) NOT NULL ,
`admin_login` VARCHAR( 255 ) NOT NULL ,
`admin_pass` VARCHAR( 255 ) NOT NULL ,
`api_user` VARCHAR( 255 ) NOT NULL ,
`api_pass` VARCHAR( 255 ) NOT NULL,
`clapi_login` VARCHAR( 255 ) NOT NULL,
`clapi_pass` VARCHAR( 255 ) NOT NULL,
`clapi_restart` BOOL DEFAULT '0'
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE `mod_glpi_rules` (
`rule_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`rule_name` VARCHAR( 255 ) NOT NULL ,
`rule_description` TEXT NOT NULL ,
`host_template_id` INT( 11 ) NOT NULL ,
`instance_id` INT( 11 ) NOT NULL ,
`activate` BOOL DEFAULT '1',
PRIMARY KEY (  `rule_id` )
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci;

ALTER TABLE `mod_glpi_rules`
  ADD CONSTRAINT `mod_glpi_rules_ibfk_1` FOREIGN KEY (`host_template_id`) REFERENCES `host` (`host_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mod_glpi_rules_ibfk_2` FOREIGN KEY (`instance_id`) REFERENCES `nagios_server` (`id`) ON DELETE CASCADE;

CREATE TABLE `mod_glpi_matching` (
`rule_id` INT( 11 ) NOT NULL ,
`rule_dropdown_name` VARCHAR( 255 ) NOT NULL ,
`rule_dropdown_value` VARCHAR( 255 ) NOT NULL
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci;

ALTER TABLE `mod_glpi_matching`
  ADD CONSTRAINT `mod_glpi_matching_ibfk_1` FOREIGN KEY (`rule_id`) REFERENCES `mod_glpi_rules` (`rule_id`) ON DELETE CASCADE;

CREATE TABLE `mod_glpi_rule_hg_relations` (
`rule_id` INT( 11 ) NOT NULL ,
`hg_id` INT( 11 ) NOT NULL
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci;

ALTER TABLE `mod_glpi_rule_hg_relations`
  ADD CONSTRAINT `mod_glpi_rule_hg_relations_ibfk_1` FOREIGN KEY (`rule_id`) REFERENCES `mod_glpi_rules` (`rule_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mod_glpi_rule_hg_relations_ibfk_2` FOREIGN KEY (`hg_id`) REFERENCES `hostgroup` (`hg_id`) ON DELETE CASCADE;

CREATE TABLE `mod_glpi_rule_hc_relations` (
`rule_id` INT( 11 ) NOT NULL ,
`hc_id` INT( 11 ) NOT NULL
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci;

ALTER TABLE `mod_glpi_rule_hc_relations`
  ADD CONSTRAINT `mod_glpi_rule_hc_relations_ibfk_1` FOREIGN KEY (`rule_id`) REFERENCES `mod_glpi_rules` (`rule_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mod_glpi_rule_hc_relations_ibfk_2` FOREIGN KEY (`hc_id`) REFERENCES `hostcategories` (`hc_id`) ON DELETE CASCADE;

INSERT INTO `topology` (`topology_name`, `topology_icone`, `topology_parent`, `topology_page`, `topology_order`, `topology_group`, `topology_url`, `topology_modules`, `topology_show`) VALUES 
    ('GLPI', NULL , '601', NULL, '80', '8', NULL, '1', '1'),
    ('Matching rules', './modules/centreon-glpi/img/glpi.gif', '601', '60138', '80', '8', './modules/centreon-glpi/core/rule_configuration/index.php', '1', '1'),
    ('GLPI', NULL , '501', NULL, '80', '8', NULL, '1', '1'),
    ('API', './modules/centreon-glpi/img/glpi.gif', '501', '50138', '80', '8', './modules/centreon-glpi/core/api_configuration/index.php', '1', '1');

INSERT INTO `mod_glpi_api` (`glpi_url`, `api_user`, `api_pass`, `admin_login`, `admin_pass`) VALUES ('', '', '', '', '');

INSERT INTO `command` (`command_name`, `command_line`, `command_type`) VALUES 
    ('glpi-ticket-host', '@NAGIOS_PLUGIN@/glpi-ticket -c @NAGIOS_PLUGIN@/glpi/webservices.ini -t $HOSTSTATETYPE$ -s $HOSTSTATE$ -o "$HOSTOUTPUT$" -h "$HOSTNAME$"', '2'),
    ('glpi-ticket-service', '@NAGIOS_PLUGIN@/glpi-ticket -c @NAGIOS_PLUGIN@/glpi/webservices.ini -t $SERVICESTATETYPE$ -s $SERVICESTATE$ -o "$SERVICEOUTPUT$" -a $HOSTSTATE$ -h "$HOSTNAME$" -e "$SERVICEDESC$"', '2');