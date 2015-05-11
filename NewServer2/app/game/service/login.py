#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
Created on 2015/5/8
@author: Linwencai
"""
from gtwisted.utils import log
from app.game.GameApp import GameServiceHandle, SendMessage

@GameServiceHandle
def register_101(key, dynamicId, argument):
    """ 注册协议.
    """
    log.msg("register_101 dynamicId:%s argument:%s" % (dynamicId, argument))
    name = argument.get('name')
    pwd = argument.get('pwd')
    return SendMessage(key, dynamicId, 1, {"name": name, "pwd": pwd})

@GameServiceHandle
def login_102(key, dynamicId, argument):
    """ 登录协议.
    """
    log.msg("login_102 dynamicId:%s argument:%s" % (dynamicId, argument))
    return SendMessage(key, dynamicId, 1, {"result": True})

@GameServiceHandle
def logout_103(key, dynamicId, argument):
    """ 下线协议.(客户端断开socket时自动调用)
    """
    log.msg("logout_103 dynamicId:%s argument:%s" % (dynamicId, argument))
    return SendMessage(key, dynamicId, 1, {"result": True})
