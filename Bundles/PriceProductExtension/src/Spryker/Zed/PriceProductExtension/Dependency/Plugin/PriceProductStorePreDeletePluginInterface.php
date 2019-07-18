<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductExtension\Dependency\Plugin;

interface PriceProductStorePreDeletePluginInterface
{
    /**
     * Specification:
     * - Runs pre delete hook for price product store entity.
     *
     * @api
     *
     * @param int $idPriceProductStore
     *
     * @return void
     */
    public function preDelete(int $idPriceProductStore): void;
}
