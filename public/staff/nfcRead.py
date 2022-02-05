import nfc
import binascii
import time

import sys#本番環境時消す

suica=nfc.clf.RemoteTarget("212F")
suica.sensf_req=bytearray.fromhex("0000030000")


# NFCスキャン処理
def nfcScan():
    with nfc.ContactlessFrontend("usb") as clf:
        target=clf.sense(suica,iterations=3,interval=1.0)
        while target:
            tag=nfc.tag.activate(clf,target)
            tag.sys=3
            idm=binascii.hexlify(tag.idm)
            print(str(idm.decode()))
            time.sleep(3)
            exit()


if __name__ == '__main__':
    nfcScan()
