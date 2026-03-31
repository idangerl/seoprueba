-- =============================================================
-- SEO Engine — Migration v2 (SEO/GEO Production Workflow)
-- Ejecutar DESPUÉS de schema.sql y seed.sql
-- =============================================================

SET NAMES utf8mb4;
SET foreign_key_checks = 0;

-- ─── sites: agregar base_json y campos SEO/GEO ───────────────
ALTER TABLE `sites`
  ADD COLUMN `specialty_label`   VARCHAR(190) NULL AFTER `business_name`,
  ADD COLUMN `base_json`         JSON         NULL AFTER `specialty_label`,
  ADD COLUMN `base_json_version` INT UNSIGNED NOT NULL DEFAULT 1 AFTER `base_json`,
  ADD COLUMN `base_json_hash`    CHAR(40)     NULL AFTER `base_json_version`;

-- ─── projects: convertir en "ronda" con soporte CSV ──────────
ALTER TABLE `projects`
  ADD COLUMN `round_code`       VARCHAR(60)  NULL AFTER `code`,
  ADD COLUMN `source_csv_name`  VARCHAR(255) NULL AFTER `round_code`,
  ADD COLUMN `source_csv_hash`  CHAR(40)     NULL AFTER `source_csv_name`,
  ADD COLUMN `source_rows_count`INT UNSIGNED NOT NULL DEFAULT 0 AFTER `source_csv_hash`,
  ADD COLUMN `level1_count`     INT UNSIGNED NOT NULL DEFAULT 0 AFTER `source_rows_count`,
  ADD COLUMN `level2_count`     INT UNSIGNED NOT NULL DEFAULT 0 AFTER `level1_count`,
  ADD COLUMN `map_json`         JSON         NULL AFTER `level2_count`,
  ADD COLUMN `strategy_year`    SMALLINT UNSIGNED NOT NULL DEFAULT 2026 AFTER `map_json`;

-- ─── tasks: extender al modelo SEO/GEO completo ──────────────

-- Primero cambiar el status ENUM al catálogo completo
ALTER TABLE `tasks`
  MODIFY COLUMN `status` ENUM(
    'draft','pending_assignment','assigned','in_progress',
    'partial_loaded','submitted','in_review','changes_requested',
    'approved','exported','blocked','cancelled'
  ) NOT NULL DEFAULT 'pending_assignment';

-- Agregar campos SEO/GEO
ALTER TABLE `tasks`
  ADD COLUMN `source_row_number`          INT UNSIGNED NULL AFTER `task_key`,
  ADD COLUMN `question_focus_h1`          VARCHAR(255) NULL AFTER `transactional_json`,
  ADD COLUMN `content_status`             ENUM('pending','valid','invalid') NOT NULL DEFAULT 'pending' AFTER `progress_pct`,
  ADD COLUMN `faqs_status`                ENUM('pending','valid','invalid') NOT NULL DEFAULT 'pending' AFTER `content_status`,
  ADD COLUMN `final_status`               ENUM('pending','assembled','valid','invalid','exported') NOT NULL DEFAULT 'pending' AFTER `faqs_status`,
  ADD COLUMN `effort_points`              DECIMAL(5,2) NOT NULL DEFAULT 1.00 AFTER `final_status`,
  ADD COLUMN `assigned_by`               VARCHAR(20)  NULL AFTER `assigned_user_id`,
  ADD COLUMN `assigned_at`               DATETIME     NULL AFTER `assigned_by`,
  ADD COLUMN `started_at`                DATETIME     NULL AFTER `assigned_at`,
  ADD COLUMN `submitted_at`              DATETIME     NULL AFTER `started_at`,
  ADD COLUMN `reviewed_by`               VARCHAR(20)  NULL AFTER `submitted_at`,
  ADD COLUMN `reviewed_at`               DATETIME     NULL AFTER `reviewed_by`,
  ADD COLUMN `approved_at`               DATETIME     NULL AFTER `reviewed_at`,
  ADD COLUMN `exported_at`               DATETIME     NULL AFTER `approved_at`,
  ADD COLUMN `last_validation_errors`    JSON         NULL AFTER `exported_at`,
  ADD COLUMN `last_notes`               TEXT          NULL AFTER `last_validation_errors`;

-- ─── json_outputs: extender con prompt snapshot ───────────────
ALTER TABLE `json_outputs`
  MODIFY COLUMN `output_kind` ENUM('content','faqs','final') NOT NULL,
  MODIFY COLUMN `validation_status` ENUM('valid','invalid','pending') NOT NULL DEFAULT 'pending',
  ADD COLUMN `prompt_snapshot`        LONGTEXT     NULL AFTER `prompt_template_version_id`,
  ADD COLUMN `is_valid`               TINYINT(1)   NOT NULL DEFAULT 0 AFTER `checksum`,
  ADD COLUMN `validation_errors_json` JSON         NULL AFTER `is_valid`,
  ADD COLUMN `submitted_by`           VARCHAR(20)  NOT NULL DEFAULT '' AFTER `validation_errors_json`,
  ADD COLUMN `submitted_at`           DATETIME     NULL AFTER `submitted_by`;

-- ─── seo_geo_exports: artefactos exportados al filesystem ─────
CREATE TABLE IF NOT EXISTS `seo_geo_exports` (
  `id`               VARCHAR(20)  NOT NULL,
  `task_id`          VARCHAR(20)  NOT NULL,
  `export_kind`      ENUM('json_final','map_json') NOT NULL DEFAULT 'json_final',
  `file_name`        VARCHAR(500) NOT NULL,
  `relative_path`    VARCHAR(500) NOT NULL,
  `checksum_sha1`    CHAR(40)     NOT NULL,
  `file_size_bytes`  BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `exported_by`      VARCHAR(20)  NOT NULL,
  `exported_at`      DATETIME     NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_exports_task` (`task_id`, `exported_at`),
  CONSTRAINT `fk_exp_task` FOREIGN KEY (`task_id`) REFERENCES `tasks`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET foreign_key_checks = 1;

-- ─── Actualizar prompt_templates para los 4 códigos SEO/GEO ──
-- (Los templates reales se insertan desde seed_prompts.sql)
UPDATE `prompt_templates` SET `code` = code; -- no-op, solo verificar que existe la tabla
