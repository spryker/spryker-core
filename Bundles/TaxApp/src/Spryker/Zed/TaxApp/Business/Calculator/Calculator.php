<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Business\Calculator;

use Generated\Shared\Transfer\ApiErrorMessageTransfer;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TaxAppConfigConditionsTransfer;
use Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer;
use Generated\Shared\Transfer\TaxAppConfigTransfer;
use Generated\Shared\Transfer\TaxAppSaleTransfer;
use Generated\Shared\Transfer\TaxCalculationRequestTransfer;
use Generated\Shared\Transfer\TaxCalculationResponseTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Spryker\Client\TaxApp\TaxAppClientInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\TaxApp\Business\AccessTokenProvider\AccessTokenProviderInterface;
use Spryker\Zed\TaxApp\Business\Aggregator\PriceAggregatorInterface;
use Spryker\Zed\TaxApp\Business\Mapper\Prices\ItemExpenseItemExpensePriceRetriever;
use Spryker\Zed\TaxApp\Business\Mapper\TaxAppMapperInterface;
use Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeInterface;
use Spryker\Zed\TaxApp\Persistence\TaxAppRepositoryInterface;

class Calculator implements CalculatorInterface
{
    use LoggerTrait;

    /**
     * @var \Spryker\Zed\TaxApp\Business\Mapper\TaxAppMapperInterface
     */
    protected TaxAppMapperInterface $taxAppMapper;

    /**
     * @var \Spryker\Client\TaxApp\TaxAppClientInterface
     */
    protected TaxAppClientInterface $taxAppClient;

    /**
     * @var \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeInterface
     */
    protected TaxAppToStoreFacadeInterface $storeFacade;

    /**
     * @var \Spryker\Zed\TaxApp\Persistence\TaxAppRepositoryInterface
     */
    protected TaxAppRepositoryInterface $taxAppRepository;

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
     * @param \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\TaxApp\Persistence\TaxAppRepositoryInterface $taxAppRepository
     * @param \Spryker\Zed\TaxApp\Business\AccessTokenProvider\AccessTokenProviderInterface $accessTokenProvider
     * @param array<\Spryker\Zed\TaxAppExtension\Dependency\Plugin\CalculableObjectTaxAppExpanderPluginInterface> $calculableObjectTaxAppExpanderPlugins
     * @param \Spryker\Zed\TaxApp\Business\Aggregator\PriceAggregatorInterface $priceAggregator
     */
    public function __construct(
        TaxAppMapperInterface $taxAppMapper,
        TaxAppClientInterface $taxAppClient,
        TaxAppToStoreFacadeInterface $storeFacade,
        TaxAppRepositoryInterface $taxAppRepository,
        AccessTokenProviderInterface $accessTokenProvider,
        array $calculableObjectTaxAppExpanderPlugins,
        PriceAggregatorInterface $priceAggregator
    ) {
        $this->taxAppMapper = $taxAppMapper;
        $this->taxAppClient = $taxAppClient;
        $this->storeFacade = $storeFacade;
        $this->taxAppRepository = $taxAppRepository;
        $this->accessTokenProvider = $accessTokenProvider;
        $this->calculableObjectTaxAppExpanderPlugins = $calculableObjectTaxAppExpanderPlugins;
        $this->priceAggregator = $priceAggregator;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $taxAppConfigTransfer = $this->getTaxAppConfigTransfer($calculableObjectTransfer);

        if ($taxAppConfigTransfer === null || !$taxAppConfigTransfer->getIsActive()) {
            $this->setHideTaxInCartFlagToFalse($calculableObjectTransfer);

            return;
        }

        $calculableObjectTransfer = $this->executeCalculableObjectTaxAppExpanderPlugins($calculableObjectTransfer);

        $taxAppSaleTransfer = $this->taxAppMapper->mapCalculableObjectToTaxAppSaleTransfer($calculableObjectTransfer, new TaxAppSaleTransfer());

        if (!$taxAppSaleTransfer->getShipments()->count()) {
            $taxTotalTransfer = (new TaxTotalTransfer())->setAmount(0);
            $calculableObjectTransfer->getTotalsOrFail()->setTaxTotal($taxTotalTransfer);
            $this->priceAggregator->calculatePriceAggregation($taxAppSaleTransfer, $calculableObjectTransfer);

            $this->getLogger()->info('At least one shipment must be selected, Tax calculation is skipped.');

            return;
        }

        $taxCalculationResponseTransfer = null;
        if ($calculableObjectTransfer->getTaxAppSaleHash()) {
            $currentTaxRequestHash = $this->getTaxAppSaleHash($taxAppSaleTransfer);
            if ($currentTaxRequestHash === $calculableObjectTransfer->getTaxAppSaleHash() && $calculableObjectTransfer->getTaxCalculationResponse()) {
                $this->getLogger()->info('Quote was not changed since last tax calculation request. Tax calculation is skipped.');

                $taxCalculationResponseTransfer = $calculableObjectTransfer->getTaxCalculationResponse();
            }
        }

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
                $this->getLogger()->warning('Tax calculation failed.', ['apiErrorMessages' => $apiErrorMessages]);

                return;
            }
        }

        $calculableObjectTransfer = $this->setTaxTotal($taxCalculationResponseTransfer, $calculableObjectTransfer);
        $calculableObjectTransfer = $this->priceAggregator->calculatePriceAggregation($taxCalculationResponseTransfer->getSaleOrFail(), $calculableObjectTransfer);

        $calculableObjectTransfer->setTaxAppSaleHash($this->getTaxAppSaleHash($taxAppSaleTransfer));
        $calculableObjectTransfer->setTaxCalculationResponse($taxCalculationResponseTransfer);
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
        $taxCalculationRequestTransfer = (new TaxCalculationRequestTransfer())->setSale($taxAppSaleTransfer);

        $taxCalculationRequestTransfer = $this->expandTaxCalculationRequestWithAccessToken($taxCalculationRequestTransfer);

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
     *
     * @return \Generated\Shared\Transfer\TaxAppConfigTransfer|null
     */
    protected function getTaxAppConfigTransfer(CalculableObjectTransfer $calculableObjectTransfer): ?TaxAppConfigTransfer
    {
        $storeTransfer = $calculableObjectTransfer->getStoreOrFail();
        $idStore = $storeTransfer->getIdStore();

        if (!$idStore) {
            $idStore = $this->storeFacade->getCurrentStore()->getIdStoreOrFail();
        }

        $taxAppConfigConditionsTransfer = new TaxAppConfigConditionsTransfer();
        $taxAppConfigConditionsTransfer->addFkStore($idStore);

        $taxAppConfigCriteriaTransfer = (new TaxAppConfigCriteriaTransfer())->setTaxAppConfigConditions($taxAppConfigConditionsTransfer);

        $taxAppConfigCollectionTransfer = $this->taxAppRepository->getTaxAppConfigCollection($taxAppConfigCriteriaTransfer);

        if (!$taxAppConfigCollectionTransfer->getTaxAppConfigs()->count()) {
            return null;
        }

        return $taxAppConfigCollectionTransfer->getTaxAppConfigs()->offsetGet(0);
    }

    /**
     * @param \Generated\Shared\Transfer\TaxCalculationResponseTransfer $taxCalculationResponseTransfer
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    protected function setTaxTotal(
        TaxCalculationResponseTransfer $taxCalculationResponseTransfer,
        CalculableObjectTransfer $calculableObjectTransfer
    ): CalculableObjectTransfer {
        if ($taxCalculationResponseTransfer->getIsSuccessful()) {
            $taxTotal = $taxCalculationResponseTransfer->getSaleOrFail()->getTaxTotalOrFail();

            $taxTotalTransfer = new TaxTotalTransfer();
            $taxTotalTransfer->setAmount((int)$taxTotal);

            $calculableObjectTransfer->getTotalsOrFail()->setTaxTotal($taxTotalTransfer);
        }

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    protected function executeCalculableObjectTaxAppExpanderPlugins(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $calculableObjectTransfer = $this->setHideTaxInCartFlagToTrue($calculableObjectTransfer);

        foreach ($this->calculableObjectTaxAppExpanderPlugins as $calculableObjectTaxAppExpanderPlugin) {
            $calculableObjectTaxAppExpanderPlugin->expand($calculableObjectTransfer);
        }

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    protected function setHideTaxInCartFlagToTrue(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        if ($calculableObjectTransfer->getOriginalQuote() !== null && $calculableObjectTransfer->getPriceMode() === ItemExpenseItemExpensePriceRetriever::PRICE_MODE_NET) {
            $quoteTransfer = $calculableObjectTransfer->getOriginalQuote();
            $quoteTransfer->setHideTaxInCart(true);
        }

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TaxCalculationRequestTransfer $taxCalculationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\TaxCalculationRequestTransfer
     */
    protected function expandTaxCalculationRequestWithAccessToken(
        TaxCalculationRequestTransfer $taxCalculationRequestTransfer
    ): TaxCalculationRequestTransfer {
        $taxCalculationRequestTransfer->setAuthorization($this->accessTokenProvider->getAccessToken());

        return $taxCalculationRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    protected function setHideTaxInCartFlagToFalse(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        if ($calculableObjectTransfer->getOriginalQuote() !== null) {
            $calculableObjectTransfer->getOriginalQuote()->setHideTaxInCart(false);
        }
    }
}
