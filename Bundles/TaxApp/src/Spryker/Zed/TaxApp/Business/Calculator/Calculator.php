<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
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
use Spryker\Zed\TaxApp\Business\Mapper\Prices\PriceFormatter;
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
     * @param \Spryker\Zed\TaxApp\Business\Mapper\TaxAppMapperInterface $taxAppMapper
     * @param \Spryker\Client\TaxApp\TaxAppClientInterface $taxAppClient
     * @param \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\TaxApp\Persistence\TaxAppRepositoryInterface $taxAppRepository
     * @param \Spryker\Zed\TaxApp\Business\AccessTokenProvider\AccessTokenProviderInterface $accessTokenProvider
     * @param array<\Spryker\Zed\TaxAppExtension\Dependency\Plugin\CalculableObjectTaxAppExpanderPluginInterface> $calculableObjectTaxAppExpanderPlugins
     */
    public function __construct(
        TaxAppMapperInterface $taxAppMapper,
        TaxAppClientInterface $taxAppClient,
        TaxAppToStoreFacadeInterface $storeFacade,
        TaxAppRepositoryInterface $taxAppRepository,
        AccessTokenProviderInterface $accessTokenProvider,
        array $calculableObjectTaxAppExpanderPlugins
    ) {
        $this->taxAppMapper = $taxAppMapper;
        $this->taxAppClient = $taxAppClient;
        $this->storeFacade = $storeFacade;
        $this->taxAppRepository = $taxAppRepository;
        $this->accessTokenProvider = $accessTokenProvider;
        $this->calculableObjectTaxAppExpanderPlugins = $calculableObjectTaxAppExpanderPlugins;
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
            return;
        }

        $calculableObjectTransfer = $this->executeCalculableObjectTaxAppExpanderPlugins($calculableObjectTransfer);

        $taxAppSaleTransfer = $this->taxAppMapper->mapCalculableObjectToTaxAppSaleTransfer($calculableObjectTransfer, new TaxAppSaleTransfer());

        if (!$taxAppSaleTransfer->getShipments()->count()) {
            $this->getLogger()->info('At least one shipment must be selected, Tax calculation is skipped.');

            return;
        }

        $taxCalculationRequestTransfer = (new TaxCalculationRequestTransfer())->setSale($taxAppSaleTransfer);

        $taxCalculationRequestTransfer = $this->expandTaxCalculationRequestWithAccessToken($taxCalculationRequestTransfer);

        $taxCalculationResponseTransfer = $this->taxAppClient->requestTaxQuotation($taxCalculationRequestTransfer, $taxAppConfigTransfer, $calculableObjectTransfer->getStoreOrFail());

        $calculableObjectTransfer = $this->setTaxTotal($taxCalculationResponseTransfer, $calculableObjectTransfer);
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
        $calculableObjectTransfer = $this->expandQuoteTransferWitHideTaxInCart($calculableObjectTransfer);

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
    protected function expandQuoteTransferWitHideTaxInCart(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        if ($calculableObjectTransfer->getOriginalQuote() !== null && $calculableObjectTransfer->getPriceMode() === PriceFormatter::PRICE_MODE_NET) {
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
}
