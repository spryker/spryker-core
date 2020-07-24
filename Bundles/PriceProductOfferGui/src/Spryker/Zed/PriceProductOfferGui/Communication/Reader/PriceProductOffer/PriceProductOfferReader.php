<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferGui\Communication\Reader\PriceProductOffer;

use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\PriceProductOfferGui\Dependency\Facade\PriceProductOfferGuiToPriceFacadeInterface;
use Spryker\Zed\PriceProductOfferGui\Dependency\Facade\PriceProductOfferGuiToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductOfferGui\Dependency\Facade\PriceProductOfferGuiToPriceProductOfferFacadeInterface;
use Spryker\Zed\PriceProductOfferGui\Dependency\Service\PriceProductOfferGuiToUtilEncodingServiceInterface;

class PriceProductOfferReader implements PriceProductOfferReaderInterface
{
    /**
     * @var \Spryker\Zed\PriceProductOfferGui\Dependency\Facade\PriceProductOfferGuiToPriceProductOfferFacadeInterface
     */
    protected $priceProductOfferFacade;

    /**
     * @var \Spryker\Zed\PriceProductOfferGui\Dependency\Facade\PriceProductOfferGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\PriceProductOfferGui\Dependency\Facade\PriceProductOfferGuiToPriceFacadeInterface
     */
    protected $priceFacade;

    /**
     * @var \Spryker\Zed\PriceProductOfferGui\Dependency\Service\PriceProductOfferGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var string
     */
    protected static $netPriceModeIdentifier;

    /**
     * @var string
     */
    protected static $grossPriceModeIdentifier;

    /**
     * @param \Spryker\Zed\PriceProductOfferGui\Dependency\Facade\PriceProductOfferGuiToPriceProductOfferFacadeInterface $priceProductOfferFacade
     * @param \Spryker\Zed\PriceProductOfferGui\Dependency\Facade\PriceProductOfferGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\PriceProductOfferGui\Dependency\Facade\PriceProductOfferGuiToPriceFacadeInterface $priceFacade
     * @param \Spryker\Zed\PriceProductOfferGui\Dependency\Service\PriceProductOfferGuiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        PriceProductOfferGuiToPriceProductOfferFacadeInterface $priceProductOfferFacade,
        PriceProductOfferGuiToPriceProductFacadeInterface $priceProductFacade,
        PriceProductOfferGuiToPriceFacadeInterface $priceFacade,
        PriceProductOfferGuiToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->priceProductOfferFacade = $priceProductOfferFacade;
        $this->priceProductFacade = $priceProductFacade;
        $this->priceFacade = $priceFacade;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @phpstan-return array<mixed>
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return array
     */
    public function getProductOfferPricesData(ProductOfferTransfer $productOfferTransfer): array
    {
        $priceTable = [];
        $currencies = [];
        $priceData = [];
        $productOfferTransfer = $this->priceProductOfferFacade->expandProductOfferWithPrices($productOfferTransfer);

        if (!$productOfferTransfer->getPrices()->count()) {
            return [];
        }

        foreach ($productOfferTransfer->getPrices() as $priceProductTransfer) {
            $priceTable = $this->getPriceTable($priceProductTransfer, $priceTable);
            $priceData = $this->getPriceData($priceProductTransfer, $priceData);
            $currencyTransfer = $priceProductTransfer->getMoneyValue()->getCurrency();

            if ($currencyTransfer) {
                $currencies[$currencyTransfer->getCode()] = $currencyTransfer;
            }
        }

        return [
            'priceTable' => $priceTable,
            'currencies' => $currencies,
            'priceData' => $priceData,
            'productOffer' => $productOfferTransfer,
        ];
    }

    /**
     * @phpstan-param array<mixed> $priceTable
     *
     * @phpstan-return array<mixed>
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param array $priceTable
     *
     * @return array
     */
    protected function getPriceTable(
        PriceProductTransfer $priceProductTransfer,
        array $priceTable
    ): array {
        $priceTypeTransfer = $priceProductTransfer->getPriceType();

        $grossPriceModeIdentifier = $this->getGrossPriceModeIdentifier();
        $netPriceModeIdentifier = $this->getNetPriceModeIdentifier();

        if (!$priceTypeTransfer) {
            return $priceTable;
        }

        $priceType = $priceTypeTransfer->getName();
        $priceModeConfiguration = $priceTypeTransfer->getPriceModeConfiguration();

        $storeName = $priceProductTransfer->getMoneyValue()->getStore()->getName();
        $currencyIsoCode = $priceProductTransfer->getMoneyValue()->getCurrency()->getCode();

        if ($priceModeConfiguration === $this->getPriceModeIdentifierForBothType()) {
            $priceTable[$storeName][$currencyIsoCode][$netPriceModeIdentifier][$priceType] = $priceProductTransfer;
            $priceTable[$storeName][$currencyIsoCode][$grossPriceModeIdentifier][$priceType] = $priceProductTransfer;
        } else {
            $priceTable[$storeName][$currencyIsoCode][$priceModeConfiguration][$priceType] = $priceProductTransfer;
        }

        return $priceTable;
    }

    /**
     * @phpstan-param array<mixed> $priceData
     *
     * @phpstan-return array<mixed>
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param array $priceData
     *
     * @return array
     */
    protected function getPriceData(
        PriceProductTransfer $priceProductTransfer,
        array $priceData
    ): array {
        $storeName = $priceProductTransfer->getMoneyValue()->getStore()->getName();
        $currencyIsoCode = $priceProductTransfer->getMoneyValue()->getCurrency()->getCode();

        $priceData[$storeName][$currencyIsoCode] = $this->utilEncodingService
            ->decodeJson($priceProductTransfer->getMoneyValue()->getPriceData(), true);

        return $priceData;
    }

    /**
     * @return string
     */
    protected function getPriceModeIdentifierForBothType(): string
    {
        return $this->priceProductFacade->getPriceModeIdentifierForBothType();
    }

    /**
     * @return string
     */
    protected function getNetPriceModeIdentifier(): string
    {
        if (!static::$netPriceModeIdentifier) {
            static::$netPriceModeIdentifier = $this->priceFacade->getNetPriceModeIdentifier();
        }

        return static::$netPriceModeIdentifier;
    }

    /**
     * @return string
     */
    protected function getGrossPriceModeIdentifier(): string
    {
        if (!static::$grossPriceModeIdentifier) {
            static::$grossPriceModeIdentifier = $this->priceFacade->getGrossPriceModeIdentifier();
        }

        return static::$grossPriceModeIdentifier;
    }
}
