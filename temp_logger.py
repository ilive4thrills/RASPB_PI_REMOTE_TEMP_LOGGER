# Joseph Garcia
# 4/25/15
# This is a python script to insert ADC data into the datetimetemp sqlite3 database
#!/usr/bin/python
import sqlite3   # for interaction with sqliote3 databases
import sys        
import fileinput  # reading stdin
import datetime   # for current date and time

def ADC_to_T_F(ADC):    # function to convert ADC to Fahrenheit temperature
	ADC = int(ADC,0)    
	T_Kel = (ADC - 60)/7.4     #Kelvin-ADC relationship
	T_F = (T_Kel - 273.15)*1.8 + 32  #Standard Fahrenheit-Kelvin relationship
	return T_F

ADC = 0
T_Kel = 0                 # variable initializations
T_F = 0
datetimetemp = 0
db_conn = 0
db_cursor = 0

#grab the current date and time
now = datetime.datetime.now()
year = now.year
month = now.month
day = now.day
hour = now.hour
minute = now.second
datestr = "%s-%s-%s" % (year,month,day)
timestr = now.strftime("%I") +":" + now.strftime("%M")  # create "texts" for date and time of reading
datetimetemp = 0

for val in sys.stdin:
	ADC = val           
if (ADC == None):    # abort the reading if the reading comes back "Null"
	exit()
T_F = ADC_to_T_F(ADC)

datetimetemp = [datestr,timestr,T_F]

db_conn = sqlite3.connect('/home/joseph/Desktop/temp_logger/datetimetemp.db') # create a database handle

if ((db_conn == None) or (db_conn < 0)):
	print "Unable to create db handle.",   #check database handle
	sys.exit(0)

db_cursor = db_conn.cursor()
if ((db_cursor == None) or (db_cursor < 0)):     #create cursor to apply actions to database
	print "Unable to create db cursor.",
	sys.exit(0)

db_cursor.execute('INSERT INTO datetimetemp VALUES (?,?,?)',datetimetemp) # insert new data entries into table (already created)
db_conn.commit()     # after the changes have been made, make them permanent
db_conn.close()  # close the connection since all has been committed (or failed)
