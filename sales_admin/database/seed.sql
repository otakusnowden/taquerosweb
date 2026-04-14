-- ============================================================
-- Datos de prueba - sales_admin
-- Archivo: database/seed.sql
-- NOTA: Ejecutar DESPUÉS de schema.sql
-- Contraseña del admin: Admin1234!
-- ============================================================

USE sales_admin;

-- Admin (password: Admin1234!)
-- Hash generado con bcrypt rounds=12
INSERT INTO users (username, email, password_hash) VALUES
('admin', 'admin@tuagencia.com',
 '$2b$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewdBPj/RK.s5uDom')
ON DUPLICATE KEY UPDATE username=username;

-- Clientes de prueba
INSERT INTO clients (name, email, phone, company, notes) VALUES
('Carlos Mendoza',   'carlos@ejemplo.com',  '+52 55 1234 5678', 'Mendoza Consulting',   'Cliente recurrente, pago puntual'),
('Laura Sánchez',    'laura@ejemplo.com',   '+52 33 9876 5432', NULL,                   'Autónoma, requiere factura'),
('Tienda El Roble',  'contacto@elroble.mx', '+52 81 5555 0000', 'El Roble S.A. de C.V.','Negocio familiar, catálogo de muebles'),
('Diego Ramírez',    'diego.r@ejemplo.com', '+52 55 7777 8888', 'DR Fotografía',        'Portafolio de fotografía profesional'),
('Ana Torres',       'ana.torres@ejemplo.com', '+52 442 123 4567', 'Pastelería Torres', NULL)
ON DUPLICATE KEY UPDATE name=name;

-- Ventas de prueba
INSERT INTO sales (client_id, service_type, price, sale_date, payment_method, payment_status) VALUES
(1, 'corporate',   18500.00, '2025-10-05', 'transfer',    'paid'),
(2, 'landing_page', 4500.00, '2025-10-12', 'mercado_pago','paid'),
(3, 'catalog',     12000.00, '2025-11-03', 'transfer',    'paid'),
(4, 'portfolio',    7500.00, '2025-11-18', 'cash',        'paid'),
(5, 'store',       22000.00, '2025-12-02', 'transfer',    'pending'),
(1, 'landing_page', 4800.00, '2025-12-15', 'mercado_pago','paid'),
(2, 'catalog',      9500.00, '2026-01-08', 'card',        'paid'),
(3, 'custom',      35000.00, '2026-01-20', 'transfer',    'pending'),
(4, 'corporate',   16000.00, '2026-02-10', 'transfer',    'paid'),
(5, 'landing_page', 5200.00, '2026-02-28', 'mercado_pago','paid'),
(1, 'store',       28000.00, '2026-03-05', 'transfer',    'paid'),
(2, 'portfolio',    6800.00, '2026-03-22', 'cash',        'pending');

-- Proyectos asociados a cada venta
INSERT INTO projects (sale_id, status, url, notes) VALUES
(1,  'finished',       'https://mendozaconsulting.mx',    'Rediseño completo. Cliente aprobó todo en primera revisión.'),
(2,  'finished',       'https://laurasanchez.com',        'Landing page para servicio de consultoría.'),
(3,  'finished',       'https://elroble.mx',              'Catálogo con +200 productos. Integrado con WhatsApp.'),
(4,  'finished',       'https://diegoramirez.photography','Portafolio con galería interactiva.'),
(5,  'in_development', NULL,                              'Tienda con pastelería. Requiere carrito + pagos en línea.'),
(6,  'finished',       'https://mendoza-promo.mx',        'Landing para campaña navideña.'),
(7,  'in_review',      NULL,                              'Catálogo en revisión final. Pendiente de imágenes del cliente.'),
(8,  'in_development', NULL,                              'Sistema custom de reservas para eventos corporativos.'),
(9,  'in_review',      NULL,                              'Rediseño corporativo. En espera de feedback del cliente.'),
(10, 'finished',       'https://promo.pasteleriatorres.mx','Landing para temporada de San Valentín.'),
(11, 'not_started',    NULL,                              'Tienda completa. Kickoff programado para la próxima semana.'),
(12, 'not_started',    NULL,                              'Portafolio. Esperando brief de la cliente.');
