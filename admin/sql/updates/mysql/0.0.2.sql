ALTER TABLE `#__siak_user` ADD `role` TINYINT UNSIGNED NOT NULL DEFAULT 0 AFTER `user_id`; -- 0 => MHS, 1=>PEGAWAI
ALTER TABLE `#__siak_user` ADD `aktif` TINYINT UNSIGNED NOT NULL DEFAULT 1 AFTER `role`; -- 1 => OK,0 => LOCK
ALTER TABLE `#__siak_user` ADD `status` TINYINT NOT NULL DEFAULT 1 AFTER `aktif`; -- 0 => OFF, 1 => AKTIF