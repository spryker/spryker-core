<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model;

use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductMapperInterface;

class PriceGrouper implements PriceGrouperInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\ReaderInterface
     */
    protected $priceReader;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductMapperInterface
     */
    protected $priceProductMapper;

    /**
     * @param \Spryker\Zed\PriceProduct\Business\Model\ReaderInterface $priceReader
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductMapperInterface $priceProductMapper
     */
    public function __construct(
        ReaderInterface $priceReader,
        PriceProductMapperInterface $priceProductMapper
    ) {
        $this->priceReader = $priceReader;
        $this->priceProductMapper = $priceProductMapper;
    }

    /**
     * @param string $sku
     *
     * @return array
     */
    public function findPricesBySkuGroupedForCurrentStore($sku)
    {
        $priceProductTransfers = $this->priceReader->findPricesBySkuForCurrentStore($sku);

        return $this->groupPriceProduct($priceProductTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return array
     */
    public function groupPriceProduct(array $priceProductTransfers)
    {
        $prices = [];
        foreach ($priceProductTransfers as $priceProductTransfer) {
            $priceMoneyValueTransfer = $priceProductTransfer->getMoneyValue();

            $priceType = $priceProductTransfer->getPriceType()->getName();
            $currencyIsoCode = $priceMoneyValueTransfer->getCurrency()->getCode();

            if ($priceMoneyValueTransfer->getGrossAmount() !== null) {
                $prices[$currencyIsoCode][$this->priceProductMapper->getGrossPriceModeIdentifier()][$priceType] = $priceMoneyValueTransfer->getGrossAmount();
            }

            if ($priceMoneyValueTransfer->getNetAmount() !== null) {
                $prices[$currencyIsoCode][$this->priceProductMapper->getNetPriceModeIdentifier()][$priceType] = $priceMoneyValueTransfer->getNetAmount();
            }
        }

        return $prices;
    }
}
