#!/usr/bin/python

import utility
import email_sender
import log


def check_runtime():
    try:
        proc_list = utility.get_th_crossed_proc()
        
        for proc in proc_list:
            
            r_id = proc[0]
            req_str = proc[1].strip()
            diff = proc[2]
            th = proc[3]
            status = proc[4]
            script = proc[5]
            
            status = status + '..TH Crossed'
            
            utility.update_status(status, r_id)
            
            
            msgBody = "Request:" + req_str + ", " + "ID:" + str(r_id) + ", " + "Script:" + script + "\n"
            msgBody += "Runtime TH: " + str(th) + " min" + "\n" + "\n"
            msgBody += "Total time taken by the process upto now: " + str(diff) + " min" + "\n"
            msgBody += "Runtime has crossed the TH value."
            
            
            email_sender.send(r_id, req_str, msgBody)
            
    
    except Exception as err:
        log.log_debug(err, 1, -4)
        log.log_debug("Error in check_runtime()", 1, -4)

