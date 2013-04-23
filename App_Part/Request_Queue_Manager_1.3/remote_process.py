#!/usr/bin/python

import utility
import config
import datetime
import sys
import log
import email_sender
import ssh


def start():
    
    utility.connect()
    
    try:
        r_id=int(sys.argv[3])
        
        hostname=sys.argv[4]        
        port=int(sys.argv[5])        
        username=sys.argv[6]        
        password=sys.argv[7]
        
        req_str=sys.argv[8]        
        script=sys.argv[9]        
        args=sys.argv[10]        
        cmd_syntax=sys.argv[11]        
        
        log.log_debug("%s %s %s %s %s %s %s %s %s" % (str(r_id), hostname, str(port), username, password, req_str, script, args, cmd_syntax), 2, -2)        

        if script.lower().rfind(".c") > 0:
            script = script[:script.lower().rfind(".c")]            
        else:	
            if script.lower().rfind(".cpp") > 0:
                script = script[:script.lower().rfind(".cpp")]		
                
        #print hostname+", "+str(port)+", "+username+", "+password+", "+req_str+", "+script+", "+args+", "+cmd_syntax+", "+str(r_id)
            

        if cmd_syntax == "NULL":
            if args == "NULL":
                cmd = script
            else:                        
                cmd = script + " " + args
        else:                        
            if args == "NULL":
                cmd = cmd_syntax + " " + script
            else:                        
                cmd = cmd_syntax + " " + script + " " + args
        
        
        cmd = cmd + ' ' + 'rid_' + str(r_id)
        
        log.log_debug("%s" % (cmd), 2, -2)
        
        client = ssh.SSHClient()        
        client.set_missing_host_key_policy(ssh.AutoAddPolicy())
        client.connect(hostname, port=port, username=username, password=password)

        utility.update_status('Running', r_id)
        #utility.update_time('start', r_id, utility.get_current_time())                
        
        stdin, stdout, stderr = client.exec_command(cmd)
                
        
        err = stderr.readlines()
        
        errStr = ''        
                
        if len(err) > 0:                                 # if failed to run script
            
            log.log_debug(err, 1, -2)
            log.log_debug("Failed to run script %s; Request:%s ID:%d" % (script, req_str, r_id), 1, -2)
            
            errStr = script + "\n"
            errStr += "Failed to run script. "+"\n"        
            errStr += '; '.join(err)
                
            email_sender.send(r_id, req_str, errStr)
                        
            utility.update_status('Failed', r_id)
            utility.update_time('end', r_id, utility.get_current_time())

        else:                                           # if process runs successfully
            
            output = stdout.readlines()            

            # search for ERROR token
            for line in output:                    
                if line.lower().find("error")!=-1 or line.lower().find("fail")!=-1 or line.lower().find("failed")!=-1 or line.lower().find("exception")!=-1:
                    errStr += "> "
                    errStr += line + '\n'
                       
                                
            if len(errStr) > 0:                      # if error found while running script
                
                log.log_debug("Error found after running script; Request:%s, ID:%d" % (req_str, r_id), 2, -2)                
                
                errStr = script + '\n' + errStr
                email_sender.send(r_id, req_str, errStr)
                                
                utility.update_status('Error', r_id)
                utility.update_time('end', r_id, utility.get_current_time())
                                
            else:                                   # no error found while running script                                                                 
                utility.update_status('Done', r_id)
                utility.update_time('end', r_id, utility.get_current_time())
                log.log_debug("No error found after running script; Request:%s, ID:%d" % (req_str, r_id), 2, -2)
        
                                                        
        client.close()
        
                
                        
    except Exception as err:
        log.log_debug(err, 1, -2)
        log.log_debug("Error in start(); Request:%s ID:%d" % (req_str, r_id), 1, -2)
        
        errStr = script + '\n' + str(err)
        email_sender.send(r_id, req_str, errStr)
                        
    
    finally:                    
        utility.close_conn()                        
            
            
if __name__ == '__main__':
    start()