#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
Created on 2015/5/8
@author: Linwencai
"""
from gfirefly.server.globalobject import GlobalObject
from gfirefly.server.globalobject import netserviceHandle
from gfirefly.utils.services import CommandService
from gtwisted.utils import log

def callTarget(self, targetKey, *args, **kw):
    target = self.getTarget(0)
    return target(targetKey, *args, **kw)
CommandService.callTarget = callTarget

@netserviceHandle
def Forwarding_0(keyname, _conn, data):
    '''转发服务器.用来接收客户端的消息转发给其他服务
    '''
    print "Forwarding_0", keyname, data
    message = GlobalObject().remote['gate'].callRemote("forwarding",
                                                       keyname, _conn.transport.sessionno, data)
    return message

