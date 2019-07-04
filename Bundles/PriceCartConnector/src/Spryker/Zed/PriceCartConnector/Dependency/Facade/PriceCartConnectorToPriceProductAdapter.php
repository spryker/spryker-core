<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Dependency\Facade;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

class PriceCartConnectorToPriceProductAdapter implements PriceCartToPriceProductInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface $priceProductFacade
     */
    public function __construct($priceProductFacade)
    {
        $this->priceProductFacade = $priceProductFacade;
    }

    /**
     * @param string $sku
     * @param string|null $priceType
     *
     * @return bool
     */
    public function hasValidPrice($sku, $priceType = null)
    {
        return $this->priceProductFacade->hasValidPrice($sku, $priceType);
    }

    /**
     * @param string $sku
     * @param string|null $priceType
     *
     * @return int|null
     */
    public function findPriceBySku($sku, $priceType = null)
    {
        return $this->priceProductFacade->findPriceBySku($sku, $priceType);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function findPriceProductFor(PriceProductFilterTransfer $priceFilterTransfer): ?PriceProductTransfer
    {
        return $this->priceProductFacade->findPriceProductFor($priceFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceFilterTransfer
     *
     * @return bool
     */
    public function hasValidPriceFor(PriceProductFilterTransfer $priceFilterTransfer)
    {
        return $this->priceProductFacade->hasValidPriceFor($priceFilterTransfer);
    }

    /**
     * @return string
     */
    public function getDefaultPriceTypeName()
    {
        return $this->priceProductFacade->getDefaultPriceTypeName();
    }

    /**
     * The method check for `method_exists` is for BC for supporting old majors of `PriceProduct` module.
     *
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer[] $priceProductFilterTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getValidPrices(array $priceProductFilterTransfers): array
    {
        if (!method_exists($this->priceProductFacade, 'getValidPrices')) {
            return $this->findPriceProductsForPriceProductFilterTransfers($priceProductFilterTransfers);
        }

        return $this->priceProductFacade->getValidPrices($priceProductFilterTransfers);
    }

    /**
     * @deprecated Will be removed with the next major.
     *
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer[] $priceProductFilterTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function findPriceProductsForPriceProductFilterTransfers(array $priceProductFilterTransfers): array
    {
        $priceProductTransfers = [];
        foreach ($priceProductFilterTransfers as $priceProductFilterTransfer) {
            $priceProductTransfer = $this->findPriceProductFor($priceProductFilterTransfer);
            if ($priceProductTransfer) {
                $priceProductTransfer->setSkuProduct($priceProductFilterTransfer->getSku());
                $priceProductTransfers[] = $priceProductTransfer;
            }
        }

        return $priceProductTransfers;
    }
}
