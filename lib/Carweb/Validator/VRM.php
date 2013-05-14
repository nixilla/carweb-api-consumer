<?php

/**
 * This validator is based on PEAR:Validate:UK http://pear.php.net/package/Validate_UK
 *
 * I've made some amendments, but I want to credit here original authors.
 * https://github.com/pear/Validate_UK/blob/master/Validate/UK.php
 * https://github.com/pear/Validate_UK/blob/master/Validate/UK/carReg.php
 */

namespace Carweb\Validator;

class VRM
{
    // source: http://en.wikipedia.org/wiki/British_car_number_plate_identifiers

    protected $regions2001 = array(
        'A' => array(
            'region' => 'East Anglia',
            'dvla'  => array(
                'ABCDEFGHJKLMN' => 'Peterborough',
                'OPRSTU'        => 'Norwich',
                'VWXY'          => 'Ipswich'
            )
        ),
        'B' => array(
            'region' => 'Birmingham',
            'dvla' => array(
                'ABCDEFGHJKLMNOPRSTUVWXY' => 'Birmingham'
            )
        ),
        'C' => array(
            'region' => 'Cymru',
            'dvla'  => array(
                'ABCDEFGHJKLMNO' => 'Cardiff',
                'PRSTUV'         => 'Swansea',
                'WXY'            => 'Bangor'
            )
        ),
        'D' => array(
            'region' => 'Deeside etc.',
            'dvla' => array(
                'ABCDEFGHJK'    => 'Chester',
                'LMNOPRSTUVWXY' => 'Shrewsbury'
            )
        ),
        'E' => array(
            'region' => 'Essex and Hertfordshire',
            'dvla' => array(
                'ABCDEFGHJKLMNOPRSTUVWXY' => 'Chelmsford'
            )
        ),
        'F' => array(
            'region' => 'Forest and Ferns',
            'dvla'  => array(
                'ABCDEFGHJKLMNOP' => 'Nottingham',
                'RSTUVWXY'        => 'Lincoln'
            )
        ),
        'G' => array(
            'region' => 'Garden of England',
            'dvla'  => array(
                'ABCDEFGHJKLMNO' => 'Maidstone',
                'PRSTUVWXY'      => 'Brighton'
            )
        ),
        'H' => array(
            'region' => 'Hants and Dorset',
            'dvla'  => array(
                'ABCDEFGHJ'      => 'Bournemouth',
                'KLMNOPRSTUVWXY' => 'Portsmouth'
            )
        ),
        'K' => array(
            'region' => '',
            'dvla'  => array(
                'ABCDEFGHJKL'  => 'Luton',
                'MNOPRSTUVWXY' => 'Northampton'
            )
        ),
        'L' => array(
            'region' => 'London',
            'dvla'  => array(
                'ABCDEFGHJ' => 'Wimbledon',
                'KLMNOPRST' => 'Standmore',
                'UVWXY'     => 'Sidcup'
            )
        ),
        'M' => array(
            'region' => 'Manchester and Merseyside',
            'dvla'  => array(
                'ABCDEFGHJKLMNOPRSTUVWXY' => 'Manchester'
            )
        ),
        'N' => array(
            'region' => 'Newcastle and North',
            'dvla'  => array(
                'ABCDEFGHJKLMNO' => 'Newcastle upon Tyne',
                'PRSTUVWXY'      => 'Stockton-on-Tees'
            )
        ),
        'O' => array(
            'region' => 'Oxford',
            'dvla'  => array(
                'ABCDEFGHJKLMNOPRSTUVWXY' => 'Oxford'
            )
        ),
        'P' => array(
            'region' => 'Preston and Pennines',
            'dvla'  => array(
                'ABCDEFGHJKLMNOPRST' => 'Preston',
                'UVWXY'              => 'Carlisle'
            )
        ),
        'Q' => array(
            'region' => 'Oxford',
            'dvla'  => array(
                'ABCDEFGHJKLMNOPRSTUVWXY' => 'Any - used for vehicles of unidentifiable age'
            )
        ),
        'R' => array(
            'region' => 'Reading',
            'dvla'  => array(
                'ABCDEFGHJKLMNOPRSTUVWXY' => 'Reading'
            )
        ),
        'S' => array(
            'region' => 'Scotland',
            'dvla'  => array(
                'ABCDEFGHJ' => 'Glasgow',
                'KLMNO'     => 'Edinburgh',
                'PRST'      => 'Dundee',
                'UVW'       => 'Aberdeen',
                'XY'        => 'Inverness'
            )
        ),
        'V' => array(
            'region' => 'Vale of Severn',
            'dvla'  => array(
                'ABCDEFGHJKLMNOPRSTUVWXY' => 'Worcester'
            )
        ),
        'W' => array(
            'region' => 'West Country',
            'dvla'  => array(
                'ABCDEFGHJ'    => 'Exeter',
                'KL'           => 'Truro',
                'MNOPRSTUVWXY' => 'Bristol'
            )
        ),
        'Y' => array(
            'region' => 'Yorkshire',
            'dvla'  => array(
                'ABCDEFGHJK' => 'Leeds',
                'LMNOPRSTU'  => 'Sheffield',
                'VWXY'       => 'Beverley'
            )
        )
    );

    /**
     * Validates a car registration number
     *
     * @param string $vrm the vehicle registration mark
     *
     * @access public
     * @return bool
     */
    public function isValid($vrm)
    {
        $vrm = $this->normalize($vrm);
        // functions to check, in order
        $regFuncs = array(
            '2001',
            '1982',
            '1963',
            '1950',
            '1932',
            'Pre1932'
        );
        foreach ($regFuncs as $func)
        {
            $method = 'validateVehicle' . $func;

            $ret   = $this->$method($vrm);
            if ($ret !== false)
            {
                // maybe return something useful here when possible?
                return true;
            }
        }
        return false;
    }

    /**
     * validateVehiclePre1932
     *
     * input must be uppercased and spaces and dashes removed
     *
     * @param string $input car reg
     *
     * @access public
     * @return bool
     */
    public function validateVehiclePre1932($input)
    {
        $input = $this->normalize($input);
        return preg_match('/^[A-Z]{1,2}\d{1,4}$/', $input) > 0;
    }

    /**
     * validateVehicle1932
     *
     * @param string $input car reg
     *
     * @access public
     * @return bool
     */
    public function validateVehicle1932($input)
    {
        $input = $this->normalize($input);
        return preg_match('/^[A-Z]{3}\d{1,3}$/', $input) > 0;
    }

    /**
     * validateVehicle1950
     *
     * @param string $input car reg
     *
     * @access public
     * @return bool
     */
    public function validateVehicle1950($input)
    {
        $input = $this->normalize($input);
        return preg_match('/(^\d{1,3}[A-Z]{3}$)|(^\d{1,4}[A-Z]{1,3}$)/', $input) > 0;
    }

    /**
     * validateVehicle1963
     *
     * @param string $input car reg
     *
     * @access public
     * @return bool
     */
    public function validateVehicle1963($input)
    {
        $input = $this->normalize($input);
        return preg_match('/^([A-Z]{3})\d{1,3}([A-Z]?)$/', $input) > 0;
    }

    /**
     * validateVehicle1982
     *
     * @param string $input car reg
     *
     * @access public
     * @return bool
     */
    public function validateVehicle1982($input)
    {
        $input = $this->normalize($input);

        if ( ! preg_match('/^([A-Z])\d{1,3}[A-Z]{3}$/', $input, $matches))
        {
            // invalidly formed
            return false;
        }

        $year = ord($matches[1]) - 65;

        if($year > 8) $year--; // as there is no I in the scheme
        if($year > 13) $year--; // as there is no O in the scheme
        if($year > 15) $year--; // as there is no Q in the scheme


        if ($year > 14)
        {
            // two letters per year
            $year -= floor(($year - 15) / 2);
        }

        $year += 1983;

        return $year >= 1983 && $year <= 2001;
    }

    /**
     * validateVehicle2001
     *
     * @param string $input car reg
     *
     * @access public
     * @return bool
     */
    public function validateVehicle2001($input)
    {
        $input = $this->normalize($input);

        if ( ! preg_match('/^([A-Z]{2})(\d{2})([A-Z]{3})$/', $input, $matches))
        {
            // invalidly formed
            return false;
        }

        if ( ! isset($this->regions2001[$matches[1][0]]))
        {
            // region can't be found
            return false;
        }

        $region = isset($this->regions2001[$matches[1][0]]) ? $this->regions2001[$matches[1][0]] : array();

        $dvla = false;

        foreach($region['dvla'] as $letters => $location)
        {
            if(strpos($letters, $matches[1][1]) !== false)
            {
                $dvla = $location;
                break;
            }
        }

        if ($dvla === false)
        {
            // dvla office can't be found
            return false;
        }


        if ($matches[2] >= 50)
        {
            $matches[2] -= 50;
        }

        if ($matches[2] < 1)
        {
            // invalid year
            return false;
        }

        if (strpbrk($matches[3], 'IQ') !== false)
        {
            // invalid suffix
            return false;
        }

        // if it didn't return by now it means it's OK
        return true;
    }

    /**
     * Removes space and dashes + changes to upper case
     *
     * @param $input
     * @return string
     */
    public function normalize($input)
    {
        return strtoupper(preg_replace(array('/\s/','/-/'), '', $input));
    }
}