-- ═══════════════════════════════════════════════════════════════
--  TaquerosWeb — Schema v1.0
--  MySQL 8.0+ / utf8mb4_unicode_ci
-- ═══════════════════════════════════════════════════════════════

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ── clientes ────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `clientes` (
  `id`                  INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `nombre`              VARCHAR(100)     NOT NULL,
  `apellidos`           VARCHAR(150)     NOT NULL,
  `telefono`            VARCHAR(20)      NOT NULL,
  `email`               VARCHAR(255)     NOT NULL,
  `password_hash`       VARCHAR(255)     NOT NULL,
  `verification_token`  VARCHAR(128)     NULL DEFAULT NULL,
  `email_verified_at`   DATETIME         NULL DEFAULT NULL,
  `last_login_at`       DATETIME         NULL DEFAULT NULL,
  `created_at`          DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`          DATETIME         NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_email` (`email`),
  INDEX `idx_verification_token` (`verification_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── paquetes ────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `paquetes` (
  `id`          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `emoji`       VARCHAR(8)       NOT NULL DEFAULT '🌮',
  `nombre`      VARCHAR(120)     NOT NULL,
  `tagline`     VARCHAR(200)     NULL,
  `precio`      DECIMAL(10,2)    NOT NULL,
  `entrega`     VARCHAR(80)      NULL COMMENT 'Ej: 3 días hábiles',
  `activo`      TINYINT(1)       NOT NULL DEFAULT 1,
  `orden`       SMALLINT         NOT NULL DEFAULT 0 COMMENT 'Display order',
  `created_at`  DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_activo` (`activo`, `orden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── ordenes ─────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `ordenes` (
  `id`                INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `cliente_id`        INT UNSIGNED     NOT NULL,
  `paquete_id`        INT UNSIGNED     NOT NULL,
  `descripcion`       TEXT             NOT NULL,
  `estado`            ENUM(
                        'borrador',
                        'pendiente_pago',
                        'pagado',
                        'en_proceso',
                        'revision',
                        'entregado',
                        'cancelado'
                      ) NOT NULL DEFAULT 'borrador',
  `mp_preference_id`  VARCHAR(255)     NULL,
  `created_at`        DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        DATETIME         NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_cliente` (`cliente_id`),
  INDEX `idx_estado`  (`estado`),
  CONSTRAINT `fk_orden_cliente`  FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_orden_paquete`  FOREIGN KEY (`paquete_id`) REFERENCES `paquetes`  (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── orden_adjuntos ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `orden_adjuntos` (
  `id`             INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `orden_id`       INT UNSIGNED     NOT NULL,
  `cliente_id`     INT UNSIGNED     NOT NULL,
  `original_name`  VARCHAR(255)     NOT NULL,
  `stored_name`    VARCHAR(255)     NOT NULL,
  `file_path`      VARCHAR(500)     NOT NULL,
  `mime_type`      VARCHAR(120)     NOT NULL,
  `file_size`      INT UNSIGNED     NOT NULL,
  `created_at`     DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_adjunto_orden` (`orden_id`),
  INDEX `idx_adjunto_cliente` (`cliente_id`),
  CONSTRAINT `fk_adjunto_orden` FOREIGN KEY (`orden_id`) REFERENCES `ordenes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_adjunto_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── pagos ───────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `pagos` (
  `id`             INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `orden_id`       INT UNSIGNED     NOT NULL,
  `mp_payment_id`  VARCHAR(100)     NOT NULL,
  `mp_status`      VARCHAR(50)      NOT NULL COMMENT 'approved|pending|rejected|cancelled',
  `monto`          DECIMAL(10,2)    NOT NULL,
  `metodo_pago`    VARCHAR(80)      NULL,
  `created_at`     DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     DATETIME         NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_mp_payment` (`mp_payment_id`),
  INDEX `idx_orden` (`orden_id`),
  CONSTRAINT `fk_pago_orden` FOREIGN KEY (`orden_id`) REFERENCES `ordenes` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── orden_logs ──────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `orden_logs` (
  `id`          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `orden_id`    INT UNSIGNED     NOT NULL,
  `cliente_id`  INT UNSIGNED     NULL,
  `accion`      VARCHAR(80)      NOT NULL COMMENT 'creada|confirmada|pago_iniciado|pago_aprobado|pago_rechazado|etc',
  `detalle`     TEXT             NULL,
  `ip`          VARCHAR(45)      NULL,
  `created_at`  DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_log_orden` (`orden_id`),
  CONSTRAINT `fk_log_orden` FOREIGN KEY (`orden_id`) REFERENCES `ordenes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
