<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Synchronization\Business\SynchronizationBusinessFactory getFactory()
 */
class SynchronizationFacade extends AbstractFacade implements SynchronizationFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $data
     * @param string $queueName
     *
     * @return void
     */
    public function storageWrite(array $data, $queueName)
    {
        $this->getFactory()->createStorageManager()->write($data, $queueName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $data
     * @param string $queueName
     *
     * @return void
     */
    public function storageDelete(array $data, $queueName)
    {
        $this->getFactory()->createStorageManager()->delete($data, $queueName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $data
     * @param string $queueName
     *
     * @return void
     */
    public function searchWrite(array $data, $queueName)
    {
        $this->getFactory()->createSearchManager()->write($data, $queueName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $data
     * @param string $queueName
     *
     * @return void
     */
    public function searchDelete(array $data, $queueName)
    {
        $this->getFactory()->createSearchManager()->delete($data, $queueName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string[] $resources
     * @param int[] $ids
     *
     * @return void
     */
    public function exportSynchronizedData(array $resources, array $ids = [])
    {
        $this->getFactory()->createExporter()->exportSynchronizedData($resources, $ids);
    }
}
