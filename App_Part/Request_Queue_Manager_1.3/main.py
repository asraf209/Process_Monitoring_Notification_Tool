#!/usr/bin/python

import utility
import config
import time
import datetime
import os
import log
import push_to_queue
import th_check


def main():
    
    t = 0
    
    utility.connect()
    
    while True:
        
        if not utility.db_lock():
            
            try:
            
                utility.update_db_lock('active', 'monitor', 'start')
                
                n = utility.running_proc()
                
                if n < config.max_running_proc:
                    new_requests = utility.get_new_requests()
                    
                    for item in new_requests:                        
                        if n < config.max_running_proc:
                            r_id=item[0]
                            req_str=item[1].strip()
                            args=item[2]
                            if args == None or args == '':
                                args = 'NULL'
                            else:
                                args = item[2].strip()
                            
                            host, port, script, username, password = utility.get_host_script_user_pass(req_str)
                            
                            if script != None:
                                ext = script[script.rfind(".")+1:]                                
                                cmd_syntax = utility.get_cmd(ext)                                
                                
                                if cmd_syntax == None or cmd_syntax == '':
                                    cmd_syntax="NULL"
                                else:
                                    cmd_syntax=cmd_syntax.strip()
                                
                                utility.update_status('Starting', r_id)
                                utility.update_time('start', r_id, utility.get_current_time())
                                
                                log.log_debug("Starting Request:%s, ID:%d" % (req_str, r_id), 2, -1)                                                                
                                                                                            
                                os.system("./remote_process.py -thread -tid %s %s %s %s %s %s %s %s %s > /dev/null &" % (str(r_id), host, str(port), username, password, req_str, script, args, cmd_syntax))
                                
                                n += 1
                                
                                time.sleep(2)
    
                            else:                                
                                log.log_debug("No script entry found for Request:%s, ID:%d" % (req_str, r_id), 2, -1)                                
                                
                        else:
                            log.log_debug("No more request can be handled....reached its max", 2, -1)                                                            
                
                else:
                    log.log_debug("No more request can be handled....reached its max", 2, -1)
                    
                
            except Exception as err:
                log.log_debug(err, 1, -1)
                log.log_debug("Error in main()", 1, -1)
                utility.rollback_conn()
            
                
            finally:
                utility.update_db_lock('inactive', 'monitor', 'end')
                t += 1
        
                
        push_to_queue.push()                        # take active proc, both single and periodic, from process_list table and insert them to queue
        
        
        time.sleep(config.sleep_time)
        
        
        # Call th_check.py in every 20min approx.        
        if t * config.sleep_time >= config.th_check_period:
            t = 0
            th_check.check_runtime()
            
        
            
if __name__ == '__main__':
    main()