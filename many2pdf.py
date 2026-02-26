#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import argparse
import os.path

import magic
import mysql.connector
from PyPDF2 import PdfMerger, PdfReader, PdfWriter
from reportlab.lib.units import mm
from reportlab.pdfgen import canvas

merged_object = PdfMerger()


def createPagePdf(num, tmp):
    c = canvas.Canvas(tmp)
    for i in range(1, num + 1):
        c.drawString((210 // 2) * mm, (4) * mm, str(i))
        c.showPage()
    c.save()


def add_page_numbers(merged_pdfs, merged_numbered_pdfs):
    """
    Add page numbers to a pdf, save the result as a new pdf
    @param merged_pdfs: path to pdf, output result file
    """

    tmp = os.path.realpath(merged_pdfs) + "__tmp.pdf"

    output = PdfWriter()

    # Open the merged pdf for get number pages
    with open(merged_pdfs, "rb") as f:
        pdf = PdfReader(f, strict=False)
        n = len(pdf.pages)

        # With number pages, create new PDF with page numbers
        createPagePdf(n, tmp)

        # Open the new created pdf with page numbers and add layer with the merged files
        with open(tmp, "rb") as ftmp:
            numberPdf = PdfReader(ftmp)

            # iterate pages
            for p in range(n):
                page = pdf.pages[p]
                numberLayer = numberPdf.pages[p]

                # merge number page with actual page
                page.merge_page(numberLayer)
                output.add_page(page)

            # write result
            if len(output.pages) > 1:
                newpath = os.path.dirname(merged_pdfs) + "/" + str(merged_numbered_pdfs)
                with open(newpath, "wb") as f:
                    output.write(f)

        os.remove(tmp)
        os.remove(merged_pdfs)


# Script arguments
parser = argparse.ArgumentParser(description="Parametros do comando.")
parser.add_argument(
    "-n", "--num", required=True, type=int, help="Número do procedimento"
)
args = vars(parser.parse_args())

# MySQL connect
conn = mysql.connector.connect(
    host="mysql", user="kkrreemmeerr", password="krm47935687hjh7ade", port=3306
)
cursor = conn.cursor(buffered=True)

num = args["num"]

cursor.execute(
    f"SELECT arquivo FROM fiscaliza2.tb_cidadao_arquivos WHERE id_pedido = {num}"
)

mime = magic.Magic(mime=True)

merged_pdfs = f"arquivos_cidadao_prev/pedido_{num}.pdf_"
merged_numbered_pdfs = f"pedido_{num}.pdf"

for data in cursor.fetchall():
    file_name = f"arquivos_cidadao_prev/{data[0]}"

    if not os.path.isfile(file_name):
        print(f"O arquivo {file_name} nao existe!")
    else:
        if mime.from_file(f"{file_name}") == "application/pdf":
            merged_object.append(PdfReader(file_name), "rb")
        else:
            print("Não é um arquivo do formato PDF!")

# Merge pdfs
merged_object.write(merged_pdfs)

# Add number to pdf pages
add_page_numbers(merged_pdfs, merged_numbered_pdfs)

conn.commit()
cursor.close()
conn.close()
