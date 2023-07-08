#!/usr/bin/env php

<?php
// camelCase variable

$filename = 'php_internship_data.csv';


// Check if the file exists
if (!file_exists($filename)) {
    echo "The file '$filename' does not exist.\n";
    exit;
}
// Open the CSV file
$csvFile = fopen($filename, 'r');
// Initialize arrays
$nameCounts = [];
$dateCounts = [];
// Read each line from the CSV file
while (($data = fgetcsv($csvFile)) !== false) {
    // Extract the name from the CSV row
    $name = $data[0];
	$birthdayDate = $data[1];
	//If the key is not defined yet, assign it a default value of 0
     // Increment the count for the name in the array
	 $nameCounts[$name] = ($nameCounts[$name] ?? 0) + 1; 
	// Filter dates from January 1, 2000
     if (strtotime($birthdayDate) >= strtotime('2000-01-01')) {
       // Increment the count for the date of birth in the array
       $dateCounts[$birthdayDate] = ($dateCounts[$birthdayDate] ?? 0) + 1;
    }
}
// Close the CSV file
fclose($csvFile);


// Sort the names based on their counts in descending order (partial sort)
uasort($nameCounts, function($a, $b) {
    return $b - $a;
});
uasort($dateCounts, function($a, $b) {
    return $b - $a;
});

// Get the top ten names
$topTenNames = array_slice($nameCounts, 0, 10, true);
$topTenDates = array_slice($dateCounts, 0, 10, true);


//convert str_pad to Polish language friendly format
function polish_str_pad ($input, $pad_length, $pad_string) {
   return str_pad($input, 
                  strlen($input)-mb_strlen($input,"UTF-8")+$pad_length, 
                  $pad_string
				 ); 
}
//convert string to Polish language friendly format
function polish_str_convert ($input,$converttype) { 
   return mb_convert_case($input,
						 $converttype == "Lower" ? MB_CASE_LOWER : ($converttype == "Upper" ? MB_CASE_UPPER : ""),
						 "UTF-8"
					 );
}
//convert only first letter to upper case
function str_concat ($input) {	
	$firstCharacter = polish_str_convert( mb_substr($input, 0, 1),"Upper");
    $remainingCharacters = polish_str_convert(mb_substr($input, 1),"Lower");
	return $firstCharacter . $remainingCharacters;
}


// Print the top ten names and their counts
echo "---- \t TOP10 \t ----\n";

echo "Name\t\tCount\n";
foreach ($topTenNames as $name => $countName) {
 	$formattedName = str_concat($name);
    $paddedName = polish_str_pad($formattedName, 18, '.',STR_PAD_RIGHT); 
    echo $paddedName . $countName . "\n"; 
}

echo "\nDate\t\tCount\n";
foreach ($topTenDates as $birthdayDate => $countDate) {
	//convert date format
	$formattedDate = date ('d.m.Y',strtotime($birthdayDate));
	$paddedDate = str_pad($formattedDate, 18, '.',STR_PAD_RIGHT); 
    echo  $paddedDate. ' ' . $countDate . "\n";
}

?>

