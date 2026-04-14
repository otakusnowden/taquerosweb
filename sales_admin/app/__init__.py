"""
Application Factory de Flask.
Inicializa extensiones, registra blueprints y filtros de Jinja2.
"""
from flask import Flask
from config import get_config
from app.extensions import db, login_manager, bcrypt, csrf


def create_app():
    app = Flask(__name__)
    app.config.from_object(get_config())

    # ── Inicializar extensiones ─────────────────────────────────────────────
    db.init_app(app)
    bcrypt.init_app(app)
    csrf.init_app(app)

    login_manager.init_app(app)
    login_manager.login_view = app.config["LOGIN_VIEW"]
    login_manager.login_message = app.config["LOGIN_MESSAGE"]
    login_manager.login_message_category = app.config["LOGIN_MESSAGE_CATEGORY"]

    # ── Registrar blueprints ────────────────────────────────────────────────
    from app.routes.auth_routes import auth_bp
    from app.routes.dashboard_routes import dashboard_bp
    from app.routes.client_routes import client_bp
    from app.routes.sale_routes import sale_bp

    app.register_blueprint(auth_bp)
    app.register_blueprint(dashboard_bp)
    app.register_blueprint(client_bp, url_prefix="/clients")
    app.register_blueprint(sale_bp, url_prefix="/sales")

    # ── Filtros Jinja2 personalizados ───────────────────────────────────────
    _register_filters(app)

    # ── User loader para Flask-Login ────────────────────────────────────────
    from app.models.user import User

    @login_manager.user_loader
    def load_user(user_id):
        return db.session.get(User, int(user_id))

    return app


# ── Filtros de plantilla ────────────────────────────────────────────────────

SERVICE_LABELS = {
    "landing_page": "Landing Page",
    "catalog":      "Catálogo",
    "store":        "Tienda en línea",
    "corporate":    "Sitio Corporativo",
    "portfolio":    "Portafolio",
    "custom":       "Desarrollo Custom",
}

PAYMENT_METHOD_LABELS = {
    "mercado_pago": "Mercado Pago",
    "transfer":     "Transferencia",
    "cash":         "Efectivo",
    "card":         "Tarjeta",
    "other":        "Otro",
}

PAYMENT_STATUS_LABELS = {
    "pending": "Pendiente",
    "paid":    "Pagado",
}

PROJECT_STATUS_LABELS = {
    "not_started":    "No iniciado",
    "in_development": "En desarrollo",
    "in_review":      "En revisión",
    "finished":       "Finalizado",
}

PROJECT_STATUS_BADGES = {
    "not_started":    "secondary",
    "in_development": "primary",
    "in_review":      "warning",
    "finished":       "success",
}

PAYMENT_STATUS_BADGES = {
    "pending": "warning",
    "paid":    "success",
}


def _register_filters(app: Flask):
    @app.template_filter("service_label")
    def service_label(value):
        return SERVICE_LABELS.get(value, value)

    @app.template_filter("payment_method_label")
    def payment_method_label(value):
        return PAYMENT_METHOD_LABELS.get(value, value)

    @app.template_filter("payment_status_label")
    def payment_status_label(value):
        return PAYMENT_STATUS_LABELS.get(value, value)

    @app.template_filter("project_status_label")
    def project_status_label(value):
        return PROJECT_STATUS_LABELS.get(value, value)

    @app.template_filter("project_status_badge")
    def project_status_badge(value):
        return PROJECT_STATUS_BADGES.get(value, "secondary")

    @app.template_filter("payment_status_badge")
    def payment_status_badge(value):
        return PAYMENT_STATUS_BADGES.get(value, "secondary")

    @app.template_filter("currency")
    def currency_filter(value):
        try:
            return f"${float(value):,.2f}"
        except (TypeError, ValueError):
            return value
