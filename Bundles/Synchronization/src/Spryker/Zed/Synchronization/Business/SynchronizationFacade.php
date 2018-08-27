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
     * @api
     *
     * @param string[] $resources
     *
     * @return void
     */
    public function executeResolvedPluginsBySources(array $resources)
    {
        $this->getFactory()->createExporterPluginResolver()->executeResolvedPluginsBySources($resources);
    }
}
