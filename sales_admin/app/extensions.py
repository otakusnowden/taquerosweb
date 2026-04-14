"""
Extensiones de Flask inicializadas sin app (patrón Application Factory).
Se vinculan a la app en app/__init__.py mediante init_app().
"""
from flask_sqlalchemy import SQLAlchemy
from flask_login import LoginManager
from flask_bcrypt import Bcrypt
from flask_wtf.csrf import CSRFProtect

db = SQLAlchemy()
login_manager = LoginManager()
bcrypt = Bcrypt()
csrf = CSRFProtect()
