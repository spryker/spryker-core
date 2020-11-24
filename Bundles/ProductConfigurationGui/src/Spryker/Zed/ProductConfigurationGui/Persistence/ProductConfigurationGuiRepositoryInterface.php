<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationGui\Persistence;

use Generated\Shared\Transfer\ProductConfigurationAggregationTransfer;

interface ProductConfigurationGuiRepositoryInterface
{
    /**
     * @param string $abstractProductSku
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationAggregationTransfer|null
     */
    public function findProductConfigurationAggregation(
        string $abstractProductSku
    ): ?ProductConfigurationAggregationTransfer;
}
