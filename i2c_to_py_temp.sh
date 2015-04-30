#!/bin/bash

/usr/sbin/i2cget -y 1 0x41 0x006 w | /usr/bin/python /home/joseph/Desktop/temp_logger/temp_logger.py
