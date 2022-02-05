import nfc
import binascii
import time
from datetime import datetime
import pymysql.cursors

import sys#本番環境時消す

suica=nfc.clf.RemoteTarget("212F")
suica.sensf_req=bytearray.fromhex("0000030000")

# timestamp用日付取得
def getDate():
    dt_now = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    return dt_now

# db登録処理　引数：str(number)->ICカード番号
def dbFunction(number):
    dt_now = getDate()
    db=pymysql.connect(host="localhost", user="dbadmin", password="dbadmin" , cursorclass=pymysql.cursors.DictCursor)

    try:
        cursor=db.cursor()
        cursor.execute("USE futoppara")
        db.commit()
        sql=('INSERT INTO kintai(staff_id, datetime) VALUES((SELECT staff_id from card WHERE card_num = "'+ number +'"),"'+ dt_now + '")')
        cursor.execute(sql)
        db.commit()
        if cursor != None:# 登録成功時
            print('DB inserted! | '+dt_now)
    except Exception as err:#例外エラー発生時
        print(str(err))
        pass
    finally:
        db.close()
        cursor.close()

# NFCスキャン処理
def nfcScan():
    try:
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
    except Exception as err:#例外エラー発生時
        print(str(err))
        pass
    except KeyboardInterrupt:
        sys.exit(0)#本番環境時消す


if __name__ == '__main__':
    nfcScan()
