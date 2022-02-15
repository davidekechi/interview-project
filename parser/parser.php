<?php

//Get the csv file name from the arguments passed
$csv_file = $argv[2];

//Get the proposed unique combination file name from the arguments passed
$unique_combo = explode('=', $argv[3]);
$unique_combo_file = $unique_combo[1];

//Terminate script if csv file does not exist in parser directory
if (!file_exists($csv_file)){
  echo '"'.$csv_file.'" does not exist in this directory!';
  exit();
}

//IF CSV FILE EXISTS IN PARSER DIRECTORY

//Open csv file
$file = fopen($csv_file, 'r');
$data = [];

//Terminate script if csv file is empty
if (filesize($csv_file) < 1) {
  echo '"'.$csv_file.'" is empty!';
  exit();
}

//Store content of csv file in an array
while (($line = fgetcsv($file)) !== FALSE) {
  $data[] = $line;
}
fclose($file);

//Declare array for single product data
$product_holder = [];


//Loop through csv content stored in array
foreach($data as $data_item) {
  $item = [];
  $item_num = [];
  foreach($data as $data_verify) {
    if ($data_item == $data_verify) {
      $item = $data_item;
      $item_num[] = $data_item;
    }
  }

  //Add csv data count as a new element
  array_push($item, count($item_num));

  //Store processed csv data in single product array
  $product_holder[] = $item;
}

$results = array();

//Loop through single product array and store in results array
foreach ($product_holder as $k => $v) {
  $results[implode($v)] = $v;
}

//Remove all duplicate arrays from results array to form unique combinations
$results = array_values($results);

//Loop through original csv data to display
foreach($data as $row) {
  //Check that all required fields are represented
  if (($row[0] == "" || $row[0] == "Not Applicable") || ($row[1] == "" || $row[1] == "Not Applicable")) {
    echo 'Required fields not represented!' . PHP_EOL;
  }else{
    //Display csv data
    $txt = $row[0].','.$row[1].','.$row[2].','.$row[3].','.$row[4].','.$row[5].','.$row[6] . PHP_EOL;
    echo  $txt;
  }
}

//Create unique combination csv file
$output = fopen($unique_combo_file,'w') or die("Can't open newfile");

//Write file headers
for ($i=0; $i < count($data); $i++) {
  $header = implode(',', $data[$i]);
  $txt = $header.',count' . PHP_EOL;
  fwrite($output, $txt);
  break;
}

//Loop through results array
foreach ($results as $key => $result){
  //Remove original file headers
  if ($key !== 0) {
    //Check that all required fields are represented
    if (($result[0] == "" || $result[0] == "Not Applicable") || ($result[1] == "" || $result[1] == "Not Applicable")) {
      fwrite($output, 'Required fields not represented!' . PHP_EOL);
    }else{
      //Write unique combination data to csv file
      $txt = $result[0].','.$result[1].','.$result[2].','.$result[3].','.$result[4].','.$result[5].','.$result[6].','.$result[7] . PHP_EOL;
      fwrite($output, $txt);
    }
  }
}
//Close csv file
fclose($output) or die("newfile");


