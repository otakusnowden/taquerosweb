"""
Punto de entrada de la aplicación.
Uso:
    flask run          # Desarrollo
    python run.py      # Alternativo
"""
from app import create_app

app = create_app()

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)
