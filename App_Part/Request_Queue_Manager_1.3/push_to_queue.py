#!/usr/bin/python

import log
import utility
    
    
def push():
    try:
        procList = utility.get_single_running_proc()
        
        if len(procList) > 0:
            for eachItem in procList:
                utility.insert_to_queue(eachItem[1], eachItem[2])
                utility.disable_proc(eachItem[0], eachItem[1], 0)
                
        
        procList = utility.get_periodic_running_proc()
        
        if len(procList) > 0:
            for eachItem in procList:
                utility.insert_to_queue(eachItem[1], eachItem[2])
                utility.update_next_runtime(eachItem[0])
                
    except Exception as err:
        log.log_debug(err, 1, -3)
        log.log_debug("Error in push()", 1, -3)