"""
Modelo Project — estado y detalles del proyecto asociado a una venta (1:1).
"""
from app.extensions import db


class Project(db.Model):
    __tablename__ = "projects"

    id         = db.Column(db.Integer, primary_key=True, autoincrement=True)
    sale_id    = db.Column(
        db.Integer, db.ForeignKey("sales.id", ondelete="CASCADE", onupdate="CASCADE"),
        nullable=False, unique=True, index=True,
    )
    status     = db.Column(
        db.Enum(
            "not_started", "in_development", "in_review", "finished",
            name="project_status_enum",
        ),
        nullable=False, default="not_started", index=True,
    )
    url        = db.Column(db.String(255))
    notes      = db.Column(db.Text)
    created_at = db.Column(db.DateTime, server_default=db.func.now(), nullable=False)
    updated_at = db.Column(
        db.DateTime,
        server_default=db.func.now(),
        onupdate=db.func.now(),
        nullable=False,
    )

    # Relación inversa
    sale = db.relationship("Sale", back_populates="project")

    def __repr__(self):
        return f"<Project sale_id={self.sale_id} status={self.status}>"
