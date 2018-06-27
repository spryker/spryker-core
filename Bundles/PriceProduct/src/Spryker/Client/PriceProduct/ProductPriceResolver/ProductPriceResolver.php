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
use Spryker\Client\PriceProduct\Dependency\Client\PriceProductToQuoteClientInterface;
use Spryker\Client\PriceProduct\PriceProductConfig;
use Spryker\Service\PriceProduct\PriceProductServiceInterface;

class ProductPriceResolver implements ProductPriceResolverInterface
{
    protected const PRICE_KEY_SEPARATOR = '-';

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
     * @var \Spryker\Client\PriceProduct\PriceProductConfig
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Service\PriceProduct\PriceProductServiceInterface
     */
    protected $priceProductService;

    /**
     * @param \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToPriceClientInterface $priceClient
     * @param \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToCurrencyClientInterface $currencyClient
     * @param \Spryker\Client\PriceProduct\PriceProductConfig $priceProductConfig
     * @param \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToQuoteClientInterface $quoteClient
     * @param \Spryker\Service\PriceProduct\PriceProductServiceInterface $priceProductService
     */
    public function __construct(
        PriceProductToPriceClientInterface $priceClient,
        PriceProductToCurrencyClientInterface $currencyClient,
        PriceProductConfig $priceProductConfig,
        PriceProductToQuoteClientInterface $quoteClient,
        PriceProductServiceInterface $priceProductService
    ) {
        $this->priceProductConfig = $priceProductConfig;
        $this->priceClient = $priceClient;
        $this->currencyClient = $currencyClient;
        $this->quoteClient = $quoteClient;
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

        $priceProductTransfers = $this->convertPriceMapToPriceProductTransfers($priceMap);
        $priceProductFilter = $this->buildPriceProductFilterWithCurrentValues();

        $priceProductTransfer = $this->priceProductService->resolveProductPriceByPriceProductFilter(
            $priceProductTransfers,
            $priceProductFilter
        );

        if (!$priceProductTransfer) {
            return $currentProductPriceTransfer;
        }

        $currencyIsoCode = $priceProductFilter->getCurrencyIsoCode();
        $priceMode = $priceProductFilter->getPriceMode();

        $price = $this->getPriceValueByPriceMode($priceProductTransfer->getMoneyValue(), $priceMode);

        if (!$price) {
            return $currentProductPriceTransfer;
        }

        $prices = $priceMap[$priceProductTransfer->getPriceDimension()->getType()][$currencyIsoCode][$priceMode] ?? [];

        return $currentProductPriceTransfer
            ->setPrice($price)
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
        $quote = $this->quoteClient->getQuote();

        return (new PriceProductFilterTransfer())
            ->setPriceMode($priceMode)
            ->setCurrencyIsoCode($currencyIsoCode)
            ->setPriceTypeName($priceTypeName)
            ->setQuote($quote);
    }

    /**
     * @param array $priceMap
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function convertPriceMapToPriceProductTransfers(array $priceMap): array
    {
        /** @var \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers */
        $priceProductTransfers = [];

        foreach ($priceMap as $priceDimension => $priceData) {
            foreach ($priceData as $currencyCode => $prices) {
                foreach ($prices as $priceMode => $priceTypes) {
                    foreach ($priceTypes as $priceType => $priceAmount) {
                        $index = implode(static::PRICE_KEY_SEPARATOR, [
                            $priceDimension,
                            $currencyCode,
                            $priceType,
                        ]);
                        if (!isset($priceProductTransfers[$index])) {
                            $priceProductTransfers[$index] = (new PriceProductTransfer())
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
                        if ($priceMode === $this->priceProductConfig->getPriceModeIdentifierForNetType()) {
                            $priceProductTransfers[$index]->getMoneyValue()->setNetAmount($priceAmount);
                            continue;
                        }

                        $priceProductTransfers[$index]->getMoneyValue()->setGrossAmount($priceAmount);
                    }
                }
            }
        }

        return $priceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     * @param string $priceMode
     *
     * @return int|null
     */
    protected function getPriceValueByPriceMode(MoneyValueTransfer $moneyValueTransfer, string $priceMode): ?int
    {
        if ($priceMode === $this->priceProductConfig->getPriceModeIdentifierForNetType()) {
            return $moneyValueTransfer->getNetAmount();
        }

        return $moneyValueTransfer->getGrossAmount();
    }
}
