<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Storage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method StorageBusinessFactory getFactory()
 */
class StorageFacade extends AbstractFacade
{

    public function get($key)
    {
        return $this->getFactory()->createStorage()->get($key);
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->getFactory()->createStorage()->getTotalCount();
    }

    /**
     * @return array
     */
    public function getTimestamps()
    {
        return $this->getFactory()->createStorage()->getTimestamps();
    }

    /**
     * @return int
     */
    public function deleteAll()
    {
        return $this->getFactory()->createStorage()->deleteAll();
    }

    /**
     * @param array $keys
     *
     * @return void
     */
    public function deleteMulti(array $keys)
    {
        $this->getFactory()->createStorage()->deleteMulti($keys);
    }

}
