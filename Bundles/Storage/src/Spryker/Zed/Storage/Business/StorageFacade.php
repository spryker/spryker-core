<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Storage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method StorageBusinessFactory getBusinessFactory()
 */
class StorageFacade extends AbstractFacade
{

    public function get($key)
    {
        return $this->getBusinessFactory()->createStorage()->get($key);
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->getBusinessFactory()->createStorage()->getTotalCount();
    }

    /**
     * @return array
     */
    public function getTimestamps()
    {
        return $this->getBusinessFactory()->createStorage()->getTimestamps();
    }

    /**
     * @return int
     */
    public function deleteAll()
    {
        return $this->getBusinessFactory()->createStorage()->deleteAll();
    }

    /**
     * @param array $keys
     *
     * @return void
     */
    public function deleteMulti(array $keys)
    {
        $this->getBusinessFactory()->createStorage()->deleteMulti($keys);
    }

}
