<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SynchronizationExtension\Dependency\Plugin;

interface SynchronizationDataRepositoryPluginInterface extends SynchronizationDataPluginInterface
{
    /**
     * Specification:
     *  - Returns query of storage or search entity, provided $ids parameter
     *    will apply to query to limit the result
     *
     * @api
     *
     * @param array $ids
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer[]
     */
    public function getData($ids = []);
}
