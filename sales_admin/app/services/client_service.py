"""
ClientService — lógica de negocio para gestión de clientes.
"""
from sqlalchemy.exc import IntegrityError
from app.extensions import db
from app.models.client import Client


class ClientError(Exception):
    """Error controlado de la capa de clientes."""


class ClientService:

    @staticmethod
    def get_all(search: str = None, page: int = 1, per_page: int = 10):
        """Retorna clientes paginados con búsqueda opcional."""
        query = Client.query.order_by(Client.created_at.desc())
        if search:
            term = f"%{search.strip()}%"
            query = query.filter(
                db.or_(
                    Client.name.ilike(term),
                    Client.email.ilike(term),
                    Client.company.ilike(term),
                )
            )
        return query.paginate(page=page, per_page=per_page, error_out=False)

    @staticmethod
    def get_by_id(client_id: int) -> Client:
        client = db.session.get(Client, client_id)
        if client is None:
            raise ClientError(f"Cliente #{client_id} no encontrado.")
        return client

    @staticmethod
    def get_all_list() -> list[Client]:
        """Lista completa para selectores de formulario."""
        return Client.query.order_by(Client.name).all()

    @staticmethod
    def create(data: dict) -> Client:
        """
        Crea un nuevo cliente.
        data debe estar previamente validado por ClientCreateSchema.
        """
        if Client.query.filter_by(email=data["email"]).first():
            raise ClientError(f"Ya existe un cliente con el email {data['email']}.")

        client = Client(
            name    = data["name"].strip(),
            email   = data["email"].strip().lower(),
            phone   = data.get("phone") or None,
            company = data.get("company") or None,
            notes   = data.get("notes") or None,
        )
        try:
            db.session.add(client)
            db.session.commit()
            return client
        except IntegrityError:
            db.session.rollback()
            raise ClientError("Error al guardar el cliente. Verifica los datos.")

    @staticmethod
    def update(client_id: int, data: dict) -> Client:
        """Actualiza los datos de un cliente existente."""
        client = ClientService.get_by_id(client_id)

        # Verificar duplicado de email (excluyendo el propio)
        if "email" in data and data["email"] != client.email:
            existing = Client.query.filter_by(email=data["email"]).first()
            if existing:
                raise ClientError(f"El email {data['email']} ya está registrado.")

        if "name" in data:
            client.name = data["name"].strip()
        if "email" in data:
            client.email = data["email"].strip().lower()
        if "phone" in data:
            client.phone = data.get("phone") or None
        if "company" in data:
            client.company = data.get("company") or None
        if "notes" in data:
            client.notes = data.get("notes") or None

        try:
            db.session.commit()
            return client
        except IntegrityError:
            db.session.rollback()
            raise ClientError("Error al actualizar el cliente.")

    @staticmethod
    def delete(client_id: int) -> None:
        """
        Elimina un cliente.
        Falla si tiene ventas asociadas (RESTRICT en FK).
        """
        client = ClientService.get_by_id(client_id)
        if client.sales.count() > 0:
            raise ClientError(
                f"No se puede eliminar a {client.name} porque tiene ventas registradas."
            )
        try:
            db.session.delete(client)
            db.session.commit()
        except IntegrityError:
            db.session.rollback()
            raise ClientError("No se puede eliminar el cliente por integridad de datos.")
