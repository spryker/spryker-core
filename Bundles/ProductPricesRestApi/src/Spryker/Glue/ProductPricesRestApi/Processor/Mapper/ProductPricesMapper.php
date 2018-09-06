<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestProductPriceAttributesTransfer;
use Generated\Shared\Transfer\RestProductPricesAttributesTransfer;

class ProductPricesMapper implements ProductPricesMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\RestProductPricesAttributesTransfer
     */
    public function mapProductPricesTransfersToRestProductPricesAttributesTransfer(
        array $priceProductTransfers
    ): RestProductPricesAttributesTransfer {
        $productPricesRestAttributesTransfer = new RestProductPricesAttributesTransfer();
        foreach ($priceProductTransfers as $priceProductTransfer) {
            $restProductPriceAttributesTransfer = new RestProductPriceAttributesTransfer();
            $restProductPriceAttributesTransfer->fromArray($priceProductTransfer->toArray(), true);
            $restProductPriceAttributesTransfer->setGrossAmount($priceProductTransfer->getMoneyValue()->getGrossAmount());
            $restProductPriceAttributesTransfer->setNetAmount($priceProductTransfer->getMoneyValue()->getNetAmount());
            $restProductPriceAttributesTransfer->setCurrency($priceProductTransfer->getMoneyValue()->getCurrency()->getCode());
            $productPricesRestAttributesTransfer->addPrice($restProductPriceAttributesTransfer);
        }
        return $productPricesRestAttributesTransfer;
    }
}
