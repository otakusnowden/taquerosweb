-- ============================================================
-- Sistema de Administración de Ventas - Web Dev Agency
-- Archivo: database/schema.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS sales_admin
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE sales_admin;

-- ------------------------------------------------------------
-- TABLA: users
-- Único usuario administrador del sistema
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id            INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    username      VARCHAR(50)     NOT NULL,
    email         VARCHAR(100)    NOT NULL,
    password_hash VARCHAR(255)    NOT NULL,
    is_active     TINYINT(1)      NOT NULL DEFAULT 1,
    created_at    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uq_users_username (username),
    UNIQUE KEY uq_users_email    (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Usuarios administradores del sistema';


-- ------------------------------------------------------------
-- TABLA: clients
-- Clientes del negocio de desarrollo web
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS clients (
    id         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    name       VARCHAR(100)  NOT NULL,
    email      VARCHAR(100)  NOT NULL,
    phone      VARCHAR(25),
    company    VARCHAR(100),
    notes      TEXT,
    created_at TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uq_clients_email (email),
    KEY idx_clients_name        (name),
    KEY idx_clients_created_at  (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Clientes del negocio';


-- ------------------------------------------------------------
-- TABLA: sales
-- Registro de ventas / contratos de servicio
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS sales (
    id             INT UNSIGNED       NOT NULL AUTO_INCREMENT,
    client_id      INT UNSIGNED       NOT NULL,
    service_type   ENUM(
                     'landing_page',
                     'catalog',
                     'store',
                     'corporate',
                     'portfolio',
                     'custom'
                   )                  NOT NULL,
    price          DECIMAL(10, 2)     NOT NULL,
    sale_date      DATE               NOT NULL,
    payment_method ENUM(
                     'mercado_pago',
                     'transfer',
                     'cash',
                     'card',
                     'other'
                   )                  NOT NULL,
    payment_status ENUM(
                     'pending',
                     'paid'
                   )                  NOT NULL DEFAULT 'pending',
    created_at     TIMESTAMP          NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at     TIMESTAMP          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    KEY idx_sales_client_id     (client_id),
    KEY idx_sales_sale_date     (sale_date),
    KEY idx_sales_payment_status(payment_status),
    KEY idx_sales_service_type  (service_type),

    CONSTRAINT fk_sales_client
        FOREIGN KEY (client_id)
        REFERENCES clients (id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Ventas y contratos de servicios web';


-- ------------------------------------------------------------
-- TABLA: projects
-- Estado y detalles del proyecto asociado a cada venta (1:1)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS projects (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    sale_id    INT UNSIGNED NOT NULL,
    status     ENUM(
                 'not_started',
                 'in_development',
                 'in_review',
                 'finished'
               )             NOT NULL DEFAULT 'not_started',
    url        VARCHAR(255),
    notes      TEXT,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uq_projects_sale_id (sale_id),
    KEY idx_projects_status        (status),

    CONSTRAINT fk_projects_sale
        FOREIGN KEY (sale_id)
        REFERENCES sales (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Estado y detalles de cada proyecto web';
