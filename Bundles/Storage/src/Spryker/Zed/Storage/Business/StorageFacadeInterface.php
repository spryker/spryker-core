<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Storage\Business;

interface StorageFacadeInterface
{

    /**
     * @param $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * @return int
     */
    public function getTotalCount();

    /**
     * @return array
     */
    public function getTimestamps();

    /**
     * @return int
     */
    public function deleteAll();

    /**
     * @param array $keys
     *
     * @return void
     */
    public function deleteMulti(array $keys);

}
