<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductMerchantRelationship\Plugin;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Spryker\Service\Kernel\AbstractPlugin;
use Spryker\Service\PriceProduct\Dependency\Plugin\PriceProductDecisionPluginInterface;

class MerchantRelationshipPriceDecisionPlugin extends AbstractPlugin implements PriceProductDecisionPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransferCollection
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer|null
     */
    public function matchValue(array $priceProductTransferCollection, PriceProductCriteriaTransfer $priceProductCriteriaTransfer): ?MoneyValueTransfer
    {
        //TODO implement logic from BusinessUnitPriceDimensionDecision  but using $priceProductTransferCollection
        return null;
    }
}
