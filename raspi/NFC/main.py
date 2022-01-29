import nfc
import binascii

suica=nfc.clf.RemoteTarget("212F")
suica.sensf_req=bytearray.fromhex("0000030000")


while True:
    with nfc.ContactlessFrontend("usb") as clf:
        target=clf.sense(suica,iterations=5,interval=1.0)
        while target:
            tag=nfc.tag.activate(clf,target)
            tag.sys=3
            idm=binascii.hexlify(tag.idm)
            print(idm.decode())
            break