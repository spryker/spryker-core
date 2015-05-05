<?php

namespace SprykerFeature\Zed\Payone\Business\Key;



interface KeyHashInterface
{

    /**
     * @param string $key
     * @return string
     */
    public function hashKey($key);

}
