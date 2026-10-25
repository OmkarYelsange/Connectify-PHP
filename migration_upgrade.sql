-- Run this once against the EXISTING chatapp_db database from the original ChatApp project.
-- Back up your database first. This migration is for the original schema, not the fresh-install database.sql.
USE chatapp_db;
ALTER TABLE users ADD COLUMN profile_photo VARCHAR(255) NULL AFTER profile_color;
ALTER TABLE users ADD COLUMN role ENUM('user','admin') NOT NULL DEFAULT 'user' AFTER profile_photo;
ALTER TABLE conversations ADD COLUMN conversation_type ENUM('private','group') NOT NULL DEFAULT 'private' AFTER conversation_id;
ALTER TABLE conversations ADD COLUMN title VARCHAR(120) NULL AFTER conversation_type;
ALTER TABLE conversations ADD COLUMN created_by INT UNSIGNED NULL AFTER title;
ALTER TABLE conversation_members ADD COLUMN member_role ENUM('member','admin') NOT NULL DEFAULT 'member' AFTER user_id;
ALTER TABLE conversation_members ADD COLUMN last_read_message_id BIGINT UNSIGNED NOT NULL DEFAULT 0 AFTER joined_at;
ALTER TABLE messages MODIFY message_text TEXT NULL;
ALTER TABLE messages ADD COLUMN message_type ENUM('text','file','image','system') NOT NULL DEFAULT 'text' AFTER message_text;
ALTER TABLE messages ADD COLUMN attachment_path VARCHAR(255) NULL AFTER message_type;
ALTER TABLE messages ADD COLUMN attachment_name VARCHAR(255) NULL AFTER attachment_path;
ALTER TABLE messages ADD COLUMN attachment_size INT UNSIGNED NULL AFTER attachment_name;
ALTER TABLE messages ADD COLUMN reply_to_id BIGINT UNSIGNED NULL AFTER attachment_size;
ALTER TABLE messages ADD COLUMN edited_at DATETIME NULL AFTER sent_at;
ALTER TABLE messages ADD COLUMN deleted_at DATETIME NULL AFTER edited_at;
ALTER TABLE messages ADD COLUMN delivered_at DATETIME NULL AFTER deleted_at;
ALTER TABLE messages ADD COLUMN read_at DATETIME NULL AFTER delivered_at;
ALTER TABLE messages ADD CONSTRAINT fk_messages_reply FOREIGN KEY(reply_to_id) REFERENCES messages(message_id) ON DELETE SET NULL;
CREATE TABLE IF NOT EXISTS typing_status (
 conversation_id INT UNSIGNED NOT NULL, user_id INT UNSIGNED NOT NULL, updated_at DATETIME NOT NULL,
 PRIMARY KEY(conversation_id,user_id),
 CONSTRAINT fk_typing_conv FOREIGN KEY(conversation_id) REFERENCES conversations(conversation_id) ON DELETE CASCADE,
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
-- After migration, promote your own account to administrator:
-- UPDATE users SET role='admin' WHERE username='your_username';
