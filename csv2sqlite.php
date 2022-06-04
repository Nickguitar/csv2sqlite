#!/usr/bin/php
# Nicholas Ferreira  -  04/06/2022
<?php

$index_str = "";

$csv_filename = $argv[1];
$basename = str_replace(".","_",basename($csv_filename));

$file_contents = file_get_contents($csv_filename);
if(!is_file($csv_filename)) die("error");

$header = explode("\n", $file_contents)[0];

//identify separator
preg_match("/\||:|,|;/",$header,$separator);
$separator = $separator[0];

$header_arr = explode($separator, $header);

//prevent memory crash on huge files
$pragma="pragma page_size = 4096;
pragma cache_size = 10000;
pragma synchronous = OFF;
pragma auto_vacuum = FULL;
pragma automatic_index = FALSE;
pragma journal_mode = OFF;";

$create_str = "sqlite3 -batch \"${basename}.db\" <<\"EOF\"
.eqp full
.mode csv
.separator \"$separator\"
".$pragma."
.import $csv_filename $basename
EOF";

echo "[*] Creating .db file\n";
shell_exec($create_str);
echo "[+] DB file created\n\n";

for($i=0;$i<count($header_arr);$i++)
	echo "[$i] $header_arr[$i]\n";

$input = readline("Select the columns to create index (use space to separate; empty if none): ");

$index_str = "";
if(strlen($input) != 0){
	$input = explode(" ", $input);
	foreach($input as $num)
		$index_str .= "create index idx_".$header_arr[$num]." on $basename (".$header_arr[$num].");\n";
}

$create_index_str = "sqlite3 -batch  <<\"EOF\"
.eqp full
".$pragma."
.open ${basename}.db
".$index_str."EOF";

echo "[*] Creating indexes\n";
exec($create_index_str);
echo "[+] Done\n";
