#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
Created on 2015/5/8
@author: Linwencai
"""
from json import dumps
from twisted.python import log
from datetime import datetime, date
from firefly.server.globalobject import GlobalObject
from firefly.utils.services import CommandService


# def mapTarget(self, target):
#     key = int((target.__name__).split('_')[-1])
#     self._targets[key] = target
# CommandService.mapTarget = mapTarget

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
    else:
        Data['Message'] = message
    jsonData = dumps(Data, separators=(',', ':'), default=jsonDefault)
    if isSend is False:
        return jsonData
    return GlobalObject().remote['gate'].callRemote("pushObject", topicID, jsonData, [dynamicId])
    