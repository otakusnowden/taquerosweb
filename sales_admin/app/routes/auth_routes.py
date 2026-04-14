"""
Rutas de autenticación — login / logout / cambio de contraseña.
"""
from flask import Blueprint, render_template, redirect, url_for, flash, request
from flask_login import login_user, logout_user, login_required, current_user
from app.services.auth_service import AuthService, AuthError

auth_bp = Blueprint("auth", __name__)


@auth_bp.route("/")
def index():
    if current_user.is_authenticated:
        return redirect(url_for("dashboard.index"))
    return redirect(url_for("auth.login"))


@auth_bp.route("/login", methods=["GET", "POST"])
def login():
    if current_user.is_authenticated:
        return redirect(url_for("dashboard.index"))

    if request.method == "POST":
        username = request.form.get("username", "").strip()
        password = request.form.get("password", "")

        try:
            user = AuthService.authenticate(username, password)
            login_user(user, remember=request.form.get("remember") == "on")
            flash(f"¡Bienvenido, {user.username}!", "success")
            next_page = request.args.get("next")
            return redirect(next_page or url_for("dashboard.index"))
        except AuthError as e:
            flash(str(e), "danger")

    return render_template("login.html")


@auth_bp.route("/logout")
@login_required
def logout():
    logout_user()
    flash("Sesión cerrada correctamente.", "info")
    return redirect(url_for("auth.login"))


@auth_bp.route("/change-password", methods=["GET", "POST"])
@login_required
def change_password():
    if request.method == "POST":
        current_pw = request.form.get("current_password", "")
        new_pw     = request.form.get("new_password", "")
        confirm_pw = request.form.get("confirm_password", "")

        if new_pw != confirm_pw:
            flash("Las contraseñas nuevas no coinciden.", "danger")
        else:
            try:
                AuthService.change_password(current_user, current_pw, new_pw)
                flash("Contraseña actualizada correctamente.", "success")
                return redirect(url_for("dashboard.index"))
            except AuthError as e:
                flash(str(e), "danger")

    return render_template("change_password.html")
