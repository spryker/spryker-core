<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Storage\Business;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;

interface StorageFacadeInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return int
     */
    public function getTotalCount();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return array
     */
    public function getTimestamps();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return int
     */
    public function deleteAll();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $keys
     *
     * @return void
     */
    public function deleteMulti(array $keys);

    /**
     * Specification:
     * - Exports the redis db to destination.
     *
     * @api
     *
     * @param string $destination
     *
     * @return bool
     */
    public function export($destination);

    /**
     * Specification:
     * - Imports redis db dump from source.
     *
     * @api
     *
     * @param string $source
     *
     * @return bool
     */
    public function import($source);

    /**
     * Specification:
     * - Executes health check for the storage.
     * - Checks that connection has been established.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function executeKeyValueStoreHealthCheck(): HealthCheckServiceResponseTransfer;
}
