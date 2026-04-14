"""
Rutas del Dashboard — panel principal con métricas y reportes.
"""
from datetime import date, datetime
from flask import Blueprint, render_template, request, jsonify
from flask_login import login_required
from app.services.dashboard_service import DashboardService

dashboard_bp = Blueprint("dashboard", __name__)


def _parse_date(value: str, fallback: date) -> date:
    try:
        return datetime.strptime(value, "%Y-%m-%d").date()
    except (ValueError, TypeError):
        return fallback


@dashboard_bp.route("/dashboard")
@login_required
def index():
    today      = date.today()
    start_date = _parse_date(request.args.get("start"), today.replace(day=1))
    end_date   = _parse_date(request.args.get("end"),   today)

    if start_date > end_date:
        start_date, end_date = end_date, start_date

    summary       = DashboardService.get_summary(start_date, end_date)
    recent_sales  = DashboardService.get_recent_sales(8)
    service_stats = DashboardService.get_service_type_revenue()

    return render_template(
        "dashboard.html",
        summary=summary,
        recent_sales=recent_sales,
        service_stats=service_stats,
        start_date=start_date,
        end_date=end_date,
    )


@dashboard_bp.route("/api/chart-data")
@login_required
def chart_data():
    """Endpoint JSON para Chart.js — datos de los últimos 6 meses."""
    data = DashboardService.get_monthly_chart_data(6)
    status_data = DashboardService.get_project_status_counts()
    return jsonify({"monthly": data, "project_status": status_data})
