CREATE DATABASE IF NOT EXISTS chatapp_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE chatapp_db;
CREATE TABLE IF NOT EXISTS users (
 user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, full_name VARCHAR(100) NOT NULL,
 username VARCHAR(40) NOT NULL UNIQUE, email VARCHAR(150) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL, profile_color VARCHAR(20) NOT NULL DEFAULT '#7c3aed',
 profile_photo VARCHAR(255) NULL, role ENUM('user','admin') NOT NULL DEFAULT 'user',
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, last_seen DATETIME NULL,
 status ENUM('active','blocked') NOT NULL DEFAULT 'active', INDEX idx_users_name(full_name), INDEX idx_users_username(username)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS conversations (
 conversation_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 conversation_type ENUM('private','group') NOT NULL DEFAULT 'private', title VARCHAR(120) NULL,
 created_by INT UNSIGNED NULL, created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_conv_creator FOREIGN KEY(created_by) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS conversation_members (
 conversation_id INT UNSIGNED NOT NULL, user_id INT UNSIGNED NOT NULL,
 member_role ENUM('member','admin') NOT NULL DEFAULT 'member', joined_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 last_read_message_id BIGINT UNSIGNED NOT NULL DEFAULT 0, PRIMARY KEY(conversation_id,user_id),
 CONSTRAINT fk_cm_conversation FOREIGN KEY(conversation_id) REFERENCES conversations(conversation_id) ON DELETE CASCADE,
 CONSTRAINT fk_cm_user FOREIGN KEY(user_id) REFERENCES users(user_id) ON DELETE CASCADE, INDEX idx_cm_user(user_id)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS messages (
 message_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, conversation_id INT UNSIGNED NOT NULL,
 sender_id INT UNSIGNED NOT NULL, message_text TEXT NULL,
 message_type ENUM('text','file','image','system') NOT NULL DEFAULT 'text', attachment_path VARCHAR(255) NULL,
 attachment_name VARCHAR(255) NULL, attachment_size INT UNSIGNED NULL,
 reply_to_id BIGINT UNSIGNED NULL, sent_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 edited_at DATETIME NULL, deleted_at DATETIME NULL, delivered_at DATETIME NULL, read_at DATETIME NULL,
 is_read TINYINT(1) NOT NULL DEFAULT 0,
 CONSTRAINT fk_messages_conversation FOREIGN KEY(conversation_id) REFERENCES conversations(conversation_id) ON DELETE CASCADE,
 CONSTRAINT fk_messages_sender FOREIGN KEY(sender_id) REFERENCES users(user_id) ON DELETE CASCADE,
 CONSTRAINT fk_messages_reply FOREIGN KEY(reply_to_id) REFERENCES messages(message_id) ON DELETE SET NULL,
 INDEX idx_messages_conversation_id(conversation_id,message_id), INDEX idx_messages_sender(sender_id)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS typing_status (
 conversation_id INT UNSIGNED NOT NULL, user_id INT UNSIGNED NOT NULL, updated_at DATETIME NOT NULL,
 PRIMARY KEY(conversation_id,user_id), CONSTRAINT fk_typing_conv FOREIGN KEY(conversation_id) REFERENCES conversations(conversation_id) ON DELETE CASCADE,
 CONSTRAINT fk_typing_user FOREIGN KEY(user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS notifications (
 notification_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, user_id INT UNSIGNED NOT NULL,
 conversation_id INT UNSIGNED NULL, message_id BIGINT UNSIGNED NULL, notification_type VARCHAR(30) NOT NULL DEFAULT 'message',
 body VARCHAR(255) NOT NULL, is_read TINYINT(1) NOT NULL DEFAULT 0, created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_notification_user FOREIGN KEY(user_id) REFERENCES users(user_id) ON DELETE CASCADE,
 CONSTRAINT fk_notification_conv FOREIGN KEY(conversation_id) REFERENCES conversations(conversation_id) ON DELETE CASCADE,
 CONSTRAINT fk_notification_message FOREIGN KEY(message_id) REFERENCES messages(message_id) ON DELETE CASCADE,
 INDEX idx_notifications_user(user_id,is_read,created_at)
) ENGINE=InnoDB;
