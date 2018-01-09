from flask import Flask, jsonify
from flask_sqlalchemy import SQLAlchemy
import os, sys
import unittest

app = Flask(__name__)
app.config.from_pyfile('testsettings.cfg')
db = SQLAlchemy(app)
     
class SetUpTest(unittest.TestCase):
    
    
    def connect_db():
        try:
            db.session.query("1").from_statement("SELECT 1").all()
            return '<h1>It works.</h1>'
        except:
            return '<h1>Something is broken.</h1>'      
        

    def setUp(self):        
        db.create_all()

    def tearDown(self):
        db.session.remove()
        db.drop_all()
        
    def test_main_page(self):
        response = self.app.get('/', follow_redirects=True)
        self.assertEqual(response.status_code, 200)    
        
if __name__ == "__main__":
    unittest.main()