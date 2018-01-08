from flask import Flask, request, flash, url_for, redirect, render_template, abort, jsonify
from sqlalchemy import and_
from featureapp import db
from featureapp.models import FeatureRequests

application = Flask(__name__)
application.debug=True

#home page redirect to add request form
@application.route("/")
def main():
    return redirect('/showFeatureRequestForm')

#Create request form     
@application.route("/showFeatureRequestForm")
def show_request_form():
    return render_template('add.html')

#Show all feature requests
@application.route('/showAllFeatureRequests')
def show_all_requests():    
    return render_template('showAllRequests.html',feature_requests=FeatureRequests.query.order_by(FeatureRequests.id.desc()).all())

#Show last entered feature requess
@application.route('/showEnteredRequest')
def show_last_inserted_requests():    
    return render_template('showLastEnteredRequest.html',
       last_feature_request=FeatureRequests.query.order_by(FeatureRequests.id.desc()).first()
    )

#Function to check if client has entered same priority 
@application.route('/update/<cname>,<cpriority>')
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

#Adding requests to DB
@application.route('/addFeatureRequests', methods=['GET', 'POST'])
def add_request():
    try:
        matching_priority = False
        if request.method == 'POST':
            client = request.json['client_name']
            priority = request.json['client_priority']   
            #Check if client has the same priority for other requests
            print("Matching Priority")
            matching_priority =  check_client_priority(client,priority)
            print(matching_priority)
            if matching_priority:
                freq = FeatureRequests.query.filter(FeatureRequests.client_name == client)
                if (freq.count() > 0):
                    #update priority order for the same client
                    for row in freq:                                        
                        row.client_priority += 1      
            #insert new requests            
            feature_requests = FeatureRequests(request.json['title'], request.json['description'], request.json['client_name'],            
                                               request.json['client_priority'], request.json['target_date'], request.json['product_area'])
            
            
            db.session.add(feature_requests)
            db.session.commit()         
            
            return jsonify({'title': request.json['title'],
                            'description': request.json['description'],
                            'client_name' : request.json['client_name'],
                            'client_priority' : request.json['client_priority'],
                            '"target_date' : request.json['target_date'],
                            'product_area' : request.json['product_area']                        
                            })                  
        else:
            flash("Error: posting feature requests")        
    except Exception as e:
        db.session.rollback()
        return {'error': str(e)}
    finally:
        db.session.close()

if __name__ == '__main__':
    db.create_all()
    application.run()