CREATE TABLE `logs_cmd` (
  `id` int(11) NOT NULL,
  `uid` bigint(20) NOT NULL,
  `gid` bigint(20) NOT NULL,
  `command` text COLLATE utf8mb4_bin NOT NULL,
  `args` text COLLATE utf8mb4_bin NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

ALTER TABLE `logs_cmd`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);

  ALTER TABLE `logs_cmd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `uid` varchar(200) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `first_name` text COLLATE utf8mb4_bin,
  `last_name` text COLLATE utf8mb4_bin,
  `username` text COLLATE utf8mb4_bin,
  `language_code` varchar(10) COLLATE utf8mb4_bin DEFAULT NULL,
  `start` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uid` (`uid`),
  ADD KEY `uid_2` (`uid`);

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;


CREATE TABLE `logs_callback` (
  `id` int(11) NOT NULL,
  `uid` bigint(20) NOT NULL,
  `gid` bigint(20) NOT NULL,
  `message_id` bigint(20) NOT NULL,
  `original_command` text COLLATE utf8mb4_bin NOT NULL,
  `original_message_id` bigint(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

ALTER TABLE `logs_callback`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `logs_callback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
