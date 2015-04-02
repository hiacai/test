# -*- coding: cp936 -*-
__author__ = 'Linwencai'
__doc__ = '''
pyתpyc
1.ֱ�����б��ļ�:ת����ǰĿ¼�µ�����py
2.��קpy�����ļ�:ת����py
3.��קĿ¼�����ļ�:ת����Ŀ¼�µ�����py

cmd�б���pyc:
python -m py_compile file.py

cmd�б���pyo:
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

