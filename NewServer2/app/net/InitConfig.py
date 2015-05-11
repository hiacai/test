#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
Created on 2015/5/8
@author: Linwencai
"""
from gfirefly.server.globalobject import GlobalObject
from gfirefly.netconnect.datapack import DataPackProtoc


def callWhenConnLost(conn):
    """ 连接断开时回调
    """
    dynamicId = conn.transport.sessionno
    GlobalObject().remote['gate'].callRemote("netconnlost", dynamicId)

GlobalObject().netfactory.doConnectionLost = callWhenConnLost
dataprotocl = DataPackProtoc(78, 37, 38, 48, 9, 0)
GlobalObject().netfactory.setDataProtocl(dataprotocl)


def loadModule():
    import NetApp
    import GateNodeApp
