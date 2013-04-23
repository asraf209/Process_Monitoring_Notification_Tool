#!/usr/bin/python

import MySQLdb
import time
import datetime
import config
import log
import os



#------------------------- main ---------------------------#

def connect():
    i = 0
    while i < config.max_attempts:
        try:
            global mysqlConn
            global mysqlCursor
            mysqlConn = MySQLdb.connect(host=config.DB_HOST, port=config.DB_PORT, user=config.DB_USER, passwd=config.DB_PASS)                
            mysqlCursor = mysqlConn.cursor()
            
            break
        except Exception as err:            
            log.log_debug(err, 1, -6)
            log.log_debug("Error in connecting MySQL DB..Trying again", 1, -6)
            i += 1
            time.sleep(2)



def db_lock():
    try:
        query = "SELECT name FROM procmon.users WHERE status='active' AND time_to_sec(TIMEDIFF(now(), start_time))/60 < 5"
        mysqlCursor.execute(query)  
        if mysqlCursor.rowcount > 0:
            return True
        else:
            return False
        
    except Exception as err:
        log.log_debug(err, 1, -6)
        log.log_debug("Error in db_lock()", 1, -6)


def update_db_lock(status, name, flag):   
    try:
        #mysqlCursor.execute("""
        #                    UPDATE procmon.users
        #                    SET status=%s
        #                    WHERE name=%s
        #                    """, (status, name))
        
        if flag == 'start':                
            mysqlCursor.execute("""
                                UPDATE procmon.users
                                SET status=%s, start_time=now()
                                WHERE name=%s
                               """, (status, name))
        elif flag == 'end':
            mysqlCursor.execute("""
                                UPDATE procmon.users
                                SET status=%s, end_time=now()
                                WHERE name=%s
                                """, (status, name))
        
        mysqlConn.commit()        
                
    except Exception as err:                
        log.log_debug(err, 1, -6)
        log.log_debug("Error in update_db_lock()", 1, -6)
        mysqlConn.rollbak()



def running_proc():
    try:
        query = "SELECT count(request) FROM procmon.request_queue WHERE status = 'Running'"
        mysqlCursor.execute(query)  
        return mysqlCursor.rowcount
    
    except Exception as err:
        log.log_debug(err, 1, -6)
        log.log_debug("Error in running_proc()", 1, -6)
        


def get_new_requests():    
    try:
        query = "SELECT id, request, args FROM procmon.request_queue WHERE status is NULL or status=''"
        mysqlCursor.execute(query)
        
        if mysqlCursor.rowcount > 0:
            return mysqlCursor.fetchall()
        else:
            return []
        
    except Exception as err:
        log.log_debug(err, 1, -6)
        log.log_debug("Error in get_new_requests()", 1, -6)
        


def get_host_script_user_pass(req_str):    
    try:
        query = "SELECT host, port, script, username, password FROM procmon.request_to_process WHERE request_token='" + req_str + "'"
        mysqlCursor.execute(query)		
        res = mysqlCursor.fetchone()        
    
        if res != None:
            return res[0].strip(), res[1], res[2].strip(), res[3].strip(), res[4].strip()
        else:
            return None, None, None, None, None
        
    except Exception as err:
        log.log_debug(err, 1, -6)
        log.log_debug("Error in get_host_script_user_pass()", 1, -6)
        


def get_cmd(ext):
    try:
        query = "SELECT cmd FROM procmon.cmd_format WHERE file_ext = '" + ext.strip() + "'"
        mysqlCursor.execute(query)	
        #return mysqlCursor.fetchone()[0]
        cmd = mysqlCursor.fetchone()
        if cmd != None:
            return cmd[0]            
        else:
            return cmd
        
    except Exception as err:
        log.log_debug(err, 1, -6)
        log.log_debug("Error in get_cmd()", 1, -6)        



def get_current_time():    
    now = datetime.datetime.now()
    now=now.strftime("%Y-%m-%d %H:%M:%S")        
    return now



def update_status(flag, id):
    try:                
        mysqlCursor.execute("""
                            UPDATE procmon.request_queue
                            SET status=%s
                            WHERE id=%s
                            """, (flag, id))
        mysqlConn.commit()
                
    except Exception as err:                
        log.log_debug(err, 1, -6)
        log.log_debug("Error in update_status()", 1, -6)
        mysqlConn.rollback()
                


def update_time(flag, id, time):
    try:
        if flag == 'start':
            mysqlCursor.execute("""
                                UPDATE procmon.request_queue
                                SET start_time=%s
                                WHERE id=%s
                                """, (time, id))
            mysqlConn.commit()
        
        elif flag == 'end':
            mysqlCursor.execute("""
                                UPDATE procmon.request_queue
                                SET end_time=%s
                                WHERE id=%s
                                """, (time, id))
            mysqlConn.commit()
                
    except Exception as err:                
        log.log_debug(err, 1, -6)
        log.log_debug("Error in update_time()", 1, -6)
        mysqlConn.rollback()


#-------------------------------------------------------#






#---------------------email_sender---------------------#

def get_contact_list(req_str):
    try:
        query = "SELECT contacts FROM procmon.request_to_process WHERE request_token='" + req_str + "'"
        mysqlCursor.execute(query)  
        res = mysqlCursor.fetchone()
    
        if(res!=None):
            return res[0].strip()
        else:
            return config.from_addr
        
    except Exception as err:
        log.log_debug(err, 1, -6)
        log.log_debug("Error in get_contact_list()", 1, -6)
        
#-------------------------------------------------------#





#-------------------push_to_queue----------------------#

def get_single_running_proc():
    try:
        query = "SELECT id, request_token, args FROM procmon.request_to_process WHERE status=1 AND timer=0"
        mysqlCursor.execute(query)
    
        if mysqlCursor.rowcount > 0:
            return mysqlCursor.fetchall()
        else:
            return []
        
    except Exception as err:
        log.log_debug(err, 1, -6)
        log.log_debug("Error in get_single_running_proc()", 1, -6)        
        



def get_periodic_running_proc():
    try:
        query = """
                SELECT id, request_token, args, entry_time, next_run_time, timer FROM procmon.request_to_process
                WHERE
                    ( status = 1 AND timer > 0 )
                AND
                    ( next_run_time IS NULL OR ( NOW() - next_run_time ) >= 0 )
                """                
        
        mysqlCursor.execute(query)
    
        if mysqlCursor.rowcount > 0:
            return mysqlCursor.fetchall()
        else:
            return []
        
    except Exception as err:
        log.log_debug(err, 1, -6)
        log.log_debug("Error in get_single_running_proc()", 1, -6)        
        



def insert_to_queue(request_name, arguments):
    try:
        mysqlCursor.execute("""
                INSERT INTO procmon.request_queue(request, args)
                VALUES(%s, %s)
                """, (request_name, arguments)                
                )
        
        mysqlConn.commit()
        
    except Exception as err:        
        log.log_debug(err, 1, -6)
        log.log_debug("Error in insert_to_queue()", 1, -6)        
        mysqlConn.rollback()


def disable_proc(id, pname, status):
    try:
        mysqlCursor.execute("""
                UPDATE procmon.request_to_process
                SET status = %s
                WHERE id = %s
                """, (status, id)
                )
        
        mysqlConn.commit()
        
    except Exception as err:        
        log.log_debug(err, 1, -6)
        log.log_debug("Error in disable_proc()", 1, -6)        
        mysqlConn.rollback()
        


def update_next_runtime(id):
    try:
        mysqlCursor.execute("""
                UPDATE procmon.request_to_process
                SET next_run_time = DATE_ADD(NOW(), INTERVAL timer SECOND)
                WHERE id = %s
                """, (id)
                )
        
        mysqlConn.commit()
        
    except Exception as err:        
        log.log_debug(err, 1, -6)
        log.log_debug("Error in update_next_runtime()", 1, -6)        
        mysqlConn.rollback()


#-----------------------------------------------------#






#---------------------th_check------------------------#

def get_th_crossed_proc():
    try:
        query = """
                SELECT
                    a.id, a.request, time_to_sec(TIMEDIFF(now(),a.start_time))/60 as DIFF, b.th, a.status, b.script
                FROM
                    procmon.request_queue a, procmon.request_to_process b
                WHERE
                    (a.status = 'Starting' OR a.status = 'Running')
                    AND
                    (a.request = b.request_token)
                HAVING
                    DIFF > b.th
                """                
        
        mysqlCursor.execute(query)
    
        if mysqlCursor.rowcount > 0:
            return mysqlCursor.fetchall()
        else:
            return []
        
    except Exception as err:
        log.log_debug(err, 1, -6)
        log.log_debug("Error in get_th_crossed_proc()", 1, -6)        
        
    
#-----------------------------------------------------#




def rollback_conn():
    mysqlConn.rollback()


def close_conn():    
    mysqlCursor.close()
    mysqlConn.close()