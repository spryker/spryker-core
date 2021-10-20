<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\Merger;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductTransfer;

class PriceProductsMerger implements PriceProductsMergerInterface
{
    /**
     * @var array<\Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\Merger\MergeStrategy\PriceProductMergeStrategyInterface>
     */
    protected $priceProductMergeStrategies;

    /**
     * @param array<\Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer\Merger\MergeStrategy\PriceProductMergeStrategyInterface> $priceProductMergeStrategies
     */
    public function __construct(array $priceProductMergeStrategies)
    {
        $this->priceProductMergeStrategies = $priceProductMergeStrategies;
    }

    /**
     * @phpstan-param ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @phpstan-return ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductTransfer $newPriceProductTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function merge(
        ArrayObject $priceProductTransfers,
        PriceProductTransfer $newPriceProductTransfer
    ): ArrayObject {
        foreach ($this->priceProductMergeStrategies as $priceProductMergeStrategy) {
            $mergedPriceProductTransfers = $priceProductMergeStrategy->merge(
                $priceProductTransfers,
                $newPriceProductTransfer,
            );

            if ($mergedPriceProductTransfers !== null) {
                return $mergedPriceProductTransfers;
            }
        }

        return $this->mergeWithDefaultStrategy(
            $priceProductTransfers,
            $newPriceProductTransfer,
        );
    }

    /**
     * @phpstan-param ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @phpstan-return ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductTransfer $newPriceProductTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function mergeWithDefaultStrategy(
        ArrayObject $priceProductTransfers,
        PriceProductTransfer $newPriceProductTransfer
    ): ArrayObject {
        $priceProductTransfers->append($newPriceProductTransfer);

        return $priceProductTransfers;
    }
}
