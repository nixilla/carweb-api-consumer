<?php

namespace Carweb\Test\Validator;

use Carweb\Validator\VRM;

class VRMTest extends \PHPUnit_Framework_TestCase
{
    public function testValidateVehiclePre1932()
    {
        $validator = new VRM();

        $valid = array('t 22', 'a 1', 'b99', 'z9999','ay 8281', 'as 1', 'gt 56',' jy 234', 'x-18');
        $invalid = array('a12345', 'as12345', 'vrf 2', 'bgfd 1', 'AA01 AAA', 'A123 AAA');

        foreach($valid as $vrm)
            $this->assertTrue($validator->validateVehiclePre1932($vrm), sprintf('VRM %s is not valid', $vrm));

        foreach($invalid as $vrm)
            $this->assertFalse($validator->validateVehiclePre1932($vrm), sprintf('VRM %s is valid', $vrm));
    }

    public function testValidateVehicle1932()
    {
        $validator = new VRM();

        $valid = array('tac 220','ayr 281', 'vrf 2','aaa 1', 'bbb 22', 'ccc 333', 'zzz-999');
        $invalid = array('1bbc','t22', 'a 1', 'a12345', 'as12345', 'bgfd 1', 'AA01 AAA', 'A123 AAA');

        foreach($valid as $vrm)
            $this->assertTrue($validator->validateVehicle1932($vrm), sprintf('VRM %s is not valid', $vrm));

        foreach($invalid as $vrm)
            $this->assertFalse($validator->validateVehicle1932($vrm), sprintf('VRM %s is valid', $vrm));
    }

    public function testValidateVehicle1950()
    {
        $validator = new VRM();

        $valid = array('1bbc','1000-e','1 ahx', '2 bbc');
        $invalid = array('t22', 'a 1', 'a12345', 'as12345', 'bgfd 1', 'AA01 AAA', 'A123 AAA');

        foreach($valid as $vrm)
            $this->assertTrue($validator->validateVehicle1950($vrm), sprintf('VRM %s is not valid', $vrm));

        foreach($invalid as $vrm)
            $this->assertFalse($validator->validateVehicle1950($vrm), sprintf('VRM %s is valid', $vrm));
    }

    public function testValidateVehicle1963()
    {
        $validator = new VRM();

        $valid = array('bbc 1 a','abc 123 z','bbc1', 'bbc2', 'xxx-22');
        $invalid = array('t22', 'a 1', 'a12345', 'as12345', 'bgfd 1', 'AA01 AAA', 'A123 AAA');

        foreach($valid as $vrm)
            $this->assertTrue($validator->validateVehicle1963($vrm), sprintf('VRM %s is not valid', $vrm));

        foreach($invalid as $vrm)
            $this->assertFalse($validator->validateVehicle1963($vrm), sprintf('VRM %s is valid', $vrm));
    }

    public function testValidateVehicle1982()
    {
        $validator = new VRM();

        $valid = array('a123 aaa', 'G111 AAA', 'J111AAA', 'K111 AAA', 'L111 AAA', 'M111AAA', 'N111AAA', 'P444 AAA', 'R222 BBB', 'S111 AAA', 'T333 CCC', 'Y111 AAA');
        $invalid = array('t22', 'a 1', 'a12345', 'as12345', 'bgfd 1', 'AA01 AAA');

        foreach($valid as $vrm)
            $this->assertTrue($validator->validateVehicle1982($vrm), sprintf('VRM %s is not valid', $vrm));

        foreach($invalid as $vrm)
            $this->assertFalse($validator->validateVehicle1982($vrm), sprintf('VRM %s is valid', $vrm));
    }

    public function testValidateVehicle2001()
    {
        $validator = new VRM();

        $valid = array('AA01 AAA', 'AA51 AAA', 'AA82 AAA');
        $invalid = array('t22', 'bbc 222','1 ahx', 'bbc 1 a', 'a123 aaa', 'JJ01 AAA','AZ11 AAA', 'AA50 AAA', 'AA01 IAA', 'AA01 AQA');

        foreach($valid as $vrm)
            $this->assertTrue($validator->validateVehicle2001($vrm), sprintf('VRM %s is not valid', $vrm));

        foreach($invalid as $vrm)
            $this->assertFalse($validator->validateVehicle2001($vrm), sprintf('VRM %s is valid', $vrm));
    }

    public function testIsValid()
    {
        $validator = new VRM();

        $valid = array('AA01 AAA', 'a123 aaa', 'bbc 1 a', '1bbc', 'tac 220', 't 22');
        $invalid = array('AA01 IAA');

        foreach($valid as $vrm)
            $this->assertTrue($validator->isValid($vrm), sprintf('VRM %s is not valid', $vrm));

        foreach($invalid as $vrm)
            $this->assertFalse($validator->isValid($vrm), sprintf('VRM %s is valid', $vrm));
    }
}