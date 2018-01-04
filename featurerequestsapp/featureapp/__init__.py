from flask import Flask
from flask_sqlalchemy import SQLAlchemy
from sqlalchemy import and_

featureapp = Flask(__name__)
featureapp.config.from_object('settings')
db = SQLAlchemy(featureapp)
