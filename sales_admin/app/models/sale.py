"""
Modelo Sale — ventas / contratos de servicio web.
"""
import enum
from app.extensions import db


class ServiceType(str, enum.Enum):
    LANDING_PAGE = "landing_page"
    CATALOG      = "catalog"
    STORE        = "store"
    CORPORATE    = "corporate"
    PORTFOLIO    = "portfolio"
    CUSTOM       = "custom"


class PaymentMethod(str, enum.Enum):
    MERCADO_PAGO = "mercado_pago"
    TRANSFER     = "transfer"
    CASH         = "cash"
    CARD         = "card"
    OTHER        = "other"


class PaymentStatus(str, enum.Enum):
    PENDING = "pending"
    PAID    = "paid"


class Sale(db.Model):
    __tablename__ = "sales"

    id             = db.Column(db.Integer, primary_key=True, autoincrement=True)
    client_id      = db.Column(
        db.Integer, db.ForeignKey("clients.id", ondelete="RESTRICT", onupdate="CASCADE"),
        nullable=False, index=True,
    )
    service_type   = db.Column(
        db.Enum(
            "landing_page", "catalog", "store",
            "corporate", "portfolio", "custom",
            name="service_type_enum",
        ),
        nullable=False, index=True,
    )
    price          = db.Column(db.Numeric(10, 2), nullable=False)
    sale_date      = db.Column(db.Date, nullable=False, index=True)
    payment_method = db.Column(
        db.Enum(
            "mercado_pago", "transfer", "cash", "card", "other",
            name="payment_method_enum",
        ),
        nullable=False,
    )
    payment_status = db.Column(
        db.Enum("pending", "paid", name="payment_status_enum"),
        nullable=False, default="pending", index=True,
    )
    created_at     = db.Column(db.DateTime, server_default=db.func.now(), nullable=False)
    updated_at     = db.Column(
        db.DateTime,
        server_default=db.func.now(),
        onupdate=db.func.now(),
        nullable=False,
    )

    # Relaciones
    client  = db.relationship("Client", back_populates="sales")
    project = db.relationship(
        "Project", back_populates="sale",
        uselist=False, cascade="all, delete-orphan",
    )

    def __repr__(self):
        return f"<Sale #{self.id} {self.service_type} ${self.price}>"
