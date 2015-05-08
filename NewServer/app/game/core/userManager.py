#coding:utf8
"""
Created on 2014-07-21
@author: Linwencai
"""
from firefly.utils.singleton import Singleton
from firefly.dbentrust.madminanager import MAdminManager
from firefly.dbentrust.mmode import MAdmin


class UserManager:
    __metaclass__ = Singleton  # 单例

    def __init__(self):
        self._users = {}  # 玩家字典
        self.memUsers = MAdmin("tb_user", "uuid")  # 玩家表mem实例
        self.memUsers.insert()
        MAdminManager().registe(self.memUsers)
        return
