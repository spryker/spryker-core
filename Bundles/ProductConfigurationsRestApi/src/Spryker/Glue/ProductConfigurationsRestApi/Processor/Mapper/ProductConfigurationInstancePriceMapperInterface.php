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
     * @param \ArrayObject<int, \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer> $restProductConfigurationPriceAttributesTransfers
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function mapRestProductConfigurationPriceAttributesTransfersToPriceProductTransfers(
        ArrayObject $restProductConfigurationPriceAttributesTransfers,
        ArrayObject $priceProductTransfers
    ): ArrayObject;

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \ArrayObject<int, \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer> $restProductConfigurationPriceAttributesTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer>
     */
    public function mapPriceProductTransfersToRestProductConfigurationPriceAttributesTransfers(
        ArrayObject $priceProductTransfers,
        ArrayObject $restProductConfigurationPriceAttributesTransfers
    ): ArrayObject;
}
