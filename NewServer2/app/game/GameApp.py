#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
Created on 2015/5/8
@author: Linwencai
"""
from json import dumps
from datetime import datetime, date
from gtwisted.utils import log
from gfirefly.server.globalobject import GlobalObject
from gfirefly.utils.services import CommandService

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


def SendMessage(topicID, dynamicId, state, message, isSend=False):
    Data = {'State': state,
            }
    if state:
        Data['Data'] = message
        log.msg("Send:%s dynamicId:%s topicID%s" % (Data, dynamicId, topicID))
    else:
        Data['Message'] = message
        log.err("Send:%s dynamicId:%s topicID:%s" % (Data, dynamicId, topicID))
    jsonData = dumps(Data, separators=(',', ':'), default=jsonDefault)
    if isSend is False:
        return jsonData
    return GlobalObject().remote['gate'].callRemote("pushObject", 999, jsonData, [dynamicId])
    