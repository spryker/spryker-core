<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProduct\ProductPriceResolver;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Client\PriceProduct\Dependency\Client\PriceProductToCurrencyClientInterface;
use Spryker\Client\PriceProduct\Dependency\Client\PriceProductToPriceClientInterface;
use Spryker\Client\PriceProduct\PriceProductConfig;
use Spryker\Service\PriceProduct\PriceProductServiceInterface;

class ProductPriceResolver implements ProductPriceResolverInterface
{
    /**
     * @var \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToPriceClientInterface
     */
    protected $priceClient;

    /**
     * @var \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToCurrencyClientInterface
     */
    protected $currencyClient;

    /**
     * @var \Spryker\Client\PriceProduct\PriceProductConfig
     */
    protected $priceProductConfig;

    /**
     * @var \Spryker\Service\PriceProduct\PriceProductServiceInterface
     */
    protected $priceProductService;

    /**
     * @param \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToPriceClientInterface $priceClient
     * @param \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToCurrencyClientInterface $currencyClient
     * @param \Spryker\Client\PriceProduct\PriceProductConfig $priceProductConfig
     * @param \Spryker\Service\PriceProduct\PriceProductServiceInterface $priceProductService
     */
    public function __construct(
        PriceProductToPriceClientInterface $priceClient,
        PriceProductToCurrencyClientInterface $currencyClient,
        PriceProductConfig $priceProductConfig,
        PriceProductServiceInterface $priceProductService
    ) {
        $this->priceProductConfig = $priceProductConfig;
        $this->priceClient = $priceClient;
        $this->currencyClient = $currencyClient;
        $this->priceProductService = $priceProductService;
    }

    /**
     * @param array $priceMap
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolve(array $priceMap): CurrentProductPriceTransfer
    {
        $currentProductPriceTransfer = new CurrentProductPriceTransfer();
        if (!$priceMap) {
            return $currentProductPriceTransfer;
        }

        $priceProductTransferCollection = $this->convertPriceMapToPriceProductTransferCollection($priceMap);
        $priceProductFilter = $this->buildPriceProductFilterWithCurrentValues();

        // todo get price product transfer
        $price = $this->priceProductService->resolveProductPriceByPriceProductFilter(
            $priceProductTransferCollection,
            $priceProductFilter
        );

        if (!$price) {
            return $currentProductPriceTransfer;
        }

        $currentProductPriceTransfer
            ->setPrice($price);

        $priceProductDimension = $this->priceProductService->resolvePriceProductDimensionByPriceProductFilter(
            $priceProductTransferCollection,
            $priceProductFilter
        );

        if (!$priceProductDimension) {
            return $currentProductPriceTransfer;
        }

        $currencyIsoCode = $priceProductFilter->getCurrencyIsoCode();
        $priceMode = $priceProductFilter->getPriceMode();

        $prices = $priceMap[$priceProductDimension->getType()][$currencyIsoCode][$priceMode];

        return $currentProductPriceTransfer
            ->setPrices($prices);
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    protected function buildPriceProductFilterWithCurrentValues(): PriceProductFilterTransfer
    {
        $currencyIsoCode = $this->currencyClient->getCurrent()->getCode();
        $priceMode = $this->priceClient->getCurrentPriceMode();
        $priceTypeName = $this->priceProductConfig->getPriceTypeDefaultName();

        return (new PriceProductFilterTransfer())
            ->setPriceMode($priceMode)
            ->setCurrencyIsoCode($currencyIsoCode)
            ->setPriceTypeName($priceTypeName);
            //->setQuote();
    }

    /**
     * @param array $priceMap
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function convertPriceMapToPriceProductTransferCollection(array $priceMap): array
    {
        /** @var \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransferCollection */
        $priceProductTransferCollection = [];

        foreach ($priceMap as $priceDimension => $priceData) {
            foreach ($priceData as $currencyCode => $prices) {
                foreach ($prices as $priceMode => $priceTypes) {
                    foreach ($priceTypes as $priceType => $priceAmount) {
                        $index = implode('-', [
                            $priceDimension,
                            $currencyCode,
                            $priceType,
                        ]);
                        if (!isset($priceProductTransferCollection[$index])) {
                            $priceProductTransferCollection[$index] = (new PriceProductTransfer())
                                ->setPriceDimension(
                                    (new PriceProductDimensionTransfer())
                                        ->setType($priceDimension)
                                )
                                ->setMoneyValue(
                                    (new MoneyValueTransfer())
                                        ->setCurrency((new CurrencyTransfer())->setCode($currencyCode))
                                )
                                ->setPriceTypeName($priceType);
                        }
                        if ($priceMode === 'GROSS_MODE') {
                            $priceProductTransferCollection[$index]->getMoneyValue()->setGrossAmount($priceAmount);
                            continue;
                        }

                        $priceProductTransferCollection[$index]->getMoneyValue()->setNetAmount($priceAmount);
                    }
                }
            }
        }

        return $priceProductTransferCollection;
    }
}
