from application import db
from application.models import FeatureRequests

db.create_all()

print("FeatureDB created.")