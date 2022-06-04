# csv2sqlite
Lazy shitty script to automate csv to sqlite3 conversion

## Usage

```
$ ./csv2sqlite.php br.csv 
[*] Creating .db file
br.csv:17435: expected 10 columns but found 11 - extras ignored
[+] DB file created
[0] id
[1] username
[2] name
[3] age
[4] email
Select the columns to create index (use space to separate; empty if none): 1 2
[*] Creating indexes
[+] Done
```
