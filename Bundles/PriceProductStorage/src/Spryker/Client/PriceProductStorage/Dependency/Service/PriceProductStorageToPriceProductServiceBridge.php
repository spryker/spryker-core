<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Dependency\Service;

use Generated\Shared\Transfer\PriceProductTransfer;

class PriceProductStorageToPriceProductServiceBridge implements PriceProductStorageToPriceProductServiceInterface
{
    /**
     * @var \Spryker\Service\PriceProduct\PriceProductServiceInterface
     */
    protected $priceProductService;

    /**
     * @param \Spryker\Service\PriceProduct\PriceProductServiceInterface $priceProductService
     */
    public function __construct($priceProductService)
    {
        $this->priceProductService = $priceProductService;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return string
     */
    public function buildPriceProductIdentifier(PriceProductTransfer $priceProductTransfer): string
    {
        return $this->priceProductService->buildPriceProductIdentifier($priceProductTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $abstractPriceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $concretePriceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function mergeConcreteAndAbstractPrices(array $abstractPriceProductTransfers, array $concretePriceProductTransfers): array
    {
        return $this->priceProductService->mergeConcreteAndAbstractPrices($abstractPriceProductTransfers, $concretePriceProductTransfers);
    }
}
