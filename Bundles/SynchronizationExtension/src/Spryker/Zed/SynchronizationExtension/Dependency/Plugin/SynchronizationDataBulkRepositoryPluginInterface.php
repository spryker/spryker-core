<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SynchronizationExtension\Dependency\Plugin;

interface SynchronizationDataBulkRepositoryPluginInterface extends SynchronizationDataPluginInterface
{
    /**
     * Specification:
     *  - Returns SynchronizationDataTransfer[] of size according to provided offset and limit.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getData(int $offset, int $limit): array;
}
