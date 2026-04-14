"""
Modelo Client — clientes del negocio de desarrollo web.
"""
from app.extensions import db


class Client(db.Model):
    __tablename__ = "clients"

    id         = db.Column(db.Integer, primary_key=True, autoincrement=True)
    name       = db.Column(db.String(100), nullable=False, index=True)
    email      = db.Column(db.String(100), nullable=False, unique=True, index=True)
    phone      = db.Column(db.String(25))
    company    = db.Column(db.String(100))
    notes      = db.Column(db.Text)
    created_at = db.Column(db.DateTime, server_default=db.func.now(), nullable=False, index=True)
    updated_at = db.Column(
        db.DateTime,
        server_default=db.func.now(),
        onupdate=db.func.now(),
        nullable=False,
    )

    # Relación: un cliente tiene muchas ventas
    sales = db.relationship("Sale", back_populates="client", lazy="dynamic")

    @property
    def total_sales(self):
        return self.sales.count()

    @property
    def total_revenue(self):
        from sqlalchemy import func
        from app.models.sale import Sale
        result = (
            db.session.query(func.sum(Sale.price))
            .filter(Sale.client_id == self.id)
            .scalar()
        )
        return float(result or 0)

    def __repr__(self):
        return f"<Client {self.name}>"
