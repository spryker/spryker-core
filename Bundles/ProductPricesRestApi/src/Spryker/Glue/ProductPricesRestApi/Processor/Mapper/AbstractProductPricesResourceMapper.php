<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Generated\Shared\Transfer\RestProductPricesAttributesTransfer;

class AbstractProductPricesResourceMapper implements AbstractProductPricesResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductStorageTransfer $priceProductStorageTransfer
     * @param string $idResource
     *
     * @return \Generated\Shared\Transfer\RestProductPricesAttributesTransfer
     */
    public function mapAbstractProductPricesTransferToRestProductPricesAttributesTransfer(
        PriceProductStorageTransfer $priceProductStorageTransfer,
        string $idResource
    ): RestProductPricesAttributesTransfer
    {
        $productPricesRestAttributesTransfer = new RestProductPricesAttributesTransfer();
        $productPricesRestAttributesTransfer->fromArray($priceProductStorageTransfer->toArray(), true);

        return $productPricesRestAttributesTransfer;


    }
}
