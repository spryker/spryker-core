<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProduct\ProductSumPriceCalculator;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Spryker\Client\PriceProduct\ProductPriceResolver\ProductPriceResolverInterface;

class ProductSumPriceCalculator implements ProductSumPriceCalculatorInterface
{
    /**
     * @var \Spryker\Client\PriceProduct\ProductPriceResolver\ProductPriceResolverInterface
     */
    protected $productPriceResolver;

    /**
     * @param \Spryker\Client\PriceProduct\ProductPriceResolver\ProductPriceResolverInterface $productPriceResolver
     */
    public function __construct(ProductPriceResolverInterface $productPriceResolver)
    {
        $this->productPriceResolver = $productPriceResolver;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function calculateProductSumPrice(
        CurrentProductPriceTransfer $currentProductPriceTransfer,
        PriceProductFilterTransfer $priceProductFilterTransfer,
        array $priceProductTransfers
    ): CurrentProductPriceTransfer {
        $currentProductPriceTransfer = $this->productPriceResolver->resolveProductPriceTransferByPriceProductFilter($priceProductTransfers, $priceProductFilterTransfer);
        $currentProductPriceTransfer->setSumPrice(
            $currentProductPriceTransfer->getPrice() * $currentProductPriceTransfer->getQuantity()
        );

        return $currentProductPriceTransfer;
    }
}
