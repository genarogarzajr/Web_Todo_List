<?php

class Filestore {

    public $filename = '';
    public $is_csv = false;

    function __construct($filename = '') 
    {
        $this->filename = $filename;
        $check = substr($filename, -3);
        if ($check == "csv") 
        {
            $this->is_csv = true;
        };
         
    }

//--------------------------
         public function read() 
         {
                if ($this->is_csv) 
                { 
                    return $this->read_csv();
                }
                else 
                {
                    return $this->read_lines();
                }
        }
//---------------------------
        public function write($array) 
        {
            if ($this->is_csv == false) 
                {
                  $this->write_lines($array);
                }
                else $this->write_csv($array);

        }

//--------------------------

    // Returns array of lines in $this->filename
    private function read_lines()
    {
        $list_array = [];
        if (is_readable($this->filename) && filesize($this->filename) > 0) 
        {
            $handle = fopen($this->filename, "r");
            $contents = (fread($handle, filesize($this->filename)));
            $contents = trim($contents);
            fclose($handle);
            return $contents;
        }
        $contents = implode("", $list_array);
        return $contents;   
    }

    //Writes each element in $array to a new line in $this->filename
    private function write_lines($array)
    {
        $handle = fopen($this->filename, "w");
        $contents = implode("\n", $array);
        fwrite($handle, $contents);
        fclose($handle);
    }

    //Reads contents of csv $this->filename, returns an array
    private function read_csv()
    {
        // Code to read file $this->filename
        $addresses = [];
        // read each line of CSV and add rows to addresses array todo
        $handle = fopen($this->filename, "r");
        while (!feof($handle)) 
        {
            $row = fgetcsv($handle);
            if (is_array($row)) 
            {
                $addresses[] = $row; // array_push($addresses, $row);
            }
        }
        fclose($handle);
        return $addresses;
    }

    //Writes contents of $array to csv $this->filename
    private function write_csv($array)
    {
        // Code to write $addresses_array to file $this->filename
        if (is_writable($this->filename)) 
        {
            $handle = fopen($this->filename, "w");
            foreach ($array as $entries) 
            {
                fputcsv($handle, $entries);
            }
            fclose($handle);
        }
    }


}