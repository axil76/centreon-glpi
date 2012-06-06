DROP TABLE `mod_glpi_api`;

DROP TABLE `mod_glpi_rule_hc_relations`;

DROP TABLE `mod_glpi_rule_hg_relations`;

DROP TABLE `mod_glpi_matching`;

DROP TABLE `mod_glpi_rules`;

DELETE FROM `topology` WHERE `topology_name` = 'GLPI';

DELETE FROM `topology` WHERE `topology_name` = 'Matching rules' AND `topology_page` = '60138';

DELETE FROM `topology` WHERE `topology_name` = 'API' AND `topology_page` = '50138';

DELETE FROM `command` WHERE `command_name` LIKE 'glpi-ticket%';