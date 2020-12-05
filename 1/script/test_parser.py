#!/usr/bin/env python3
# coding: utf8
import re
import sys

# Функция для очистки строки от двойных пробелов, пробела в начале строки и в конце
def deleteSpace(string):
    if len(string) > 0:
        while(string[0]==" "):
            string = string[1:]        
        for i in range(len(string)-3):
            if string[i+1] == " " and string[i] == " ":
                string = string[:i] + string[(i+1):]
        while(string[len(string)-1]==" "):
            string = string[:-1]
        return string
    else:
        return ""

# Функция для склеивания строк с разделителем
def splitData(string, tempString):
    if string == "":
        string = deleteSpace(tempString)
    else:
        string = deleteSpace(string) + "|" + deleteSpace(tempString)
    return string

# Функция для составления строки шифров
def useCipher(line, it_cipher):
    for item in (re.findall(x, line))[0].split("+"):
        it_cipher = splitData(it_cipher, useRegular(REGULAR_CLEANING_FOR_CIPHER, item))
    return it_cipher

# Функция для применения регулярного выражения к строке
def useRegular(REGULAR, item):
    return re.sub(REGULAR, '', item)

# Функция для проверки на не пустую строку
def isNotEmpty(string):
    if len(string) > 0:
        return True
    else: 
        return False

# Регулярные выражения для поиска строк из документа
REGULAR_FIO = r"\\fio.*\{(.*)\}\{(.*)\}\{([^}]*)\}\ ?"
REGULAR_BBK = r"\\bbk\{(.*)\}.*"
REGULAR_UDK = r"\\udk\{(.*)\}.*"
REGULAR_KEYWORDS = r"\\keywords\{(.*)\}\{.*"
REGULAR_ART = r"\\art(\[.*\])?\{(.*)\}\{(.*)\}.*"

# Регулярные выражения для очистки строк от не нужной информации (пробелы/английские буквы/и т.д.) 
REGULAR_CLEANING = r"[аАбБвВгГдДеЕёЁжЖзЗиИйЙкКлЛмМнНоОпПрРсСтТуУфФхХцЦчЧшШщЩъЪыЫьЬэЭюЮяЯ\,\.\-]*"
REGULAR_CLEANING_FOR_CIPHER = r"[^\d.]*"
REGULAR_CLEANING_SPACE = r"[^аАбБвВгГдДеЕёЁжЖзЗиИйЙкКлЛмМнНоОпПрРсСтТуУфФхХцЦчЧшШщЩъЪыЫьЬэЭюЮяЯ\,\.\ \-]*"
REGULAR_CLEANING_FOR_FIO = r"[^аАбБвВгГдДеЕёЁжЖзЗиИйЙкКлЛмМнНоОпПрРсСтТуУфФхХцЦчЧшШщЩъЪыЫьЬэЭюЮяЯ]*"

# Переменные для хранения данных
lists = [REGULAR_FIO, REGULAR_BBK, REGULAR_UDK, REGULAR_KEYWORDS, REGULAR_ART]
fio_list = ""
keyword = ""
sum_authors = 0
udk = ""
bbk = ""
art = ""

# Чтение файла .tex с принудительной кодировкой UTF-8 и запись его в list
if len(sys.argv) > 1:
    f = open(sys.argv[1], 'r')
    list = f.readlines()
    if(len(list) == 0):
        print("This file is empty.")
    else:
        # Чтение списка и поиск ключеных слов с помощью регулярных выражений
        for line in list:
            for x in lists:
                if len(re.findall(x, line)) != 0:
                    if isNotEmpty(fio_list) & isNotEmpty(art) & isNotEmpty(bbk) & isNotEmpty(udk) & isNotEmpty(keyword):
                        break
                    if x == REGULAR_FIO: # если строка относится к ФИО
                        sum_authors += 1
                        tempFio = ""
                        for itemFio in re.findall(x, line)[0]:
                            tempFio = tempFio + " " + useRegular(REGULAR_CLEANING_FOR_FIO, itemFio)
                        fio_list = splitData(fio_list, tempFio)
                    if x == REGULAR_BBK: # если строка относится к шифру ББК
                        bbk = useCipher(line, bbk)
                    if x == REGULAR_UDK: # если строка относится к шифру УДК
                        udk = useCipher(line, udk)
                    if x == REGULAR_KEYWORDS: # если строка относится к ключевым словам
                        for y in useRegular(REGULAR_CLEANING_SPACE, (re.findall(x, line))[0]).split(", "):
                            if len(re.findall(REGULAR_CLEANING, y)[0]) != 0:
                                keyword = splitData(keyword, y)
                    if x == REGULAR_ART: # если строка относится к названию статьи
                        art = deleteSpace(useRegular(REGULAR_CLEANING_SPACE, (re.findall(x, line))[0][1]))
                    
        print(str(sum_authors) + ";" + fio_list + ";" + art + ";" + bbk + ";" + udk + ";" + keyword + ";")
        list.clear()
else:
    print("Error in read file or file name not found.")