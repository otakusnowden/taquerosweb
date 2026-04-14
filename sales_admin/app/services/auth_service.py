"""
AuthService — lógica de autenticación.
"""
from app.extensions import db, bcrypt
from app.models.user import User


class AuthError(Exception):
    """Error controlado de autenticación."""


class AuthService:

    @staticmethod
    def authenticate(username: str, password: str) -> User:
        """
        Valida credenciales y retorna el User si son correctas.
        Lanza AuthError si las credenciales son inválidas.
        """
        if not username or not password:
            raise AuthError("Usuario y contraseña son requeridos.")

        user = User.query.filter_by(username=username).first()
        if user is None:
            raise AuthError("Credenciales inválidas.")

        if not user.is_active:
            raise AuthError("Tu cuenta está desactivada. Contacta al administrador.")

        if not bcrypt.check_password_hash(user.password_hash, password):
            raise AuthError("Credenciales inválidas.")

        return user

    @staticmethod
    def create_admin(username: str, email: str, password: str) -> User:
        """
        Crea el usuario administrador inicial.
        Solo debe llamarse desde CLI / scripts de inicialización.
        """
        if User.query.count() > 0:
            raise AuthError("Ya existe un usuario administrador.")

        if len(password) < 8:
            raise AuthError("La contraseña debe tener al menos 8 caracteres.")

        hashed = bcrypt.generate_password_hash(password).decode("utf-8")
        user = User(username=username, email=email, password_hash=hashed)
        db.session.add(user)
        db.session.commit()
        return user

    @staticmethod
    def change_password(user: User, current_password: str, new_password: str) -> None:
        """Permite al admin cambiar su propia contraseña."""
        if not bcrypt.check_password_hash(user.password_hash, current_password):
            raise AuthError("La contraseña actual es incorrecta.")

        if len(new_password) < 8:
            raise AuthError("La nueva contraseña debe tener al menos 8 caracteres.")

        user.password_hash = bcrypt.generate_password_hash(new_password).decode("utf-8")
        db.session.commit()
