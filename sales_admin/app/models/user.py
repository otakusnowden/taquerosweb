"""
Modelo User — usuario administrador del sistema.
Implementa UserMixin para integración con Flask-Login.
"""
from flask_login import UserMixin
from app.extensions import db


class User(UserMixin, db.Model):
    __tablename__ = "users"

    id            = db.Column(db.Integer, primary_key=True, autoincrement=True)
    username      = db.Column(db.String(50), nullable=False, unique=True, index=True)
    email         = db.Column(db.String(100), nullable=False, unique=True, index=True)
    password_hash = db.Column(db.String(255), nullable=False)
    is_active     = db.Column(db.Boolean, nullable=False, default=True)
    created_at    = db.Column(db.DateTime, server_default=db.func.now(), nullable=False)
    updated_at    = db.Column(
        db.DateTime,
        server_default=db.func.now(),
        onupdate=db.func.now(),
        nullable=False,
    )

    def __repr__(self):
        return f"<User {self.username}>"
