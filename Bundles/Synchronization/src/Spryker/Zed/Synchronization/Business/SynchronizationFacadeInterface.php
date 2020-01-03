<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business;

interface SynchronizationFacadeInterface
{
    /**
     * @api
     *
     * @deprecated Use \Spryker\Zed\Synchronization\Business\SynchronizationFacadeInterface::processStorageMessages instead.
     *
     * Specification:
     * - Writes json encoded data to storage
     * - Will not write if the data is outdated compare to storage timestamp
     *
     * @param array $data
     * @param string $queueName
     *
     * @return void
     */
    public function storageWrite(array $data, $queueName);

    /**
     * @api
     *
     * @deprecated Use \Spryker\Zed\Synchronization\Business\SynchronizationFacadeInterface::processStorageMessages instead.
     *
     * Specification:
     * - Deletes all data keys from storage
     * - Will not delete if the data is outdated compare to storage timestamp
     *
     * @param array $data
     * @param string $queueName
     *
     * @return void
     */
    public function storageDelete(array $data, $queueName);

    /**
     * @api
     *
     * @deprecated Use \Spryker\Zed\Synchronization\Business\SynchronizationFacadeInterface::processSearchMessages instead.
     *
     * Specification:
     * - Writes json encoded data to search
     * - Will not write if the data is outdated compare to search timestamp
     *
     * @param array $data
     * @param string $queueName
     *
     * @return void
     */
    public function searchWrite(array $data, $queueName);

    /**
     * @api
     *
     * @deprecated Use \Spryker\Zed\Synchronization\Business\SynchronizationFacadeInterface::processSearchMessages instead.
     *
     * Specification:
     * - Deletes all data keys from search
     * - Will not delete if the data is outdated compare to search timestamp
     *
     * @param array $data
     * @param string $queueName
     *
     * @return void
     */
    public function searchDelete(array $data, $queueName);

    /**
     * Specification:
     * - Syncs the queue messages to search.
     * - Marks the messages as failed if error occurs.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    public function processSearchMessages(array $queueMessageTransfers): array;

    /**
     * Specification:
     * - Syncs the queue messages to storage.
     * - Marks the messages as failed if error occurs.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    public function processStorageMessages(array $queueMessageTransfers): array;

    /**
     * @api
     *
     * @deprecated Use SynchronizationFacadeInterface::executeResolvedPluginsBySourcesWithIds() instead.
     *
     * @param string[] $resources
     *
     * @return void
     */
    public function executeResolvedPluginsBySources(array $resources);

    /**
     * @api
     *
     * @param string[] $resources
     * @param int[] $ids
     *
     * @return void
     */
    public function executeResolvedPluginsBySourcesWithIds(array $resources, array $ids);

    /**
     * Specification:
     *  - Returns sorted resource names list from plugins configured in SynchronizationDependencyProvider::getSynchronizationDataPlugins().
     *
     * @api
     *
     * @return string[]
     */
    public function getAvailableResourceNames(): array;
}
