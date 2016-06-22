#!/usr/bin/python

import sys
import fileinput

ct = 0
for value in sys.stdin:
	if (value != None):
		ct = ct + 1
	if (value == None):
		sys.exit("Unable to read file")
print ct

