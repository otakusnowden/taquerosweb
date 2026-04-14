"""
seed.py — Inicializa la base de datos y crea el usuario administrador.
Ejecutar solo la primera vez:
    python seed.py
"""
import os
from dotenv import load_dotenv
from app import create_app
from app.extensions import db, bcrypt
from app.models import User, Client, Sale, Project
from datetime import date
from decimal import Decimal

load_dotenv()

app = create_app()


def create_tables():
    with app.app_context():
        db.create_all()
        print("✅ Tablas creadas/verificadas.")


def create_admin():
    with app.app_context():
        username = os.getenv("ADMIN_USERNAME", "admin")
        email    = os.getenv("ADMIN_EMAIL", "admin@tuagencia.com")
        password = os.getenv("ADMIN_PASSWORD", "Admin1234!")

        if User.query.filter_by(username=username).first():
            print(f"ℹ️  Usuario '{username}' ya existe, omitiendo.")
            return

        hashed = bcrypt.generate_password_hash(password).decode("utf-8")
        admin  = User(username=username, email=email, password_hash=hashed)
        db.session.add(admin)
        db.session.commit()
        print(f"✅ Admin creado — usuario: {username} | contraseña: {password}")


def seed_demo_data():
    """Inserta datos de demostración si la BD está vacía."""
    with app.app_context():
        if Client.query.count() > 0:
            print("ℹ️  Ya existen clientes. Omitiendo datos de demo.")
            return

        # ── Clientes ──────────────────────────────────────────────────────
        clients_data = [
            dict(name="Carlos Mendoza",   email="carlos@ejemplo.com",     phone="+52 55 1234 5678", company="Mendoza Consulting"),
            dict(name="Laura Sánchez",    email="laura@ejemplo.com",       phone="+52 33 9876 5432"),
            dict(name="Tienda El Roble",  email="contacto@elroble.mx",     phone="+52 81 5555 0000", company="El Roble S.A."),
            dict(name="Diego Ramírez",    email="diego.r@ejemplo.com",     phone="+52 55 7777 8888", company="DR Fotografía"),
            dict(name="Ana Torres",       email="ana.torres@ejemplo.com",  phone="+52 442 123 4567", company="Pastelería Torres"),
        ]
        clients = [Client(**d) for d in clients_data]
        db.session.add_all(clients)
        db.session.flush()  # obtener IDs

        # ── Ventas + proyectos ────────────────────────────────────────────
        sales_data = [
            # (client_idx, service, price, date, method, pay_status, proj_status, url, notes)
            (0, "corporate",    18500, date(2025, 10, 5),  "transfer",    "paid",    "finished",       "https://mendozaconsulting.mx",     "Rediseño completo"),
            (1, "landing_page",  4500, date(2025, 10, 12), "mercado_pago","paid",    "finished",       "https://laurasanchez.com",          "LP para consultoría"),
            (2, "catalog",      12000, date(2025, 11, 3),  "transfer",    "paid",    "finished",       "https://elroble.mx",                "Catálogo +200 productos"),
            (3, "portfolio",     7500, date(2025, 11, 18), "cash",        "paid",    "finished",       "https://diegoramirez.photography",  "Galería interactiva"),
            (4, "store",        22000, date(2025, 12, 2),  "transfer",    "pending", "in_development", None,                               "Carrito + pagos en línea"),
            (0, "landing_page",  4800, date(2025, 12, 15), "mercado_pago","paid",    "finished",       "https://mendoza-promo.mx",          "LP campaña navideña"),
            (1, "catalog",       9500, date(2026, 1, 8),   "card",        "paid",    "in_review",      None,                               "En revisión final"),
            (2, "custom",       35000, date(2026, 1, 20),  "transfer",    "pending", "in_development", None,                               "Sistema de reservas"),
            (3, "corporate",    16000, date(2026, 2, 10),  "transfer",    "paid",    "in_review",      None,                               "Rediseño corporativo"),
            (4, "landing_page",  5200, date(2026, 2, 28),  "mercado_pago","paid",    "finished",       "https://promo.pasteleriatorres.mx", "LP San Valentín"),
            (0, "store",        28000, date(2026, 3, 5),   "transfer",    "paid",    "not_started",    None,                               "Kickoff semana próxima"),
            (1, "portfolio",     6800, date(2026, 3, 22),  "cash",        "pending", "not_started",    None,                               "Esperando brief"),
        ]

        for (ci, stype, price, sdate, method, pay_st, proj_st, url, notes) in sales_data:
            sale = Sale(
                client_id      = clients[ci].id,
                service_type   = stype,
                price          = Decimal(price),
                sale_date      = sdate,
                payment_method = method,
                payment_status = pay_st,
            )
            proj = Project(status=proj_st, url=url, notes=notes)
            sale.project = proj
            db.session.add(sale)

        db.session.commit()
        print(f"✅ Datos de demo insertados: {len(clients_data)} clientes, {len(sales_data)} ventas.")


if __name__ == "__main__":
    print("🚀 Inicializando base de datos...")
    create_tables()
    create_admin()
    seed_demo_data()
    print("\n🎉 Listo. Ejecuta: flask run")
