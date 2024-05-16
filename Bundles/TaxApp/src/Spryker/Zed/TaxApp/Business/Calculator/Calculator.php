<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\TaxAppConfigTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\TaxApp\Business\AccessTokenProvider\AccessTokenProviderInterface;
use Spryker\Zed\TaxApp\Business\Aggregator\PriceAggregatorInterface;
use Spryker\Zed\TaxApp\Business\Config\ConfigReaderInterface;
use Spryker\Zed\TaxApp\Business\Mapper\Prices\ItemExpensePriceRetriever;
use Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeInterface;

class Calculator implements CalculatorInterface
{
    use LoggerTrait;

    /**
     * @var \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeInterface
     */
    protected TaxAppToStoreFacadeInterface $storeFacade;

    /**
     * @var \Spryker\Zed\TaxApp\Business\Config\ConfigReaderInterface
     */
    protected ConfigReaderInterface $configReader;

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
     * @var \Spryker\Zed\TaxApp\Business\Calculator\FallbackCalculatorInterface
     */
    protected FallbackCalculatorInterface $fallbackQuoteCalculator;

    /**
     * @var \Spryker\Zed\TaxApp\Business\Calculator\FallbackCalculatorInterface
     */
    protected FallbackCalculatorInterface $fallbackOrderCalculator;

    /**
     * @var \Spryker\Zed\TaxApp\Business\Calculator\TaxAppCalculatorInterface
     */
    protected TaxAppCalculatorInterface $taxAppCalculator;

    /**
     * @param \Spryker\Zed\TaxApp\Dependency\Facade\TaxAppToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\TaxApp\Business\Config\ConfigReaderInterface $configReader
     * @param \Spryker\Zed\TaxApp\Business\Calculator\FallbackCalculatorInterface $fallbackQuoteCalculator
     * @param \Spryker\Zed\TaxApp\Business\Calculator\FallbackCalculatorInterface $fallbackOrderCalculator
     * @param \Spryker\Zed\TaxApp\Business\Calculator\TaxAppCalculatorInterface $taxAppCalculator
     */
    public function __construct(
        TaxAppToStoreFacadeInterface $storeFacade,
        ConfigReaderInterface $configReader,
        FallbackCalculatorInterface $fallbackQuoteCalculator,
        FallbackCalculatorInterface $fallbackOrderCalculator,
        TaxAppCalculatorInterface $taxAppCalculator
    ) {
        $this->storeFacade = $storeFacade;
        $this->configReader = $configReader;
        $this->fallbackQuoteCalculator = $fallbackQuoteCalculator;
        $this->fallbackOrderCalculator = $fallbackOrderCalculator;
        $this->taxAppCalculator = $taxAppCalculator;
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

            $this->recalculateUsingFallbackCalculator($calculableObjectTransfer);

            return;
        }

        if ($calculableObjectTransfer->getOriginalQuote()) {
            $calculableObjectTransfer->getOriginalQuoteOrFail()->setTaxVendor($taxAppConfigTransfer->getVendorCode());
        }
        $this->setHideTaxInCartFlagToTrue($calculableObjectTransfer);

        $this->taxAppCalculator->recalculate($calculableObjectTransfer, $taxAppConfigTransfer);
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
            $idStore = $this->storeFacade->getStoreByName($storeTransfer->getNameOrFail())->getIdStoreOrFail();
        }

        return $this->configReader->getTaxAppConfigByIdStore($idStore);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    protected function recalculateUsingFallbackCalculator(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        if ($calculableObjectTransfer->getOriginalQuote()) {
            $this->fallbackQuoteCalculator->recalculate($calculableObjectTransfer);

            return;
        }

        if ($calculableObjectTransfer->getOriginalOrder()) {
            $this->fallbackOrderCalculator->recalculate($calculableObjectTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    protected function setHideTaxInCartFlagToTrue(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        if ($calculableObjectTransfer->getOriginalQuote() !== null && $calculableObjectTransfer->getPriceMode() === ItemExpensePriceRetriever::PRICE_MODE_NET) {
            $calculableObjectTransfer->getOriginalQuote()->setHideTaxInCart(true);
        }

        return $calculableObjectTransfer;
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
