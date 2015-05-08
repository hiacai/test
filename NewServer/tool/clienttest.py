# -*- coding: utf-8 -*-
import time
from socket import AF_INET, SOCK_STREAM, socket
from thread import start_new
from time import sleep
import struct
import json

PORT = 10000
BUFSIZE = 1024
ADDR=(HOST , PORT)
client = socket(AF_INET,SOCK_STREAM)
client.connect(ADDR)

HOST = raw_input("host:")
if not HOST:
    HOST = "127.0.0.1"


def sendData(sendstr, commandId):
    HEAD_0 = chr(78)
    HEAD_1 = chr(37)
    HEAD_2 = chr(38)
    HEAD_3 = chr(48)
    ProtoVersion = chr(9)
    ServerVersion = 0
    data = struct.pack('!sssss3I', HEAD_0, HEAD_1, HEAD_2,
                       HEAD_3, ProtoVersion, ServerVersion,
                       len(sendstr) + 4, commandId)
    return data + sendstr


def resolveRecvdata(data):
    dataList = data.split("N%&0")
    for dataStr in dataList:
        if not dataStr:
            continue
        head = struct.unpack('!sssss3I', dataStr[:17])
        length = head[5]
        dataStr = dataStr[13:13 + length]
        try:
            dataDict = json.loads(dataStr, "utf-8")
            dataJson = json.dumps(dataDict, ensure_ascii=False, indent=2, sort_keys=True)
            print "!!!!!!!!!!recv:!!!!!!!!!!\n%s" % dataJson
        except BaseException:
            dataStr = unicode(dataStr, "utf-8")
            print '!!!!!!!!!!recv:!!!!!!!!!!\n%s\nkey:' % dataStr,
    return data


def recv():
    while True:
        try:
            message = client.recv(BUFSIZE)
            resolveRecvdata(message)
        except BaseException, e:
            time.sleep(1)
            print e


def send():
    global client

    def _send(key):
        global client
        if not isinstance(key, int):
            return
        if key == 101:
            msg = sendData('{"name":"hiacai","pwd":"123"}', key)
        elif key == 102:
            msg = sendData('{"name":"hiacai","pwd":"123"}', key)
        else:
            json = raw_input("json:")
            msg = sendData(msg, key)
        client.sendall(msg)
        return

    while True:
        try:
            key = int(raw_input("key:"))
            if _send(key):
                break
            sleep(0.3)
        except Exception, e:
            raw_input(e)
            break

if __name__ == "__main__":
    start_new(recv, ())
    send()
