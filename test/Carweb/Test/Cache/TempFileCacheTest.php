<?php

namespace Carweb\Test\Cache;

use Carweb\Cache\TempFileCache;

class TempFileCacheTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $cache = new TempFileCache();
        $this->assertAttributeEquals('carweb', 'path', $cache);
        $this->assertAttributeEquals('3600', 'ttl', $cache);

        $cache = new TempFileCache('test',3700);
        $this->assertAttributeEquals('test', 'path', $cache);
        $this->assertAttributeEquals('3700', 'ttl', $cache);
    }

    public function testCaching()
    {
        $cache = new TempFileCache('test', 2);

        $cache->clear('test_key');

        $this->assertFalse($cache->has('test_key'));

        $this->assertNull($cache->get('test_key'));

        $cache->save('test_key', 'test value');

        $this->assertTrue($cache->has('test_key'));

        $this->assertEquals('test value',$cache->get('test_key'));

        sleep(2);

        $this->assertFalse($cache->has('test_key'));

        $this->assertNotNull($cache->get('test_key'));

        $cache->clear('test_key');

        $this->assertNull($cache->get('test_key'));
    }
}