import pymysql.cursors  
 
# Function return a connection.
def getConnection():
     
    # connection arguments.
    connection = pymysql.connect(host='127.0.0.1',
                                 user='sarul',
                                 password='test2006',                             
                                 db='features',
                                 charset='utf8mb4',
                                 cursorclass=pymysql.cursors.DictCursor)
    return connection