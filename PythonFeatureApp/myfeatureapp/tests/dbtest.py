import pymysql
from flask import Flask
from flask_sqlalchemy import SQLAlchemy

app = Flask(__name__)
app.config.from_pyfile('testsettings.cfg')
db = SQLAlchemy(app)
     
class DBTest():   
    
    def connect_db():
        try:
            db.session.query("1").from_statement("SELECT 1").all()
            return '<h1>It works.</h1>'
        except:
            return '<h1>Something is broken.</h1>'      
   
        
if __name__ == "__main__":
     app.run(debug=True)