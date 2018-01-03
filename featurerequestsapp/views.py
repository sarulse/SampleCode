from flask import Flask, request, flash, url_for, redirect, render_template, abort, jsonify
from flask_sqlalchemy import SQLAlchemy

import pymysql, datetime

app = Flask(__name__)
app.config.from_pyfile('db.cfg')
app.secret_key = 'super secret key'
app.config['SESSION_TYPE'] = 'filesystem'
db = SQLAlchemy(app)

import models

@app.route("/")
def hello():   
    return "Hello WOppprld!"

#test db connection
@app.route('/db')
def connect_db():
    try:
        db.session.query("1").from_statement("SELECT 1").all()
        return '<h1>It works.</h1>'
    except:
        return '<h1>Something is broken.</h1>'
    
@app.route("/showFeatureRequestForm")
def show_request_form():
    return render_template('add.html')

@app.route('/showAllFeatureRequests')
def show():
    
    return render_template('showAllRequests.html',
       feature_requests=FeatureRequests.query.order_by(FeatureRequests.id.desc()).all()
    )
    """ 
    feature_requests=FeatureRequests.query.order_by(FeatureRequests.id.desc()).all()   
    return jsonify(feature_requests)
   
    cur = db.execute('select * from FeatureRequests order by id asc')
    entries = [dict(
               title=row[0],
                description=row[1],
                client_name=row[2],
                client_priority=row[3],
                target_date=row[4],
                product_area = row[5])
                for row in cur.fetchall()]
    return jsonify(tasks=entries)
    """


#map models

    

@app.route('/addFeatureRequests', methods=['GET', 'POST'])
def add_request():    
    if request.method == 'POST':
        print ("I am posting")
        print (request.json['title'])
        print(request.json['description']);
        print (request.json['client_name'])
        print (request.json['client_priority'])
        print (request.json['target_date'])
        print (request.json['product_area'])
        target_date = request.json['target_date']
        
        print (target_date);
        
        feature_requests = FeatureRequests(request.json['title'], request.json['description'], request.json['client_name'],
                                           request.json['client_priority'], request.json['target_date'], request.json['product_area'])
        #db.drop_all()
        db.create_all()
        db.session.add(feature_requests)
        db.session.commit()
        flash(u'Feature Request was successfully created')
        
        return jsonify({"title": request.json['title'],
                        "description": request.json['description'],
                        "client_name" : request.json['client_name'],
                        "client_priority" : request.json['client_priority'],
                        "target_date" : request.json['target_date'],
                        "product_area" : request.json['product_area'],                        
                        })
               
    else:
        print("Error in posting the data")
    return redirect(url_for('showAllFeatureRequests'))
   
   
    



if __name__ == '__main__':
    app.run()