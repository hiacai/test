#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
Created on 2015/5/8
@author: Linwencai
"""
import json
from twisted.python import log
from datetime import datetime, date
from firefly.server.globalobject import GlobalObject
from firefly.utils.services import CommandService

GameService = CommandService("GameServer")
GlobalObject().remote['gate']._reference.addService(GameService)


def GameServiceHandle(target):
    GameService.mapTarget(target)


def jsonDefault(obj):
    """json.dumps支持datetime/date类型
    """
    if isinstance(obj, datetime):
        return obj.strftime('%Y-%m-%d %H:%M:%S')
    elif isinstance(obj, date):
        return obj.strftime('%Y-%m-%d')
    else:
        raise TypeError('%r is not JSON serializable' % obj)


def SendMessage(topicID, dynamicId, state, message, isSend=True):
    Data = {'State': state,
            }
    if state:
        Data['Data'] = message
        log.msg("Send:%s dynamicId:%s topicID%s" % (Data, dynamicId, topicID))
    else:
        Data['Message'] = message
        log.err("Send:%s dynamicId:%s topicID:%s" % (Data, dynamicId, topicID))
    jsonData = json.dumps(Data, separators=(',', ':'), default=jsonDefault)
    if isSend:
        return GlobalObject().remote['gate'].callRemote("pushObject", topicID, jsonData, [dynamicId])
    return jsonData
