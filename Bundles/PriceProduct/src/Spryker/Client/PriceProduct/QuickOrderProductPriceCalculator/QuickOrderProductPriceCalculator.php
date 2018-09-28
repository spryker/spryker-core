<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProduct\QuickOrderProductPriceCalculator;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\QuickOrderProductPriceTransfer;
use Spryker\Client\PriceProduct\Dependency\Client\PriceProductToCurrencyClientInterface;
use Spryker\Client\PriceProduct\ProductPriceResolver\ProductPriceResolverInterface;

class QuickOrderProductPriceCalculator implements QuickOrderProductPriceCalculatorInterface
{
    /**
     * @var \Spryker\Client\PriceProduct\ProductPriceResolver\ProductPriceResolverInterface
     */
    protected $productPriceResolver;

    /**
     * @var \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToCurrencyClientInterface
     */
    protected $currencyClient;

    /**
     * @param \Spryker\Client\PriceProduct\ProductPriceResolver\ProductPriceResolverInterface $productPriceResolver
     * @param \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToCurrencyClientInterface $currencyClient
     */
    public function __construct(
        ProductPriceResolverInterface $productPriceResolver,
        PriceProductToCurrencyClientInterface $currencyClient
    ) {
        $this->productPriceResolver = $productPriceResolver;
        $this->currencyClient = $currencyClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderProductPriceTransfer $quickOrderProductPriceTransfer
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\QuickOrderProductPriceTransfer
     */
    public function calculateQuickOrderProductPrice(
        QuickOrderProductPriceTransfer $quickOrderProductPriceTransfer,
        PriceProductFilterTransfer $priceProductFilterTransfer,
        array $priceProductTransfers
    ): QuickOrderProductPriceTransfer {
        $currentProductPriceTransfer = $this->productPriceResolver->resolveProductPriceTransferByPriceProductFilter($priceProductTransfers, $priceProductFilterTransfer);

        $quickOrderProductPriceTransfer->setCurrentProductPrice($currentProductPriceTransfer);
        $quickOrderProductPriceTransfer->setCurrency(
            $this->currencyClient->getCurrent()
        );
        $quickOrderProductPriceTransfer->setTotal(
            $currentProductPriceTransfer->getPrice() * $quickOrderProductPriceTransfer->getQuantity()
        );

        return $quickOrderProductPriceTransfer;
    }
}
