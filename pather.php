<?php

$input = $argv[1];
$output = $argv[2];

//open input.txt
$input_file = fopen($input, "r");
//create output.txt; overwrite if it already exists
$output_file = fopen($output, "w") or die("Unable to open file!");

$begin = false; //true if # is at the beginning of the path
$marker_index; //marks what index an * should go
$total_hash = count_total_hashes($input_file);

$input_file = fopen($input, "r");
$hash_count = 0;

while(!feof($input_file))
{
    $line = fgets($input_file);

    $hash_index = strpos($line, "#");

    // if there is a "#" on the line
    if ($hash_index !== FALSE)
    {
    	// if the path hasn't started yet, mark as the beginning
    	if (!$begin)
    	{
    		$begin = true;
    		$marker_index = $hash_index;

    		//check if any other # exists on the first line
    		for($i = $hash_index; $i < strlen($line); $i++)
	    	{
	    		if ($line[$i] == "#")
	    		{
                    $hash_count++;
	    			if($hash_index < $i)
	    			{
	    				$line = fill_left_right($line, $hash_index+1, $i);
	    				$marker_index = $i;
	    			}	    			
	    		}
	    	}
            if($hash_count == $total_hash)
            {
                $begin = false;
            }
    	}

    	else
    	{
    		$begin = false;
    		
    		// there is a "#" to the right of the marker index 
    		if ($marker_index < $hash_index)
    		{
    			$line = fill_left_right($line, $marker_index, $hash_index);
    		}
    		// there is a "#" to the left of the marker index
    		else
    		{
    			$line = fill_right_left($line, $marker_index, $hash_index);
    		}
    	}
    }
    // there is no "#" on the line
    else
    {
    	if ($begin)
    	{
    		$line[$marker_index] = "*";
    	}
    }

    $edited_line = $line;
    fwrite($output_file, $edited_line);
} //end while
fclose($input_file);
fclose($output_file);

//count all #'s in the entire file, close the file and return an int
function count_total_hashes($input_file)
{
    $total_hash = 0;
    while(!feof($input_file))
    {
        $line = fgets($input_file);
        for($i = 0; $i < strlen($line); $i++)
        {
            if($line[$i] == "#")
                $total_hash++;
        }
    }
    fclose($input_file);
    return $total_hash;

}

// This function replaces .'s with *'s from left to right from the marker index to one below the hash index
function fill_left_right($line, $marker_index, $hash_index)
{
	for($i = $marker_index; $i < $hash_index; $i++)
	{
		if ($line[$i] != "#")
			$line[$i] = "*";
	}
	return $line;
}

// This function replaces .'s with *'s from right to left from the marker index to one above the hash index
function fill_right_left($line, $marker_index, $hash_index)
{
	for($i = $marker_index; $i > $hash_index; $i--)
	{
		if ($line[$i] != "#")
			$line[$i] = "*";
	}
	return $line;
}
?>