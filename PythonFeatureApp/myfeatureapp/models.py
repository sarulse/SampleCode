from app import db
from sqlalchemy.dialects import mysql
from sqlalchemy.dialects.mysql import TINYINT, LONGTEXT, VARCHAR, DATE, ENUM

import datetime


class FeatureRequests(db.Model):  
    __tablename__ = 'requests'
    id = db.Column('request_id', mysql.TINYINT, primary_key=True, autoincrement=True)
    title = db.Column(mysql.VARCHAR(80), nullable=False)
    description = db.Column(mysql.LONGTEXT, nullable=False)
    client_name = db.Column(mysql.ENUM('Client A', 'Client B','Client C','Client D'), nullable=False)
    client_priority = db.Column(mysql.INTEGER, nullable=False)
    target_date = db.Column(mysql.DATE, nullable=False)
    product_area = db.Column(mysql.ENUM('Policies', 'Billing','Claims','Reports'), nullable=False)    

    def __init__(self, title, description, client_name, client_priority, target_date, product_area):        
        self.title = title
        self.description = description
        self.client_name = client_name
        self.client_priority = client_priority
        self.target_date = datetime.datetime.strptime(target_date, '%Y-%m-%d').strftime('%Y-%m-%d')      
        self.product_area = product_area
    
    def __repr__(self):
        return '<FeatureRequests {0} {1}: {5}>'.format(self.title,
                                               self.description,
                                               self.client_name,
                                               self.client_priority,
                                               self.target_date,
                                               self.product_area)