<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business;

interface SynchronizationFacadeInterface
{
    /**
     * Specification:
     * - Writes json encoded data to storage
     * - Will not write if the data is outdated compare to storage timestamp
     *
     * @api
     *
     * @param array $data
     * @param string $queueName
     *
     * @return void
     */
    public function storageWrite(array $data, $queueName);

    /**
     * Specification:
     * - Deletes all data keys from storage
     * - Will not delete if the data is outdated compare to storage timestamp
     *
     * @api
     *
     * @param array $data
     * @param string $queueName
     *
     * @return void
     */
    public function storageDelete(array $data, $queueName);

    /**
     * Specification:
     * - Writes json encoded data to search
     * - Will not write if the data is outdated compare to search timestamp
     *
     * @api
     *
     * @param array $data
     * @param string $queueName
     *
     * @return void
     */
    public function searchWrite(array $data, $queueName);

    /**
     * Specification:
     * - Deletes all data keys from search
     * - Will not delete if the data is outdated compare to search timestamp
     *
     * @api
     *
     * @param array $data
     * @param string $queueName
     *
     * @return void
     */
    public function searchDelete(array $data, $queueName);

    /**
     * @api
     *
     * @param string[] $resources
     *
     * @return void
     */
    public function executeResolvedPluginsBySources(array $resources);
}
