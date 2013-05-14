<?php

namespace Carweb\Cache;

class TempFileCache implements CacheInterface
{
    /**
     * @var string
     */
    protected $path = 'carweb';

    /**
     * @var int
     */
    protected $ttl = 3600;

    /**
     * Construct
     *
     * @param string $path
     * @param int $ttl
     */
    public function __construct($path = 'carweb', $ttl = 3600)
    {
        $this->path = $path;
        $this->ttl = $ttl;
    }

    /**
     * check if the current item is cached
     *
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        $path = sprintf('%s/%s/%s.xml', sys_get_temp_dir(), $this->path, $key);

        return file_exists($path) && (time() - filectime($path) < $this->ttl);
    }

    /**
     * Saves the value to cache
     *
     * @param $key
     * @param $value
     * @return mixed
     */
    public function save($key, $value)
    {
        $path = sprintf('%s/%s/%s.xml', sys_get_temp_dir(), $this->path, $key);

        if( ! file_exists(dirname($path)))
            mkdir(dirname($path),0777, true);

        $handle = fopen($path, 'w');
        fwrite($handle, $value);
        fclose($handle);
    }

    /**
     * gets cached value for current item
     *
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        $path = sprintf('%s/%s/%s.xml', sys_get_temp_dir(), $this->path, $key);
        if(file_exists($path))
            return file_get_contents($path);
        else
            return null;
    }

    /**
     * Clears the current value
     *
     * @param $key
     * @return mixed
     */
    public function clear($key)
    {
        $path = sprintf('%s/%s/%s.xml', sys_get_temp_dir(), $this->path, $key);
        if(file_exists($path))
            unlink($path);
    }
}