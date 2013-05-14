<?php

namespace Carweb\Converter;

class DefaultConverter implements ConverterInterface
{

    /**
     * Converts string result from API call to something usable
     *
     * @param $string
     * @return mixed
     */
    public function convert($string)
    {
        return $string;
    }
}