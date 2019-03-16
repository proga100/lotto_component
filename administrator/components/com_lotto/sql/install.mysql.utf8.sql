CREATE TABLE IF NOT EXISTS `#__lotto_users` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`name` VARCHAR(255)  NOT NULL ,
`tickets` VARCHAR(255)  NOT NULL ,
`balance` VARCHAR(255)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;



CREATE TABLE IF NOT EXISTS `#__lotto_bonuses` (
  `id` int(11) NOT NULL,
  `unique_number` varchar(255) NOT NULL,
  `amount` int(255) NOT NULL,
  `created_date` date NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `#__lotto_tickets` (
  `tickets_id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `tickets_tiraj_numbers` int(255) NOT NULL,
  `numbers` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `#__lotto_tickets`
  ADD PRIMARY KEY (`tickets_id`),
  ADD UNIQUE KEY `tickets_id` (`tickets_id`),
  ADD KEY `tickets_id_2` (`tickets_id`);


ALTER TABLE `#__lotto_tickets`
  MODIFY `tickets_id` int(255) NOT NULL AUTO_INCREMENT;
COMMIT;

ALTER TABLE `#__lotto_tickets` ADD `price` VARCHAR(255) NOT NULL AFTER `created_data`;

ALTER TABLE `#__lotto_tickets` ADD `additional_numbers` MEDIUMTEXT NOT NULL AFTER `numbers`;

CREATE TABLE `#__lotto_tiraje` (
  `id` int(11) NOT NULL,
  `Tiraje_number` int(11) NOT NULL,
  `selection_numbers` varchar(1024) NOT NULL,
  `ticket_total_numbers` int(11) NOT NULL,
  `additional_selection_numbers` int(11) NOT NULL,
  `additional_ticket_total_numbers` int(11) NOT NULL,
  `playing date` date NOT NULL,
  `ticket_price` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `#__lotto_tiraje`   ADD PRIMARY KEY (`id`),   ADD UNIQUE KEY `Tiraje_number` (`Tiraje_number`);


ALTER TABLE `#__lotto_tiraje`   MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE `#__lotto_winning_numbers` (
  `id` int(11) NOT NULL,
  `tiraje_number` int(11) NOT NULL,
  `main_winning_numbers` varchar(255) NOT NULL,
  `additional_winning_numbers` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `#__lotto_winning_numbers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tiraje_number` (`tiraje_number`);


ALTER TABLE `#__lotto_winning_numbers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__lotto_winning_numbers` ADD `winning_prize` VARCHAR(1024) NOT NULL AFTER `additional_winning_numbers`;

ALTER TABLE `#__lotto_winning_numbers` ADD `winning_prize` VARCHAR(1024) NOT NULL AFTER `additional_winning_numbers`;

ALTER TABLE `#__lotto_tiraje`    ADD `playing_date` date NOT NULL AFTER `additional_ticket_total_numbers`;

CREATE TABLE `#__lotto_freekassapayments` (
  `id` int(11) NOT NULL,
  `merchant_id` varchar(255) NOT NULL,
  `merchant_key` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `#__lotto_freekassapayments`   ADD UNIQUE KEY `id` (`id`);

ALTER TABLE `#__lotto_tickets ADD `played` VARCHAR(256) NOT NULL AFTER `price`;


