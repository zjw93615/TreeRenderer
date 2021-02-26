import csv
import string
import random

results = []
for i in range(1,100000):
    results.append([i, random.randint(0, i), ''.join(random.choices(string.ascii_uppercase + string.digits, k=5)), random.randint(1, 100)])

with open('input_file.txt', 'w') as finalfile:
    writer = csv.writer(finalfile, delimiter='|')
    writer.writerows(results)