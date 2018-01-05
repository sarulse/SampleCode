from flask import Flask
from flask_sqlalchemy import SQLAlchemy


featureapp = Flask(__name__)
featureapp.config.from_object('settings')
db = SQLAlchemy(featureapp)
