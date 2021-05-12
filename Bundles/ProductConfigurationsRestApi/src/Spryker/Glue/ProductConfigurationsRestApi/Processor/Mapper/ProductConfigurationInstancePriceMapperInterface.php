<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper;

use ArrayObject;

interface ProductConfigurationInstancePriceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer[]|\ArrayObject $restProductConfigurationPriceAttributesTransfers
     * @param \Generated\Shared\Transfer\PriceProductTransfer[]|\ArrayObject $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]|\ArrayObject
     */
    public function mapRestProductConfigurationPriceAttributesTransfersToPriceProductTransfers(
        ArrayObject $restProductConfigurationPriceAttributesTransfers,
        ArrayObject $priceProductTransfers
    ): ArrayObject;

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[]|\ArrayObject $priceProductTransfers
     * @param \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer[]|\ArrayObject $restProductConfigurationPriceAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer[]|\ArrayObject
     */
    public function mapPriceProductTransfersToRestProductConfigurationPriceAttributesTransfers(
        ArrayObject $priceProductTransfers,
        ArrayObject $restProductConfigurationPriceAttributesTransfers
    ): ArrayObject;
}
