<?php

namespace App\Data;

use App\Configs;
use App\Consts\CacheConsts;
use App\Logs\Logger;

abstract class Cache
{
    private $service;

    protected $main;

    public function __constructor(\App\Main $main, string $host, string $password, int $port = 6379): void
    {
        $this->main = $main;

        $this->service = new \Redis();
        try {
            $this->service->pconnect('tls://' . $host, $port);
            $this->service->auth($password);

        } catch (\Exception $ex) {
            Die('Redis failed to connect', $ex->getMessage());
        }

        if (!$this->service->ping()) {
            Die('Redis failed to ping');
        }
    }

    public function FlushAll()
    {
        return $this->_FlushAll();
    }

    public function GetKeys()
    {
        return $this->_Keys();
    }

    public function HashKeyExists($hash, $key)
    {
        return $this->_HashKeyExists($hash, $key);
    }

    public function KeyExists($key)
    {
        return $this->_KeyExists($key);
    }

    public function SetHashKey($hash, $key, $value)
    {
        return $this->_SetHashKey($hash, $key, $value);
    }

    public function SetKey($key, $value)
    {
        return $this->_SetKey($key, $value);
    }

    public function GetKey($key)
    {
        return $this->_GetKey($key);
    }

    public function GetHashKey($hash, $key)
    {
        return $this->_GetHashKey($hash, $key);
    }

    public function GetHashAllKeys($hash)
    {
        return $this->_GetHashAllKeys($hash);
    }

    public function DeleteHashKey($hash, $key)
    {
        return $this->_DeleteHashKey($hash, $key);
    }

    public function DeleteKey($key)
    {
        return $this->_DeleteKey($key);
    }

    private function _FlushAll()
    {
        try {
            return $this->service->flushAll();
        } catch (\Exception $ex) {
            Die('', $ex->getMessage());
            return false;
        }
    }

    private function _Keys()
    {
        try {
            return $this->service->keys('*');
        } catch (\Exception $ex) {
            Die('', $ex->getMessage());

            return false;
        }
    }

    private function _HashKeyExists($hash, $key)
    {
        try {
            return $this->service->hexists($hash, $key);
        } catch (\Exception $ex) {
            Die('', $ex->getMessage());

            return false;
        }
    }

    private function _KeyExists($key)
    {
        try {
            return $this->service->exists($key);
        } catch (\Exception $ex) {
            Die('', $ex->getMessage());

            return false;
        }
    }

    private function _SetHashKey($hash, $key, $value)
    {
        try {
            $this->service->hset($hash, $key, $value);
            $this->service->expire($key, KEY_LIFE);

            return true;
        } catch (\Exception $ex) {
            Die('', $ex->getMessage());

            return false;
        }
    }

    private function _GetHashKey($hash, $key)
    {
        try {
            return $this->service->hget($hash, $key);
        } catch (\Exception $ex) {
            Die('', $ex->getMessage());

            return false;
        }
    }

    private function _GetHashAllKeys($hash)
    {
        try {
            return $this->service->hgetall($hash);
        } catch (\Exception $ex) {
            Die('', $ex->getMessage());

            return false;
        }
    }

    private function _SetKey($key, $value)
    {
        try {
            $this->service->set($key, $value);
            $this->service->expire($key, KEY_LIFE);

            return true;
        } catch (\Exception $ex) {
            Die('', $ex->getMessage());

            return false;
        }
    }

    private function _GetKey($key)
    {
        try {
            $value = $this->service->get($key);

            return $value;
        } catch (\Exception $ex) {
            Die('', $ex->getMessage());

            return false;
        }
    }

    private function _DeleteKey($key)
    {
        try {
            $this->service->del($key);

            return true;
        } catch (\Exception $ex) {
            Die('', $ex->getMessage());

            return false;
        }
    }

    private function _DeleteHashKey($hash, $key)
    {
        try {
            $this->service->hdel($hash, $key);

            return true;
        } catch (\Exception $ex) {
            Die('', $ex->getMessage());

            return false;
        }
    }
}
