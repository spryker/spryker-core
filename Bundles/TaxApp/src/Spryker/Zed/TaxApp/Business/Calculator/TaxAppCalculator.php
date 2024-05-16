<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Business\Calculator;

use Generated\Shared\Transfer\ApiErrorMessageTransfer;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TaxAppConfigTransfer;
use Generated\Shared\Transfer\TaxAppSaleTransfer;
use Generated\Shared\Transfer\TaxCalculationRequestTransfer;
use Generated\Shared\Transfer\TaxCalculationResponseTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Spryker\Client\TaxApp\TaxAppClientInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\TaxApp\Business\AccessTokenProvider\AccessTokenProviderInterface;
use Spryker\Zed\TaxApp\Business\Aggregator\PriceAggregatorInterface;
use Spryker\Zed\TaxApp\Business\Mapper\TaxAppMapperInterface;

class TaxAppCalculator implements TaxAppCalculatorInterface
{
    use LoggerTrait;

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_NET
     *
     * @var string
     */
    protected const PRICE_MODE_NET = 'NET_MODE';

    /**
     * @var \Spryker\Zed\TaxApp\Business\Mapper\TaxAppMapperInterface
     */
    protected TaxAppMapperInterface $taxAppMapper;

    /**
     * @var \Spryker\Client\TaxApp\TaxAppClientInterface
     */
    protected TaxAppClientInterface $taxAppClient;

    /**
     * @var \Spryker\Zed\TaxApp\Business\AccessTokenProvider\AccessTokenProviderInterface
     */
    protected AccessTokenProviderInterface $accessTokenProvider;

    /**
     * @var array<int, \Spryker\Zed\TaxAppExtension\Dependency\Plugin\CalculableObjectTaxAppExpanderPluginInterface>
     */
    protected array $calculableObjectTaxAppExpanderPlugins;

    /**
     * @var \Spryker\Zed\TaxApp\Business\Aggregator\PriceAggregatorInterface
     */
    protected PriceAggregatorInterface $priceAggregator;

    /**
     * @param \Spryker\Zed\TaxApp\Business\Mapper\TaxAppMapperInterface $taxAppMapper
     * @param \Spryker\Client\TaxApp\TaxAppClientInterface $taxAppClient
     * @param \Spryker\Zed\TaxApp\Business\AccessTokenProvider\AccessTokenProviderInterface $accessTokenProvider
     * @param array<\Spryker\Zed\TaxAppExtension\Dependency\Plugin\CalculableObjectTaxAppExpanderPluginInterface> $calculableObjectTaxAppExpanderPlugins
     * @param \Spryker\Zed\TaxApp\Business\Aggregator\PriceAggregatorInterface $priceAggregator
     */
    public function __construct(
        TaxAppMapperInterface $taxAppMapper,
        TaxAppClientInterface $taxAppClient,
        AccessTokenProviderInterface $accessTokenProvider,
        array $calculableObjectTaxAppExpanderPlugins,
        PriceAggregatorInterface $priceAggregator
    ) {
        $this->taxAppMapper = $taxAppMapper;
        $this->taxAppClient = $taxAppClient;
        $this->accessTokenProvider = $accessTokenProvider;
        $this->calculableObjectTaxAppExpanderPlugins = $calculableObjectTaxAppExpanderPlugins;
        $this->priceAggregator = $priceAggregator;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param \Generated\Shared\Transfer\TaxAppConfigTransfer $taxAppConfigTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer, TaxAppConfigTransfer $taxAppConfigTransfer): void
    {
        $calculableObjectTransfer = $this->executeCalculableObjectTaxAppExpanderPlugins($calculableObjectTransfer);

        $taxAppSaleTransfer = $this->taxAppMapper->mapCalculableObjectToTaxAppSaleTransfer($calculableObjectTransfer, new TaxAppSaleTransfer());

        // for correct tax calculation in NET price mode, at least one shipment must be selected, otherwise tax calculation is skipped.
        if ($calculableObjectTransfer->getPriceModeOrFail() === static::PRICE_MODE_NET && $taxAppSaleTransfer->getShipments()->count() === 0) {
            $taxTotalTransfer = (new TaxTotalTransfer())->setAmount(0);
            $calculableObjectTransfer->getTotalsOrFail()->setTaxTotal($taxTotalTransfer);
            $this->priceAggregator->calculatePriceAggregation($taxAppSaleTransfer, $calculableObjectTransfer);

            return;
        }

        $taxCalculationResponseTransfer = $this->getCachedTaxAppResponseTransfer($calculableObjectTransfer, $taxAppSaleTransfer);

        if (!$taxCalculationResponseTransfer) {
            $taxCalculationResponseTransfer = $this->getTaxCalculationResponse(
                $taxAppSaleTransfer,
                $taxAppConfigTransfer,
                $calculableObjectTransfer->getStoreOrFail(),
            );

            if (!$taxCalculationResponseTransfer->getIsSuccessful()) {
                $apiErrorMessages = array_map(function (ApiErrorMessageTransfer $apiErrorMessageTransfer) {
                    return $apiErrorMessageTransfer->toArray();
                }, $taxCalculationResponseTransfer->getApiErrorMessages()->getArrayCopy());
                $this->getLogger()->error('Tax calculation failed.', ['apiErrorMessages' => $apiErrorMessages]);

                $taxAppSaleTransfer = $this->resetTaxAppSaleTaxTotals($taxAppSaleTransfer);
                $taxCalculationResponseTransfer->setSale($taxAppSaleTransfer);
            }
        }

        $calculableObjectTransfer = $this->priceAggregator->calculatePriceAggregation($taxCalculationResponseTransfer->getSaleOrFail(), $calculableObjectTransfer);

        if ($taxCalculationResponseTransfer->getIsSuccessful()) {
            $calculableObjectTransfer->setTaxAppSaleHash($this->getTaxAppSaleHash($taxAppSaleTransfer));
            $calculableObjectTransfer->setTaxCalculationResponse($taxCalculationResponseTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\TaxAppSaleTransfer $taxAppSaleTransfer
     * @param \Generated\Shared\Transfer\TaxAppConfigTransfer $taxAppConfigTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\TaxCalculationResponseTransfer
     */
    protected function getTaxCalculationResponse(
        TaxAppSaleTransfer $taxAppSaleTransfer,
        TaxAppConfigTransfer $taxAppConfigTransfer,
        StoreTransfer $storeTransfer
    ): TaxCalculationResponseTransfer {
        $taxCalculationRequestTransfer = (new TaxCalculationRequestTransfer())
            ->setSale($taxAppSaleTransfer)
            ->setAuthorization($this->accessTokenProvider->getAccessToken());

        return $this->taxAppClient->requestTaxQuotation($taxCalculationRequestTransfer, $taxAppConfigTransfer, $storeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\TaxAppSaleTransfer $taxAppSaleTransfer
     *
     * @return string
     */
    protected function getTaxAppSaleHash(TaxAppSaleTransfer $taxAppSaleTransfer): string
    {
        return md5(json_encode($taxAppSaleTransfer->toArray()) ?: '');
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param \Generated\Shared\Transfer\TaxAppSaleTransfer $taxAppSaleTransfer
     *
     * @return \Generated\Shared\Transfer\TaxCalculationResponseTransfer|null
     */
    protected function getCachedTaxAppResponseTransfer(
        CalculableObjectTransfer $calculableObjectTransfer,
        TaxAppSaleTransfer $taxAppSaleTransfer
    ): ?TaxCalculationResponseTransfer {
        $currentTaxRequestHash = null;

        if ($calculableObjectTransfer->getTaxAppSaleHash()) {
            $currentTaxRequestHash = $this->getTaxAppSaleHash($taxAppSaleTransfer);
        }

        // Quote was not changed since last tax calculation request. Tax calculation is skipped.
        if ($currentTaxRequestHash === $calculableObjectTransfer->getTaxAppSaleHash() && $calculableObjectTransfer->getTaxCalculationResponse()) {
            return $calculableObjectTransfer->getTaxCalculationResponse();
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\TaxAppSaleTransfer $taxAppSaleTransfer
     *
     * @return \Generated\Shared\Transfer\TaxAppSaleTransfer
     */
    protected function resetTaxAppSaleTaxTotals(TaxAppSaleTransfer $taxAppSaleTransfer): TaxAppSaleTransfer
    {
        $taxAppSaleTransfer->setTaxTotal(0);
        foreach ($taxAppSaleTransfer->getItems() as $taxAppItemTransfer) {
            $taxAppItemTransfer->setTaxTotal(0);
        }
        foreach ($taxAppSaleTransfer->getShipments() as $taxAppShipmentTransfer) {
            $taxAppShipmentTransfer->setTaxTotal(0);
        }

        return $taxAppSaleTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    protected function executeCalculableObjectTaxAppExpanderPlugins(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        foreach ($this->calculableObjectTaxAppExpanderPlugins as $calculableObjectTaxAppExpanderPlugin) {
            $calculableObjectTaxAppExpanderPlugin->expand($calculableObjectTransfer);
        }

        return $calculableObjectTransfer;
    }
}
