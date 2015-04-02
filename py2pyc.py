# -*- coding: cp936 -*-
__author__ = 'Linwencai'
__doc__ = '''
py转pyc
1.直接运行本文件:转换当前目录下的所有py
2.拖拽py到本文件:转换该py
3.拖拽目录到本文件:转换该目录下的所有py

cmd中编译pyc:
python -m py_compile file.py

cmd中编译pyo:
python -O -m py_compile file.py
'''

import os
import sys
import py_compile
import compileall

if len(sys.argv) == 1:
    compileall.compile_dir(os.getcwd())
else:
    for path in sys.argv[1:]:
        if os.path.isdir(path):
            compileall.compile_dir(path)
        elif os.path.isfile(path):
            py_compile.compile(path)

#os.system("pause")

