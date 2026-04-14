"""
Rutas de Ventas y Proyectos — CRUD completo.
"""
from flask import Blueprint, render_template, redirect, url_for, flash, request
from flask_login import login_required
from marshmallow import ValidationError
from app.schemas import SaleCreateSchema, SaleUpdateSchema, ProjectUpdateSchema
from app.services.sale_service import SaleService, SaleError
from app.services.client_service import ClientService

sale_bp = Blueprint("sales", __name__)

_create_schema  = SaleCreateSchema()
_update_schema  = SaleUpdateSchema()
_project_schema = ProjectUpdateSchema()

SERVICE_TYPES    = ["landing_page", "catalog", "store", "corporate", "portfolio", "custom"]
PAYMENT_METHODS  = ["mercado_pago", "transfer", "cash", "card", "other"]
PAYMENT_STATUSES = ["pending", "paid"]
PROJECT_STATUSES = ["not_started", "in_development", "in_review", "finished"]


@sale_bp.route("/")
@login_required
def index():
    search         = request.args.get("q", "").strip()
    payment_status = request.args.get("payment_status", "")
    project_status = request.args.get("project_status", "")
    service_type   = request.args.get("service_type", "")
    page           = request.args.get("page", 1, type=int)

    pagination = SaleService.get_all(
        search         = search or None,
        payment_status = payment_status or None,
        project_status = project_status or None,
        service_type   = service_type or None,
        page           = page,
        per_page       = 10,
    )
    return render_template(
        "sales/index.html",
        pagination      = pagination,
        sales           = pagination.items,
        search          = search,
        payment_status  = payment_status,
        project_status  = project_status,
        service_type    = service_type,
        service_types   = SERVICE_TYPES,
        payment_statuses= PAYMENT_STATUSES,
        project_statuses= PROJECT_STATUSES,
    )


@sale_bp.route("/new", methods=["GET", "POST"])
@login_required
def create():
    clients   = ClientService.get_all_list()
    errors    = {}
    form_data = {}

    if request.method == "POST":
        form_data = request.form.to_dict()
        # Convertir client_id a int para el schema
        try:
            form_data["client_id"] = int(form_data.get("client_id", 0))
        except (ValueError, TypeError):
            form_data["client_id"] = 0

        try:
            data = _create_schema.load(form_data)
            # Agregar notas de proyecto si vienen en el form
            data["project_notes"] = request.form.get("project_notes", "").strip() or None
            sale = SaleService.create(data)
            flash(f"Venta #{sale.id} registrada correctamente.", "success")
            return redirect(url_for("sales.detail", sale_id=sale.id))
        except ValidationError as e:
            errors = e.messages
            flash("Corrige los errores del formulario.", "danger")
        except SaleError as e:
            flash(str(e), "danger")

    return render_template(
        "sales/form.html",
        sale             = None,
        clients          = clients,
        errors           = errors,
        form_data        = form_data,
        service_types    = SERVICE_TYPES,
        payment_methods  = PAYMENT_METHODS,
        payment_statuses = PAYMENT_STATUSES,
    )


@sale_bp.route("/<int:sale_id>")
@login_required
def detail(sale_id):
    try:
        sale = SaleService.get_by_id(sale_id)
    except SaleError as e:
        flash(str(e), "warning")
        return redirect(url_for("sales.index"))
    return render_template(
        "sales/detail.html",
        sale             = sale,
        project_statuses = PROJECT_STATUSES,
    )


@sale_bp.route("/<int:sale_id>/edit", methods=["GET", "POST"])
@login_required
def edit(sale_id):
    try:
        sale = SaleService.get_by_id(sale_id)
    except SaleError as e:
        flash(str(e), "warning")
        return redirect(url_for("sales.index"))

    clients   = ClientService.get_all_list()
    errors    = {}
    form_data = {}

    if request.method == "POST":
        form_data = request.form.to_dict()
        try:
            form_data["client_id"] = int(form_data.get("client_id", 0))
        except (ValueError, TypeError):
            form_data["client_id"] = 0

        try:
            data = _update_schema.load(form_data)
            SaleService.update(sale_id, data)
            flash("Venta actualizada correctamente.", "success")
            return redirect(url_for("sales.detail", sale_id=sale_id))
        except ValidationError as e:
            errors = e.messages
            flash("Corrige los errores del formulario.", "danger")
        except SaleError as e:
            flash(str(e), "danger")
    else:
        form_data = {
            "client_id":      sale.client_id,
            "service_type":   sale.service_type,
            "price":          str(sale.price),
            "sale_date":      sale.sale_date.isoformat(),
            "payment_method": sale.payment_method,
            "payment_status": sale.payment_status,
        }

    return render_template(
        "sales/form.html",
        sale             = sale,
        clients          = clients,
        errors           = errors,
        form_data        = form_data,
        service_types    = SERVICE_TYPES,
        payment_methods  = PAYMENT_METHODS,
        payment_statuses = PAYMENT_STATUSES,
    )


@sale_bp.route("/<int:sale_id>/delete", methods=["POST"])
@login_required
def delete(sale_id):
    try:
        SaleService.delete(sale_id)
        flash("Venta eliminada correctamente.", "success")
    except SaleError as e:
        flash(str(e), "danger")
    return redirect(url_for("sales.index"))


@sale_bp.route("/<int:sale_id>/project", methods=["POST"])
@login_required
def update_project(sale_id):
    """Actualiza el estado del proyecto asociado a una venta."""
    form_data = {
        "status": request.form.get("status", ""),
        "url":    request.form.get("url", "").strip() or None,
        "notes":  request.form.get("notes", "").strip() or None,
    }
    try:
        data = _project_schema.load(form_data)
        SaleService.update_project(sale_id, data)
        flash("Proyecto actualizado correctamente.", "success")
    except ValidationError as e:
        first_error = next(iter(e.messages.values()))[0]
        flash(f"Error: {first_error}", "danger")
    except SaleError as e:
        flash(str(e), "danger")

    return redirect(url_for("sales.detail", sale_id=sale_id))
