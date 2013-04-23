#!/usr/bin/python

import time
import config


def log_debug(string, level, r_id):
    
    if level == 1:
        levelmsg = "Critical"
    elif level == 2:
        levelmsg = "Message"
    
    timestr = time.strftime('%Y-%m-%d %X', time.localtime(time.time()))        
    
    if r_id >= 0:
        error_msg = "[Request:%d] [%s] [%s] %s" % (r_id, timestr, levelmsg, string)
    elif r_id == -1:
        error_msg = "[Main] [%s] [%s] %s" % (timestr, levelmsg, string)
    elif r_id == -2:
        error_msg = "[Remote] [%s] [%s] %s" % (timestr, levelmsg, string)
    elif r_id == -3:
        error_msg = "[PushToQueue] [%s] [%s] %s" % (timestr, levelmsg, string)
    elif r_id == -4:
        error_msg = "[THCheck] [%s] [%s] %s" % (timestr, levelmsg, string)
    elif r_id == -5:
        error_msg = "[Email] [%s] [%s] %s" % (timestr, levelmsg, string)    
    elif r_id == -6:
        error_msg = "[Utility] [%s] [%s] %s" % (timestr, levelmsg, string)
        
        
    if config.debug:
        print error_msg
            
    try:        
        filename = config.log_file_name
        debugfile = open(filename, "a")
        debugfile.write(error_msg.__str__() + '\n')
        debugfile.close()

    except Exception, e:
        pass
