<?php

namespace SprykerFeature\Zed\Payone\Business\Key;



class KeyHashProvider implements KeyHashInterface
{

    /**
     * @param string $key
     * @return string
     */
    public function hashKey($key)
    {
        return hash('md5', $key);
    }

}
