# PROJETO TRILHA 1

## BANCO DE DADOS

``CREATE TABLE `users` (
 `id` int(10) NOT NULL AUTO_INCREMENT,
 `email` varchar(255) NOT NULL,
 `password` varchar(255) NOT NULL,
 `name` varchar(255) NOT NULL,
 `identification` varchar(255) NOT NULL,
 `registration` varchar(255) NOT NULL,
 `birth_date` varchar(255) NOT NULL,
 `created_at` timestamp NULL DEFAULT NULL,
 `updated_at` timestamp NULL DEFAULT NULL,
 `deleted_at` timestamp NULL DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;``


``CREATE TABLE `user_phone` (
 `id` int(10) NOT NULL AUTO_INCREMENT,
 `user_id` int(10) NOT NULL,
 `phone` varchar(255) NOT NULL,
 `created_at` timestamp NULL DEFAULT NULL,
 `updated_at` timestamp NULL DEFAULT NULL,
 `deleted_at` timestamp NULL DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;``


``CREATE TABLE `user_address` (
 `id` int(10) NOT NULL AUTO_INCREMENT,
 `user_id` int(10) NOT NULL,
 `cep` varchar(255) NOT NULL,
 `address` varchar(255) NOT NULL,
 `number` varchar(255) NOT NULL,
 `reference` varchar(255) NOT NULL,
 `observation` varchar(255) not NULL,
 `created_at` timestamp NULL DEFAULT NULL,
 `updated_at` timestamp NULL DEFAULT NULL,
 `deleted_at` timestamp NULL DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;``


``CREATE TABLE `user_account` (
 `id` int(10) NOT NULL AUTO_INCREMENT,
 `user_id` int(10) NOT NULL,
 `account_number` varchar(255) NOT NULL,
 `funds` varchar(255) NOT NULL,
 `created_at` timestamp NULL DEFAULT NULL,
 `updated_at` timestamp NULL DEFAULT NULL,
 `deleted_at` timestamp NULL DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;``


``CREATE TABLE `account_transactions` (
 `id` int(10) NOT NULL AUTO_INCREMENT,
 `account_origin_id` int(10) NOT NULL,
 `account_destination_id` int(10) NOT NULL,
 `value` varchar(255) NOT NULL,
 `transaction_type` varchar(255) NOT NULL,
 `transaction_date` varchar(255) NOT NULL,
 `created_at` timestamp NULL DEFAULT NULL,
 `updated_at` timestamp NULL DEFAULT NULL,
 `deleted_at` timestamp NULL DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;``


`ALTER TABLE user_phone ADD CONSTRAINT FK_userIdPhone FOREIGN KEY (user_id) REFERENCES users(id);`


`ALTER TABLE user_address ADD CONSTRAINT FK_userIdAddress FOREIGN KEY (user_id) REFERENCES users(id);`


`ALTER TABLE user_account ADD CONSTRAINT FK_userIdAccount FOREIGN KEY (user_id) REFERENCES users(id);`


`ALTER TABLE account_transactions ADD CONSTRAINT FK_accountOrigin FOREIGN KEY (account_origin_id) REFERENCES user_account(id);`


`ALTER TABLE account_transactions ADD CONSTRAINT FK_accountDestination FOREIGN KEY (account_destination_id) REFERENCES user_account(id);`




