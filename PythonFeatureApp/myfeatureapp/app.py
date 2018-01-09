from flask import Flask, request, flash, url_for, redirect, render_template, abort, jsonify
from flask_sqlalchemy import SQLAlchemy
from sqlalchemy import and_

app = Flask(__name__)
app.config.from_pyfile('settings.cfg')
db = SQLAlchemy(app)

#After app is created import database Class Feature Requests
from models import FeatureRequests

@app.route("/")
def hello():   
    return "Hello WOppprld!"

@app.route("/showFeatureRequestForm")
def show_request_form():
    return render_template('add.html')

@app.route('/showAllFeatureRequests')
def show_all_requests():    
    return render_template('showAllRequests.html',
       feature_requests=FeatureRequests.query.order_by(FeatureRequests.id.desc()).all()
    )
@app.route('/showEnteredRequest')
def show_last_inserted_requests():    
    return render_template('showLastEnteredRequest.html',
       last_feature_request=FeatureRequests.query.order_by(FeatureRequests.id.desc()).first()
    )
#to check if client has entered same priority 
@app.route('/update/<cname>,<cpriority>')
def check_client_priority(cname,cpriority):
    found_match_priority = False
    fr = FeatureRequests.query.filter(
    and_(
        FeatureRequests.client_name == cname,
        FeatureRequests.client_priority == cpriority
    ))  
    if (fr.count() > 0):
        found_match_priority = True
        print ("Row Exist")
    return found_match_priority        


@app.route('/addFeatureRequests', methods=['GET', 'POST'])
def add_request():
    try:
        matching_priority = False
        if request.method == 'POST':
            print ("I am posting")
            print (request.json['title'])
            print(request.json['description'])
            print (request.json['client_name'])
            print (request.json['client_priority'])
            print (request.json['target_date'])
            print (request.json['product_area'])
            client = request.json['client_name']
            priority = request.json['client_priority']                     
            #Check if client has the same priority for other requests
            matching_priority =  check_client_priority(client,priority)
            if matching_priority:
                freq = FeatureRequests.query.filter(FeatureRequests.client_name == client)
                if (freq.count() > 0):
                    #update priority
                    for row in freq:                                        
                        row.client_priority += 1      
            #insert new requests 
            feature_requests = FeatureRequests(request.json['title'], request.json['description'], request.json['client_name'],            
                                               request.json['client_priority'], request.json['target_date'], request.json['product_area'])
            #db.drop_all()
            db.create_all()
            db.session.add(feature_requests)
            db.session.commit()            
            
            return jsonify({"title": request.json['title'],
                            "description": request.json['description'],
                            "client_name" : request.json['client_name'],
                            "client_priority" : request.json['client_priority'],
                            "target_date" : request.json['target_date'],
                            "product_area" : request.json['product_area'],                        
                            })                  
        else:
            print("Error: posting feature requests")
        return redirect(url_for('showAllFeatureRequests'))
    except Exception as e:
        return {'error': str(e)}

if __name__ == '__main__':
    app.run()