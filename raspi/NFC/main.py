import nfc
import binascii
import time
from datetime import datetime

import pymysql.cursors

suica=nfc.clf.RemoteTarget("212F")
suica.sensf_req=bytearray.fromhex("0000030000")

def getDate():
    dt_now = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    print(dt_now)
    return dt_now

def dbFunction(number):
    dt_now = getDate()
    db=pymysql.connect(host="localhost", user="dbadmin", password="dbadmin" , cursorclass=pymysql.cursors.DictCursor)
    cursor=db.cursor()
    cursor.execute("USE futoppara")
    db.commit()
    sql=('INSERT INTO kintai(user_id, datatime) VALUES((SELECT user_id from card WHERE card_num = "'+ number +'"),"'+dt_now+'")')
    cursor.execute(sql)
    db.commit()
    db.close()
    if cursor != None:
        print('')
    cursor.close()

def nfcScan():
    while True:
        with nfc.ContactlessFrontend("usb") as clf:
            target=clf.sense(suica,iterations=3,interval=1.0)
            while target:
                tag=nfc.tag.activate(clf,target)
                tag.sys=3
                idm=binascii.hexlify(tag.idm)
                dbFunction(str(idm.decode()))
                time.sleep(5)
                break

if __name__ == '__main__':
    nfcScan()
