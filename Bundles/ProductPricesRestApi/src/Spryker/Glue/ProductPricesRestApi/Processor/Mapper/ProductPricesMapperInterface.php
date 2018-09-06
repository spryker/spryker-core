<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestProductPricesAttributesTransfer;

interface ProductPricesMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\RestProductPricesAttributesTransfer
     */
    public function mapProductPricesTransfersToRestProductPricesAttributesTransfer(
        array $priceProductTransfers
    ): RestProductPricesAttributesTransfer;
}
