<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct\Model;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

class PriceProductMatcher implements PriceProductMatcherInterface
{
    protected const PRICE_NET_MODE = 'NET_MODE';

    /**
     * @var \Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductDecisionPluginInterface[]
     */
    protected $priceProductDecisionPlugins = [];

    public function __construct(array $priceProductDecisionPlugins)
    {
        $this->priceProductDecisionPlugins = $priceProductDecisionPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransferCollection
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function matchPriceValueByPriceProductCriteria(
        array $priceProductTransferCollection,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): ?PriceProductTransfer {
        $priceProductCriteriaTransfer
            ->requirePriceMode()
            ->requirePriceType()
            ->requireIdCurrency();

        if (count($priceProductTransferCollection) === 0) {
            return null;
        }

        foreach ($this->priceProductDecisionPlugins as $priceProductDecisionPlugin) {
            $priceProductTransfer = $priceProductDecisionPlugin->matchPriceByPriceProductCriteria($priceProductTransferCollection, $priceProductCriteriaTransfer);
            if ($priceProductTransfer) {
                return $priceProductTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransferCollection
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function matchPriceValueByPriceProductFilter(
        array $priceProductTransferCollection,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): ?PriceProductTransfer {
        $priceProductFilterTransfer
            ->requirePriceTypeName()
            ->requireCurrencyIsoCode()
            ->requirePriceMode();

        if (count($priceProductTransferCollection) === 0) {
            return null;
        }

        foreach ($this->priceProductDecisionPlugins as $priceProductDecisionPlugin) {
            $priceProductTransfer = $priceProductDecisionPlugin->matchPriceByPriceProductFilter($priceProductTransferCollection, $priceProductFilterTransfer);
            if ($priceProductTransfer) {
                return $priceProductTransfer;
            }
        }

        return null;
    }
}
