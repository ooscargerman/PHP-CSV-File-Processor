<?php

Class Processor
{
    public $CAD = 0;
    public $CvsContent = [];
    public $CvsData = [];
    public $arrayHeaders = [];
    public $arrayIndexHeaders = [];

    public function getCAD($amount)
    {
        $req_url = 'https://api.exchangeratesapi.io/latest?base=USD';
        $response_json = file_get_contents($req_url);
        $result = 0;
        if (false !== $response_json) {
            try {
                $response_object = json_decode($response_json);
                $result = money_format('%+.2n', $amount * $response_object->rates->CAD);

            } catch (Exception $e) {
                return "error";
            }
        }
        return $this->CAD = $result;
    }

    public function CSV_Reader($filename, $header = false)
    {
        $handle = fopen($filename, "r");

        $this->CvsContent = fgetcsv($handle);
        $error = 0;
        $index = 0;
        foreach ($this->CvsContent as $headers) {
            $lower = strtolower($headers);
            if ($lower == "sku" || $lower == "cost" || $lower == "price" || $lower == "qty")
                $error++;

            $this->arrayHeaders[$headers] = 0;
            $this->arrayIndexHeaders[$index] = $headers;
            $index++;

        }
        if ($error < 4) {
            echo '<div class="alert alert-danger" role="alert">Error: Theres some headers missing.</div>';
            exit(1);
        } else {
            $the_big_array = [];
            if ($handle !== FALSE) {
                while (($data = fgetcsv($handle)) !== FALSE) {
                    $the_big_array[] = $data;
                }
            }
            fclose($handle);
            $this->CvsData = $the_big_array;
            return $this->CvsContent;
        }

    }

    public function SearchPositionArray($value, $array)
    {

        return array_search(strtolower($value),array_map('strtolower',$array));
    }
}