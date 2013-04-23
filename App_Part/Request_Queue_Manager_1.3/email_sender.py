#!/usr/bin/python

import config
import utility
import log
import smtplib
from email.mime.text import MIMEText


def send(r_id, req_str, msg_body):        
        
    subject = "WARNING with Request: " + req_str +", " + "ID:" + str(r_id)
    contact_list = utility.get_contact_list(req_str)
    
    from_addr = config.from_addr
    #to_addr  = ['asraf.alom02@gmail.com', 'md.asrafulalom@yahoo.com']        
    to_addr = contact_list.split(',')

    msg = MIMEText(msg_body, 'plain')                # Msg Body

    msg['Subject'] = subject
    msg['From'] = config.from_addr_alias
    msg['To'] = ', '.join(to_addr)
        
    username = config.from_username
    password = config.from_password  

    try:
        server = smtplib.SMTP('smtp.gmail.com:587')  
        server.starttls()  
        server.login(username, password)  
        server.sendmail(from_addr, to_addr, msg.as_string())  
        server.quit()
        log.log_debug("Email sent successfully for Request:%s, ID:%d" % (req_str, r_id), 2, -5)
    
    except Exception as err:
        log.log_debug(err, 1, -5)
        log.log_debug("Email sending failed for Request:%s, ID:%d" % (req_str, r_id), 1, -5)
        
