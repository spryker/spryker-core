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
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    public function processSearchMessages(array $queueMessageTransfers): array
    {
        return $this->getFactory()
            ->createSearchQueueMessageProcessor()
            ->processMessages($queueMessageTransfers);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    public function processStorageMessages(array $queueMessageTransfers): array
    {
        return $this->getFactory()
            ->createStorageQueueMessageProcessor()
            ->processMessages($queueMessageTransfers);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @deprecated Use SynchronizationFacade::executeResolvedPluginsBySourcesWithIds() instead.
     *
     * @param string[] $resources
     *
     * @return void
     */
    public function executeResolvedPluginsBySources(array $resources)
    {
        $this->getFactory()->createExporterPluginResolver()->executeResolvedPluginsBySources($resources);
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
    public function executeResolvedPluginsBySourcesWithIds(array $resources, array $ids)
    {
        $this->getFactory()->createExporterPluginResolver()->executeResolvedPluginsBySourcesWithIds($resources, $ids);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string[]
     */
    public function getAvailableResourceNames(): array
    {
        return $this->getFactory()->createExporterPluginResolver()->getAvailableResourceNames();
    }
}
