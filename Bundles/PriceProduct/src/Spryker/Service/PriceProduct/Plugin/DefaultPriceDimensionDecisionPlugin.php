<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct\Plugin;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Spryker\Service\Kernel\AbstractPlugin;
use Spryker\Service\PriceProduct\Dependency\Plugin\PriceProductDecisionPluginInterface;

class DefaultPriceDimensionDecisionPlugin extends AbstractPlugin implements PriceProductDecisionPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransferCollection
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer|null
     */
    public function matchValue(
        array $priceProductTransferCollection,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): ?MoneyValueTransfer {

        if (empty($priceProductTransferCollection)) {
            return null;
        }

        foreach ($priceProductTransferCollection as $priceProductTransfer) {
            if (!$priceProductTransfer->getPriceDimension()) {
                continue;
            }
            if ($priceProductTransfer->getPriceDimension()->getType() === $priceProductCriteriaTransfer->getPriceDimension()->getType()) {
                return $priceProductTransfer->getMoneyValue();
            }
        }

        return null;
    }
}
