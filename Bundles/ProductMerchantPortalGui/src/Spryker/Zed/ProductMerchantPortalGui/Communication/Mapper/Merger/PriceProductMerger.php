<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductTransfer;

class PriceProductMerger implements PriceProductMergerInterface
{
    /**
     * @var array<\Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\MergeStrategy\PriceProductMergeStrategyInterface>
     */
    protected array $mergeStrategies;

    /**
     * @param array<\Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\MergeStrategy\PriceProductMergeStrategyInterface> $mergeStrategies
     */
    public function __construct(array $mergeStrategies)
    {
        $this->mergeStrategies = $mergeStrategies;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $newPriceProductTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function mergePriceProducts(
        PriceProductTransfer $newPriceProductTransfer,
        ArrayObject $priceProductTransfers
    ): ArrayObject {
        foreach ($this->mergeStrategies as $mergeStrategy) {
            if ($mergeStrategy->isApplicable($newPriceProductTransfer, $priceProductTransfers)) {
                return $mergeStrategy->merge($newPriceProductTransfer, $priceProductTransfers);
            }
        }

        $priceProductTransfers->append($newPriceProductTransfer);

        return $priceProductTransfers;
    }
}
