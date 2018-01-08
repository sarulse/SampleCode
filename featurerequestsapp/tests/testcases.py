import unittest
import urllib2
from flask import Flask, request,url_for
from flask_sqlalchemy import SQLAlchemy
from flask_testing import TestCase
from featureapp import db
from featureapp.models import FeatureRequests
from collections import defaultdict, Counter


class TestBasic(unittest.TestCase):
    def test_setUp(self):
        db.create_all()

    def test_tearDown(self):
        db.session.remove()
        #db.drop_all()
        
    def test_create_app(self):
        application = Flask(__name__)
        application.config['TESTING'] = True
        application.config.from_object('testsettings')
        db = SQLAlchemy(application)        
        return application
    
    
      
class TestDB(unittest.TestCase):
    #Testing DB connection     
    def test_db_connection(self):
        try:
            db.session.execute('SELECT 1')
            print('DB connection works')
        except:
            print('DB connection fails')
                       
    #Test empty table    
    def test_empty_table(self):
            feature_requests = FeatureRequests.query.all()
            print (len(feature_requests))
            self.assertTrue(len(feature_requests) > 0)           
            
           
    #Test if client priorities are unique and ordered
    def test_client_priority(self):
            freqs = FeatureRequests.query.all()
            client_priority_list = defaultdict(list)
            for row in freqs:
                client_priority_list[row.client_name].append(row.client_priority)               
            
            for client,priority in client_priority_list.items():
                priority_arr = priority
                for val in set(priority_arr):
                        #In case of duplicates
                        if priority_arr.count(val) > 1:
                            print ("Client and their priorities are unordered and duplicate priorities found")
                            self.assertEqual(priority_arr.count(val), 1, 'Duplicate priorities for the same client')
                            
                        
                            
                        
                        
                        
    

            
            
            
      
            
            
    
        
    

if __name__ == '__main__':
    unittest.main()