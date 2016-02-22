<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Storage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Storage\Business\StorageBusinessFactory getFactory()
 */
class StorageFacade extends AbstractFacade implements StorageFacadeInterface
{

    /**
     * @param string $key
     *
     * @return mixed
     */
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
