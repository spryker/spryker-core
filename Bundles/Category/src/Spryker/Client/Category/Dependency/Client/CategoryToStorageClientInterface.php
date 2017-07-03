<?php

namespace Spryker\Client\Category\Dependency\Client;


interface CategoryToStorageClientInterface
{

    /**
     * @param string $key
     *
     * @return string
     */
    public function get($key);

}