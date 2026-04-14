"""
Schemas Marshmallow — validación y serialización de datos.
Actúan como DTOs entre la capa de presentación y la de servicios.
"""
from marshmallow import Schema, fields, validate, validates, ValidationError, post_load
from datetime import date


# ── Schemas de Cliente ──────────────────────────────────────────────────────

class ClientCreateSchema(Schema):
    name    = fields.Str(required=True, validate=validate.Length(min=2, max=100))
    email   = fields.Email(required=True)
    phone   = fields.Str(load_default=None, validate=validate.Length(max=25))
    company = fields.Str(load_default=None, validate=validate.Length(max=100))
    notes   = fields.Str(load_default=None)

    @validates("name")
    def validate_name(self, value):
        if not value.strip():
            raise ValidationError("El nombre no puede estar vacío.")
        return value.strip()


class ClientUpdateSchema(ClientCreateSchema):
    name  = fields.Str(required=False, validate=validate.Length(min=2, max=100))
    email = fields.Email(required=False)


class ClientSchema(Schema):
    """Schema de salida (serialización)."""
    id            = fields.Int(dump_only=True)
    name          = fields.Str()
    email         = fields.Email()
    phone         = fields.Str()
    company       = fields.Str()
    notes         = fields.Str()
    total_sales   = fields.Int(dump_only=True)
    total_revenue = fields.Float(dump_only=True)
    created_at    = fields.DateTime(dump_only=True)


# ── Schemas de Venta ────────────────────────────────────────────────────────

VALID_SERVICE_TYPES   = ["landing_page", "catalog", "store", "corporate", "portfolio", "custom"]
VALID_PAYMENT_METHODS = ["mercado_pago", "transfer", "cash", "card", "other"]
VALID_PAYMENT_STATUSES = ["pending", "paid"]


class SaleCreateSchema(Schema):
    client_id      = fields.Int(required=True, validate=validate.Range(min=1))
    service_type   = fields.Str(required=True, validate=validate.OneOf(VALID_SERVICE_TYPES))
    price          = fields.Decimal(required=True, validate=validate.Range(min=0.01), as_string=False)
    sale_date      = fields.Date(required=True)
    payment_method = fields.Str(required=True, validate=validate.OneOf(VALID_PAYMENT_METHODS))
    payment_status = fields.Str(
        load_default="pending", validate=validate.OneOf(VALID_PAYMENT_STATUSES)
    )

    @validates("sale_date")
    def validate_sale_date(self, value):
        if value > date.today():
            raise ValidationError("La fecha de venta no puede ser futura.")
        return value


class SaleUpdateSchema(SaleCreateSchema):
    client_id      = fields.Int(required=False)
    service_type   = fields.Str(required=False, validate=validate.OneOf(VALID_SERVICE_TYPES))
    price          = fields.Decimal(required=False, validate=validate.Range(min=0.01))
    sale_date      = fields.Date(required=False)
    payment_method = fields.Str(required=False, validate=validate.OneOf(VALID_PAYMENT_METHODS))


# ── Schemas de Proyecto ─────────────────────────────────────────────────────

VALID_PROJECT_STATUSES = ["not_started", "in_development", "in_review", "finished"]


class ProjectUpdateSchema(Schema):
    status = fields.Str(required=True, validate=validate.OneOf(VALID_PROJECT_STATUSES))
    url    = fields.Url(load_default=None, allow_none=True)
    notes  = fields.Str(load_default=None)

    @validates("url")
    def validate_url(self, value):
        if value and len(value) > 255:
            raise ValidationError("La URL no puede superar 255 caracteres.")
        return value
