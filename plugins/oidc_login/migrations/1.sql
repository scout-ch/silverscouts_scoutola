DROP TABLE IF EXISTS /*TABLE_PREFIX*/t_oidc_identities;

CREATE TABLE /*TABLE_PREFIX*/t_oidc_identities (
  s_provider VARCHAR(255) NOT NULL,
  s_sub VARCHAR(255) NOT NULL,
  s_email VARCHAR(100) NOT NULL,
  fk_i_user_id INT(10) UNSIGNED NULL,
  dt_created_at TIMESTAMP,
  dt_updated_at TIMESTAMP,
  PRIMARY KEY (s_provider, s_sub),
  FOREIGN KEY (fk_i_user_id) REFERENCES /*TABLE_PREFIX*/t_user (pk_i_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci';
