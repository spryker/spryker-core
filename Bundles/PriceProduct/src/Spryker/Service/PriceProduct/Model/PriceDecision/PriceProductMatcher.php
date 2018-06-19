<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct\Business\Model\PriceDecision;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;

class PriceProductMatcher implements PriceProductMatcherInterface
{
    /**
     * @var \Spryker\Service\PriceProduct\Dependency\Plugin\PriceProductDecisionPluginInterface[]
     */
    protected $priceProductDecisionPlugins = [];

    /**
     * @param \Spryker\Service\PriceProduct\Dependency\Plugin\PriceProductDecisionPluginInterface[] $priceProductDecisionPlugins
     */
    public function __construct(array $priceProductDecisionPlugins)
    {
        $this->priceProductDecisionPlugins = $priceProductDecisionPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyPriceProductStoreEntityTransfer[] $priceProductStoreEntityTransferCollection
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer|null
     */
    public function matchPriceValue(
        array $priceProductStoreEntityTransferCollection,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): ?MoneyValueTransfer {

        if (count($priceProductStoreEntityTransferCollection) === 0) {
            return null;
        }

        foreach ($this->priceProductDecisionPlugins as $priceProductDecisionPlugin) {
            $moneyValueTransfer = $priceProductDecisionPlugin->matchValue($priceProductStoreEntityTransferCollection, $priceProductCriteriaTransfer);
            if ($moneyValueTransfer) {
                return $moneyValueTransfer;
            }
        }

        return null;
    }
}
