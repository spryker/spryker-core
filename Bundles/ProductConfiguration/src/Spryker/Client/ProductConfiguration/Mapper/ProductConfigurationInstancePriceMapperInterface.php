<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Mapper;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;

interface ProductConfigurationInstancePriceMapperInterface
{
    /**
     * @param array<string, mixed> $configuratorResponseData
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer
     */
    public function mapConfiguratorResponseDataPricesToProductConfigurationInstancePrices(
        array $configuratorResponseData,
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
    ): ProductConfigurationInstanceTransfer;
}
