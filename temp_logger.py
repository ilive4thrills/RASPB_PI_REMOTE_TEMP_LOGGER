# Joseph Garcia
# 4/25/15
# This is a python script to insert ADC data into the tempdata sqlite3 database
#!/usr/bin/python
import sqlite3
import sys
import fileinput
import datetime

#Function prototypes

def ADC_to_T_F(ADC):
	ADC = int(ADC,0)
	T_Kel = (ADC - 85)/7.4
	T_F = (T_Kel - 273.15)*1.8 + 32
	return T_F

#grab the current date and time
now = datetime.datetime.now()
year = now.year
month = now.month
day = now.day
hour = now.hour
minute = now.second
datestr = "%s-%s-%s" % (year,month,day)
timestr = now.strftime("%I") +":" + now.strftime("%M")
datetimetemp = 0

#read in the data from STDIN first.
#From calibration, ADC = 7.4*TEMP_IN_K
ADC = 0
T_Kel = 0
T_F = 0

for val in sys.stdin:
	ADC = val           #error check!!!
if (ADC == None):
	exit()
T_F = ADC_to_T_F(ADC)

datetimetemp = [datestr,timestr,T_F]

db_conn = sqlite3.connect('/home/joseph/Desktop/temp_logger/datetimetemp.db')
db_cursor = db_conn.cursor()
# Create table
db_cursor.execute('INSERT INTO datetimetemp VALUES (?,?,?)',datetimetemp)

db_conn.commit()     # after the changes have been made, make them permanent
db_conn.close()  # close the connection since all has been committed (or failed)
