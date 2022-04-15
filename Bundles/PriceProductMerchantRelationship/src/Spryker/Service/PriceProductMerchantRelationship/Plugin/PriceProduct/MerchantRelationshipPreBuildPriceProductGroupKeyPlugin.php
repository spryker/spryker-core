<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductMerchantRelationship\Plugin\PriceProduct;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Service\PriceProductExtension\Dependency\Plugin\PreBuildPriceProductGroupKeyPluginInterface;

class MerchantRelationshipPreBuildPriceProductGroupKeyPlugin implements PreBuildPriceProductGroupKeyPluginInterface
{
    /**
     * Specification:
     *  - Sets PriceProduct.priceDimension.isMerchantActive to null.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function preBuild(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        $priceProductTransfer->getPriceDimensionOrFail()->setIsMerchantActive(null);

        return $priceProductTransfer;
    }
}
