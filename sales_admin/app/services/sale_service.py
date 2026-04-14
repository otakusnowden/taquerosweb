"""
SaleService — lógica de negocio para ventas y proyectos.
"""
from sqlalchemy.exc import IntegrityError
from app.extensions import db
from app.models.sale import Sale
from app.models.project import Project


class SaleError(Exception):
    """Error controlado de la capa de ventas."""


class SaleService:

    @staticmethod
    def get_all(
        search: str = None,
        payment_status: str = None,
        project_status: str = None,
        service_type: str = None,
        page: int = 1,
        per_page: int = 10,
    ):
        """Retorna ventas paginadas con filtros opcionales."""
        from app.models.client import Client

        query = (
            Sale.query
            .join(Sale.client)
            .outerjoin(Sale.project)
            .order_by(Sale.sale_date.desc())
        )

        if search:
            term = f"%{search.strip()}%"
            query = query.filter(Client.name.ilike(term))

        if payment_status:
            query = query.filter(Sale.payment_status == payment_status)

        if project_status:
            query = query.filter(Project.status == project_status)

        if service_type:
            query = query.filter(Sale.service_type == service_type)

        return query.paginate(page=page, per_page=per_page, error_out=False)

    @staticmethod
    def get_by_id(sale_id: int) -> Sale:
        sale = db.session.get(Sale, sale_id)
        if sale is None:
            raise SaleError(f"Venta #{sale_id} no encontrada.")
        return sale

    @staticmethod
    def create(data: dict) -> Sale:
        """
        Crea una venta y su proyecto asociado (status: not_started).
        data debe estar previamente validado.
        """
        from app.models.client import Client

        client = db.session.get(Client, data["client_id"])
        if client is None:
            raise SaleError("El cliente seleccionado no existe.")

        sale = Sale(
            client_id      = data["client_id"],
            service_type   = data["service_type"],
            price          = data["price"],
            sale_date      = data["sale_date"],
            payment_method = data["payment_method"],
            payment_status = data.get("payment_status", "pending"),
        )

        # Crear proyecto asociado automáticamente
        project = Project(
            status = "not_started",
            notes  = data.get("project_notes") or None,
        )
        sale.project = project

        try:
            db.session.add(sale)
            db.session.commit()
            return sale
        except IntegrityError:
            db.session.rollback()
            raise SaleError("Error al registrar la venta.")

    @staticmethod
    def update(sale_id: int, data: dict) -> Sale:
        """Actualiza datos de una venta."""
        sale = SaleService.get_by_id(sale_id)

        if "client_id" in data:
            sale.client_id = data["client_id"]
        if "service_type" in data:
            sale.service_type = data["service_type"]
        if "price" in data:
            sale.price = data["price"]
        if "sale_date" in data:
            sale.sale_date = data["sale_date"]
        if "payment_method" in data:
            sale.payment_method = data["payment_method"]
        if "payment_status" in data:
            sale.payment_status = data["payment_status"]

        try:
            db.session.commit()
            return sale
        except IntegrityError:
            db.session.rollback()
            raise SaleError("Error al actualizar la venta.")

    @staticmethod
    def delete(sale_id: int) -> None:
        """Elimina una venta (y su proyecto en cascada)."""
        sale = SaleService.get_by_id(sale_id)
        try:
            db.session.delete(sale)
            db.session.commit()
        except IntegrityError:
            db.session.rollback()
            raise SaleError("No se puede eliminar la venta.")

    # ── Proyectos ───────────────────────────────────────────────────────────

    @staticmethod
    def update_project(sale_id: int, data: dict) -> Project:
        """Actualiza el estado y datos del proyecto asociado a una venta."""
        sale = SaleService.get_by_id(sale_id)

        if sale.project is None:
            project = Project(sale_id=sale_id)
            db.session.add(project)
            sale.project = project

        project = sale.project

        if "status" in data:
            project.status = data["status"]
        if "url" in data:
            project.url = data.get("url") or None
        if "notes" in data:
            project.notes = data.get("notes") or None

        try:
            db.session.commit()
            return project
        except IntegrityError:
            db.session.rollback()
            raise SaleError("Error al actualizar el proyecto.")
