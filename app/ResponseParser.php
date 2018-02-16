<?php

namespace App;

class ResponseParser
{
    public function achievement($data)
    {
        $output = [];
        $data = str_replace("\'", "'", $data);

        preg_match("/name_enus: '(.*?)(',)/s", $data, $match);
        $output['name'] = $match[1];

        preg_match("/icon: '(.*?)(',)/s", $data, $match);
        $output['icon'] = $match[1];

        return $output;
    }
}
