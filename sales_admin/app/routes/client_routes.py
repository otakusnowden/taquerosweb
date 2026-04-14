"""
Rutas de Clientes — CRUD completo.
"""
from flask import Blueprint, render_template, redirect, url_for, flash, request
from flask_login import login_required
from marshmallow import ValidationError
from app.schemas import ClientCreateSchema, ClientUpdateSchema
from app.services.client_service import ClientService, ClientError

client_bp = Blueprint("clients", __name__)

_create_schema = ClientCreateSchema()
_update_schema = ClientUpdateSchema()


@client_bp.route("/")
@login_required
def index():
    search   = request.args.get("q", "").strip()
    page     = request.args.get("page", 1, type=int)
    per_page = 10
    pagination = ClientService.get_all(search=search or None, page=page, per_page=per_page)
    return render_template(
        "clients/index.html",
        pagination=pagination,
        clients=pagination.items,
        search=search,
    )


@client_bp.route("/new", methods=["GET", "POST"])
@login_required
def create():
    errors = {}
    form_data = {}

    if request.method == "POST":
        form_data = request.form.to_dict()
        try:
            data   = _create_schema.load(form_data)
            client = ClientService.create(data)
            flash(f"Cliente «{client.name}» creado correctamente.", "success")
            return redirect(url_for("clients.index"))
        except ValidationError as e:
            errors = e.messages
            flash("Corrige los errores del formulario.", "danger")
        except ClientError as e:
            flash(str(e), "danger")

    return render_template("clients/form.html", client=None, errors=errors, form_data=form_data)


@client_bp.route("/<int:client_id>")
@login_required
def detail(client_id):
    try:
        client = ClientService.get_by_id(client_id)
    except ClientError as e:
        flash(str(e), "warning")
        return redirect(url_for("clients.index"))

    sales = client.sales.order_by(None).order_by(
        __import__("app.models.sale", fromlist=["Sale"]).Sale.sale_date.desc()
    ).all()
    return render_template("clients/detail.html", client=client, sales=sales)


@client_bp.route("/<int:client_id>/edit", methods=["GET", "POST"])
@login_required
def edit(client_id):
    try:
        client = ClientService.get_by_id(client_id)
    except ClientError as e:
        flash(str(e), "warning")
        return redirect(url_for("clients.index"))

    errors    = {}
    form_data = {}

    if request.method == "POST":
        form_data = request.form.to_dict()
        try:
            data = _update_schema.load(form_data)
            ClientService.update(client_id, data)
            flash("Cliente actualizado correctamente.", "success")
            return redirect(url_for("clients.detail", client_id=client_id))
        except ValidationError as e:
            errors = e.messages
            flash("Corrige los errores del formulario.", "danger")
        except ClientError as e:
            flash(str(e), "danger")
    else:
        form_data = {
            "name":    client.name,
            "email":   client.email,
            "phone":   client.phone or "",
            "company": client.company or "",
            "notes":   client.notes or "",
        }

    return render_template(
        "clients/form.html",
        client=client,
        errors=errors,
        form_data=form_data,
    )


@client_bp.route("/<int:client_id>/delete", methods=["POST"])
@login_required
def delete(client_id):
    try:
        client = ClientService.get_by_id(client_id)
        ClientService.delete(client_id)
        flash(f"Cliente «{client.name}» eliminado.", "success")
    except ClientError as e:
        flash(str(e), "danger")
    return redirect(url_for("clients.index"))
