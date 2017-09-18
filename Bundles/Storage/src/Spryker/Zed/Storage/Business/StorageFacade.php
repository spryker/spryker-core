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
     * @api
     *
     * @param string $key
     * @param string $prefix
     *
     * @return mixed
     */
    public function get($key, $prefix = '')
    {
        return $this->getFactory()->createStorage()->get($key, $prefix);
    }

    /**
     * @api
     *
     * @return int
     */
    public function getTotalCount()
    {
        return $this->getFactory()->createStorage()->getTotalCount();
    }

    /**
     * @api
     *
     * @return array
     */
    public function getTimestamps()
    {
        return $this->getFactory()->createStorage()->getTimestamps();
    }

    /**
     * @api
     *
     * @return int
     */
    public function deleteAll()
    {
        return $this->getFactory()->createStorage()->deleteAll();
    }

    /**
     * @api
     *
     * @param array $keys
     *
     * @return void
     */
    public function deleteMulti(array $keys)
    {
        $this->getFactory()->createStorage()->deleteMulti($keys);
    }

}
