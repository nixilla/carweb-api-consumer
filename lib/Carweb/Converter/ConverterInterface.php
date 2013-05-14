<?php

namespace Carweb\Converter;

interface ConverterInterface
{
    /**
     * Converts string result from API call to something usable
     *
     * @param $string
     * @return mixed
     */
    public function convert($string);
}