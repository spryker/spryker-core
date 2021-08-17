<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;

interface ProductConfigurationPriceProductVolumeMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     * @param \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer[] $restProductConfigurationPriceAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer[]
     */
    public function mapProductConfigurationInstanceToRestProductConfigurationPriceAttributes(
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer,
        array $restProductConfigurationPriceAttributesTransfers
    ): array;
}
