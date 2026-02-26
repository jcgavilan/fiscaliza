#!/usr/bin/python3

import sys

import pdfkit

if len(sys.argv) == 3:
    pdfkit.from_file(sys.argv[1], sys.argv[2])
else:
    print("Numero inválido de argumentos")
