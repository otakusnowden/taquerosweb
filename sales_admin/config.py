import os
from dotenv import load_dotenv

load_dotenv()


class Config:
    """Configuración base de la aplicación."""

    # Flask
    SECRET_KEY = os.getenv("SECRET_KEY", "dev-key-insegura-cambiar-en-produccion")
    WTF_CSRF_ENABLED = True
    WTF_CSRF_TIME_LIMIT = 3600  # 1 hora

    # Base de datos
    DB_HOST = os.getenv("DB_HOST", "localhost")
    DB_PORT = os.getenv("DB_PORT", "3306")
    DB_NAME = os.getenv("DB_NAME", "sales_admin")
    DB_USER = os.getenv("DB_USER", "root")
    DB_PASSWORD = os.getenv("DB_PASSWORD", "")

    SQLALCHEMY_DATABASE_URI = (
        f"mysql+pymysql://{DB_USER}:{DB_PASSWORD}@{DB_HOST}:{DB_PORT}/{DB_NAME}"
        "?charset=utf8mb4"
    )
    SQLALCHEMY_TRACK_MODIFICATIONS = False
    SQLALCHEMY_ECHO = False  # Cambiar a True para ver SQL en consola

    # Login
    LOGIN_VIEW = "auth.login"
    LOGIN_MESSAGE = "Debes iniciar sesión para acceder a esta página."
    LOGIN_MESSAGE_CATEGORY = "warning"

    # Paginación
    ITEMS_PER_PAGE = 10


class DevelopmentConfig(Config):
    DEBUG = True
    SQLALCHEMY_ECHO = False


class ProductionConfig(Config):
    DEBUG = False
    WTF_CSRF_SSL_STRICT = True


config_map = {
    "development": DevelopmentConfig,
    "production": ProductionConfig,
    "default": DevelopmentConfig,
}


def get_config():
    env = os.getenv("FLASK_ENV", "default")
    return config_map.get(env, DevelopmentConfig)
