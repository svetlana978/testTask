<?php

class CBRCurrency
{
    protected $list = array();
    protected $result = array();
 
    public function loadCurrencyData($date)
    {
        $xml = new DOMDocument();
        $url = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=' . $date;
 
        if ($xml->load($url)) {
            $this->list = []; 
            $root = $xml->documentElement;
            $items = $root->getElementsByTagName('Valute');

            foreach ($items as $item) {
                $name = $item->getElementsByTagName('Name')->item(0)->nodeValue;
                $value = $item->getElementsByTagName('Value')->item(0)->nodeValue;
                $this->list[$name] = floatval(str_replace(',', '.', $value));
            }

            return true;
        }

        return false;
    }

    public function getCurrencyData($from, $to)
    {
        $period = new DatePeriod($from, new DateInterval('P1D'), $to);
        
        $arrayOfDates = array_map(
            function($item){return $item->format('d.m.Y');},
            iterator_to_array($period)
        );

        foreach ($arrayOfDates as $date) {
            $this->loadCurrencyData($date);

            foreach ($this->getCurrency() as $name => $value) {
                $newArr[$name][$date] = $value;
            }
        }

        foreach ($newArr as $curName => $value) {
            $max[$curName][array_search(max($value), $value)] = max($value);
            $min[$curName][array_search(min($value), $value)] = min($value);
            $averege[$curName] = array_sum($value)/count($value);
        }

        $result['max_value'] = $max;
        $result['min_value'] = $min;
        $result['averege_value'] = $averege;

        return $this->$result = $result;
    }
 
    public function getCurrency()
    {
        return isset($this->list) ? $this->list : 0;
    }
}
