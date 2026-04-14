"""
DashboardService — consultas analíticas para el panel principal.
"""
from datetime import date, timedelta
from sqlalchemy import func, extract, case
from app.extensions import db
from app.models.sale import Sale
from app.models.client import Client
from app.models.project import Project


class DashboardService:

    @staticmethod
    def get_summary(start_date: date = None, end_date: date = None) -> dict:
        """
        Retorna el resumen general del negocio en el rango de fechas dado.
        Si no se especifica rango, usa el mes en curso.
        """
        today = date.today()

        if start_date is None:
            start_date = today.replace(day=1)
        if end_date is None:
            end_date = today

        # Ventas en el período
        sales_q = Sale.query.filter(
            Sale.sale_date >= start_date,
            Sale.sale_date <= end_date,
        )

        total_sales   = sales_q.count()
        total_revenue = float(
            db.session.query(func.coalesce(func.sum(Sale.price), 0))
            .filter(Sale.sale_date >= start_date, Sale.sale_date <= end_date)
            .scalar()
        )
        paid_revenue = float(
            db.session.query(func.coalesce(func.sum(Sale.price), 0))
            .filter(
                Sale.sale_date >= start_date,
                Sale.sale_date <= end_date,
                Sale.payment_status == "paid",
            )
            .scalar()
        )
        pending_revenue = total_revenue - paid_revenue

        # Proyectos completados en el período
        finished_projects = (
            db.session.query(func.count(Project.id))
            .join(Sale, Project.sale_id == Sale.id)
            .filter(
                Sale.sale_date >= start_date,
                Sale.sale_date <= end_date,
                Project.status == "finished",
            )
            .scalar()
        )

        # Totales globales (sin filtro de fecha)
        total_clients = Client.query.count()
        total_projects_active = Project.query.filter(
            Project.status.in_(["in_development", "in_review"])
        ).count()

        return {
            "start_date":           start_date,
            "end_date":             end_date,
            "total_sales":          total_sales,
            "total_revenue":        total_revenue,
            "paid_revenue":         paid_revenue,
            "pending_revenue":      pending_revenue,
            "finished_projects":    finished_projects,
            "total_clients":        total_clients,
            "total_projects_active": total_projects_active,
        }

    @staticmethod
    def get_monthly_chart_data(months: int = 6) -> dict:
        """
        Retorna datos de ventas e ingresos de los últimos N meses
        para renderizar en Chart.js.
        """
        today = date.today()
        start = (today.replace(day=1) - timedelta(days=30 * (months - 1))).replace(day=1)

        rows = (
            db.session.query(
                extract("year",  Sale.sale_date).label("year"),
                extract("month", Sale.sale_date).label("month"),
                func.count(Sale.id).label("count"),
                func.coalesce(func.sum(Sale.price), 0).label("revenue"),
            )
            .filter(Sale.sale_date >= start)
            .group_by("year", "month")
            .order_by("year", "month")
            .all()
        )

        # Construir mapa mes → datos
        MONTH_NAMES = {
            1: "Ene", 2: "Feb", 3: "Mar", 4: "Abr",
            5: "May", 6: "Jun", 7: "Jul", 8: "Ago",
            9: "Sep", 10: "Oct", 11: "Nov", 12: "Dic",
        }
        data_map = {(int(r.year), int(r.month)): r for r in rows}

        labels, counts, revenues = [], [], []
        cursor = start
        for _ in range(months):
            key  = (cursor.year, cursor.month)
            row  = data_map.get(key)
            labels.append(f"{MONTH_NAMES[cursor.month]} {cursor.year}")
            counts.append(row.count if row else 0)
            revenues.append(float(row.revenue) if row else 0.0)
            # avanzar al siguiente mes
            if cursor.month == 12:
                cursor = cursor.replace(year=cursor.year + 1, month=1)
            else:
                cursor = cursor.replace(month=cursor.month + 1)

        return {"labels": labels, "counts": counts, "revenues": revenues}

    @staticmethod
    def get_project_status_counts() -> dict:
        """Cantidad de proyectos por estado (para dona/doughnut chart)."""
        rows = (
            db.session.query(Project.status, func.count(Project.id))
            .group_by(Project.status)
            .all()
        )
        return {status: count for status, count in rows}

    @staticmethod
    def get_recent_sales(limit: int = 8) -> list:
        """Últimas ventas para la tabla del dashboard."""
        return (
            Sale.query
            .join(Sale.client)
            .order_by(Sale.sale_date.desc())
            .limit(limit)
            .all()
        )

    @staticmethod
    def get_service_type_revenue() -> list:
        """Ingresos agrupados por tipo de servicio."""
        rows = (
            db.session.query(
                Sale.service_type,
                func.count(Sale.id).label("count"),
                func.coalesce(func.sum(Sale.price), 0).label("revenue"),
            )
            .group_by(Sale.service_type)
            .order_by(func.sum(Sale.price).desc())
            .all()
        )
        return [{"type": r.service_type, "count": r.count, "revenue": float(r.revenue)}
                for r in rows]
