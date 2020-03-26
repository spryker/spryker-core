<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Storage\Business;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Storage\Business\StorageBusinessFactory getFactory()
 */
class StorageFacade extends AbstractFacade implements StorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->getFactory()->createStorage()->get($key);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return int
     */
    public function getTotalCount()
    {
        return $this->getFactory()->createStorage()->getTotalCount();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function getTimestamps()
    {
        return $this->getFactory()->createStorage()->getTimestamps();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return int
     */
    public function deleteAll()
    {
        return $this->getFactory()->createStorage()->deleteAll();
    }

    /**
     * {@inheritDoc}
     *
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

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $destination
     *
     * @return bool
     */
    public function export($destination)
    {
        return $this->getFactory()->createStorageExporter()->export($destination);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $source
     *
     * @return bool
     */
    public function import($source)
    {
        return $this->getFactory()->createStorageImporter()->import($source);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function executeKeyValueStoreHealthCheck(): HealthCheckServiceResponseTransfer
    {
        return $this->getFactory()->createKeyValueStoreHealthChecker()->executeHealthCheck();
    }
}
