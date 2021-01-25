<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceCartConnector\Business\Fixture;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProduct\Business\PriceProductFacade;

class PriceProductFacadeStub extends PriceProductFacade
{
    protected const EUR_ISO_CODE = 'EUR';

    /**
     * @var int[]
     */
    protected $prices = [];

    /**
     * @var bool[]
     */
    protected $validities = [];

    /**
     * @param string $sku
     * @param string|null $priceType
     *
     * @return int|null
     */
    public function findPriceBySku($sku, $priceType = null)
    {
        return $this->prices[$sku];
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceFilterTransfer
     *
     * @return mixed
     */
    public function findPriceFor(PriceProductFilterTransfer $priceFilterTransfer)
    {
        if (!isset($this->prices[$priceFilterTransfer->getSku()])) {
            return null;
        }

        return $this->prices[$priceFilterTransfer->getSku()];
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function findPriceProductFor(PriceProductFilterTransfer $priceFilterTransfer): ?PriceProductTransfer
    {
        $price = $this->findPriceFor($priceFilterTransfer);

        if ($price === null) {
            return null;
        }

        return (new PriceProductTransfer())
            ->setSkuProduct($priceFilterTransfer->getSku())
            ->setPriceTypeName($this->getDefaultPriceTypeName())
            ->setMoneyValue(
                (new MoneyValueTransfer())
                    ->setGrossAmount($price)
                    ->setCurrency(
                        (new CurrencyTransfer())
                            ->setCode(static::EUR_ISO_CODE)
                    )
            );
    }

    /**
     * @param string $sku
     * @param string|null $priceType
     *
     * @return bool
     */
    public function hasValidPrice($sku, $priceType = null): bool
    {
        if (!isset($this->validities[$sku])) {
            return false;
        }

        return $this->validities[$sku];
    }

    /**
     * @param string $sku
     * @param int $price
     *
     * @return void
     */
    public function addPriceStub(string $sku, int $price): void
    {
        $this->prices[$sku] = $price;
    }

    /**
     * @param string $sku
     * @param bool $validity
     *
     * @return void
     */
    public function addValidityStub(string $sku, bool $validity = true): void
    {
        $this->validities[$sku] = $validity;
    }

    /**
     * @return string
     */
    public function getDefaultPriceTypeName(): string
    {
        return 'DEFAULT';
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer[] $priceProductFilterTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getValidPrices(array $priceProductFilterTransfers): array
    {
        $priceProductTransfers = [];
        foreach ($priceProductFilterTransfers as $priceProductFilterTransfer) {
            $priceProductTransfers[] = $this->findPriceProductFor($priceProductFilterTransfer);
        }

        return array_filter($priceProductTransfers);
    }
}
