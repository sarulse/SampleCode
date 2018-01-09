
from flask import Flask, request, flash, url_for, redirect, render_template, abort
from flask_sqlalchemy import SQLAlchemy
from sqlalchemy.dialects import mysql
from sqlalchemy.dialects.mysql import TINYINT, LONGTEXT, VARCHAR, DATE, ENUM   


import pymysql


app = Flask(__name__)
app.config.from_pyfile('db.cfg')
db = SQLAlchemy(app)


@app.route("/")
def hello():   
    return "Hello WOppprld!"
"""
@app.route("/show_request_form")
def show_request_form():
    return render_template('requestForm.html')

#test db connection
@app.route('/db')
def connect_db():
    try:
        db.session.query("1").from_statement("SELECT 1").all()
        return '<h1>It works.</h1>'
    except:
        return '<h1>Something is broken.</h1>'  
    

# map models
class FeatureRequests(db.Model):  
    __tablename__ = 'requests'
    id = db.Column('request_id', mysql.TINYINT, primary_key=True, autoincrement=True)
    title = db.Column(mysql.VARCHAR(80), unique=True, nullable=False)
    description = db.Column(mysql.LONGTEXT, nullable=False)
    client_name = db.Column(mysql.ENUM('Client A', 'Client B', 'Client C'), nullable=False)
    client_priority = db.Column(mysql.TINYINT, unique=True, nullable=False)
    target_date = db.Column(mysql.DATE, nullable=False)
    product_area = db.Column(mysql.ENUM('Policies', 'Billing','Claims','Reports'), nullable=False)    

    def __init__(self, title, description, client_name, client_priority, target_date, product_area):        
        self.title = title
        self.description = description
        self.client_name = client_name
        self.client_priority = client_priority
        self.target_date = target_date
        self.product_area = product_area

    def __repr__(self):
        return '<FeatureRequests {0} {1}: {5}>'.format(self.title,
                                               self.description,
                                               self.client_name,
                                               self.client_priority,
                                               self.target_date,
                                               self.product_area)
    

    
@app.route('/feature_requests/add', methods=['GET', 'POST'])
def add_request():
    """
    cur = db.execute('insert into requests (title, description, client_name, client_priority, target_date, product_area) values (?, ?, ?, ?, ?,?)',
               [request.json['title'], request.json['description'], request.json['client_name'],
                request.json['client_priority'], request.json['target_date'], request.json['product_area']])
    db.commit()
    request_id = cur.lastrowid
    """
    if request.method == 'POST':
        feature_requests = FeatureRequests(request.form['title'], request.form['description'], request.form['client_name'],
                                           request.form['client_priority'], request.form['target_date'],request.form['product_area'])
        db.session.add(feature_requests)
        db.session.commit()
        flash(u'Feature Request was successfully created')
        
        """
        return jsonify({"title": request.json['title'],
                        "description": request.json['description'],
                        "client_name" : request.json['client_name'],
                        "client_priority" : request.json['client_priority'],
                        "target_date" : request.json['target_date'],
                        "product_area" : request.json['product_area'],
                        "id": id})
        """
        return jsonify({"title": request.form['title'],
                        "description": request.form['description'],
                        "client_name" : request.form['client_name'],
                        "client_priority" : request.form['client_priority'],
                        "target_date" : request.form['target_date'],
                        "product_area" : equest.form['product_area'],
                        "id": id})
                    
    return render_template('requestForm.html')

"""
if __name__ == "__main__" or __name__ == "db":
    app.run(debug=True)
"""    
 
"""