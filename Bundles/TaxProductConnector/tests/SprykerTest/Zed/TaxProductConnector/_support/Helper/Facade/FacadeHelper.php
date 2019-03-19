<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxProductConnector\Helper\Facade;


use Codeception\Module;
use PHPUnit_Framework_MockObject_MockObject;
use Generated\Shared\DataBuilder\SequenceNumberSettingsBuilder;
use Spryker\Zed\Tax\Business\Model\CalculatorInterface;
use Spryker\Zed\TaxProductConnector\Business\Calculator\ProductItemTaxRateCalculator as ProductItemTaxRateCalculatorWithMultipleShipmentTaxRate;
use Spryker\Zed\TaxProductConnector\Business\Model\ProductItemTaxRateCalculator;
use Spryker\Zed\TaxProductConnector\Business\StrategyResolver\ProductItemTaxRateCalculatorStrategyResolver;
use Spryker\Zed\TaxProductConnector\Business\StrategyResolver\ProductItemTaxRateCalculatorStrategyResolverInterface;
use Spryker\Zed\TaxProductConnector\Business\TaxProductConnectorBusinessFactory;
use Spryker\Zed\TaxProductConnector\Business\TaxProductConnectorFacade;
use Spryker\Zed\TaxProductConnector\Business\TaxProductConnectorFacadeInterface;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxBridge;
use Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainer;
use Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainerInterface;

class FacadeHelper extends Module
{
    /**
     * @param \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\TaxProductConnector\TaxProductConnectorBusinessFactory $mockedTaxProductConnectorBusinessFactory
     * @param \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxInterface $mockedTaxProductConnectorToTaxFacadeBridge
     *
     * @return \Spryker\Zed\TaxProductConnector\Business\TaxProductConnectorFacadeInterface
     */
    public function getTaxProductConnectorFacadeWithMockedFactory(
        PHPUnit_Framework_MockObject_MockObject $mockedTaxProductConnectorBusinessFactory,
        PHPUnit_Framework_MockObject_MockObject $mockedTaxProductConnectorToTaxFacadeBridge
    ): TaxProductConnectorFacadeInterface {
        $taxProductConnectorFacade = $this->createTaxProductConnectorFacade();

        $mockedTaxProductConnectorBusinessFactory->method('createProductItemTaxRateCalculatorStrategyResolver')->willReturn(
            $this->createProductItemTaxRateCalculatorStrategyResolver($mockedTaxProductConnectorToTaxFacadeBridge)
        );

        $mockedTaxProductConnectorBusinessFactory->method('createProductItemTaxRateCalculator')->willReturn(
            $this->createProductItemTaxRateCalculator($mockedTaxProductConnectorToTaxFacadeBridge)
        );
        $mockedTaxProductConnectorBusinessFactory->method('createProductItemTaxRateCalculatorWithMultipleShipmentTaxRate')->willReturn(
            $this->createProductItemTaxRateCalculatorWithMultipleShipmentTaxRate($mockedTaxProductConnectorToTaxFacadeBridge)
        );

        $taxProductConnectorFacade->setFactory($mockedTaxProductConnectorBusinessFactory);

        return $taxProductConnectorFacade;
    }

    /**
     * @return \Spryker\Zed\TaxProductConnector\Business\TaxProductConnectorFacadeInterface
     */
    protected function createTaxProductConnectorFacade(): TaxProductConnectorFacadeInterface
    {
        return new TaxProductConnectorFacade();
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxInterface $mockedTaxProductConnectorToTaxFacadeBridge
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\TaxProductConnector\Business\StrategyResolver\ProductItemTaxRateCalculatorStrategyResolver
     */
    public function createProductItemTaxRateCalculatorStrategyResolver(PHPUnit_Framework_MockObject_MockObject $mockedTaxProductConnectorToTaxFacadeBridge): ProductItemTaxRateCalculatorStrategyResolverInterface
    {
        $strategyContainer = [];

        $strategyContainer[ProductItemTaxRateCalculatorStrategyResolver::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () use ($mockedTaxProductConnectorToTaxFacadeBridge) {
            return $this->createProductItemTaxRateCalculator($mockedTaxProductConnectorToTaxFacadeBridge);
        };

        $strategyContainer[ProductItemTaxRateCalculatorStrategyResolver::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () use ($mockedTaxProductConnectorToTaxFacadeBridge) {
            return $this->createProductItemTaxRateCalculatorWithMultipleShipmentTaxRate($mockedTaxProductConnectorToTaxFacadeBridge);
        };

        return new ProductItemTaxRateCalculatorStrategyResolver($strategyContainer);
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxInterface $mockedTaxProductConnectorToTaxFacadeBridge
     *
     * @return \Spryker\Zed\Tax\Business\Model\CalculatorInterface
     */
    protected function createProductItemTaxRateCalculator(PHPUnit_Framework_MockObject_MockObject $mockedTaxProductConnectorToTaxFacadeBridge): CalculatorInterface
    {
        return new ProductItemTaxRateCalculator(
            $this->createQueryContainer(),
            $mockedTaxProductConnectorToTaxFacadeBridge
        );
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxInterface $mockedTaxProductConnectorToTaxFacadeBridge
     *
     * @return \Spryker\Zed\Tax\Business\Model\CalculatorInterface
     */
    protected function createProductItemTaxRateCalculatorWithMultipleShipmentTaxRate(PHPUnit_Framework_MockObject_MockObject $mockedTaxProductConnectorToTaxFacadeBridge): CalculatorInterface
    {
        return new ProductItemTaxRateCalculatorWithMultipleShipmentTaxRate(
            $this->createQueryContainer(),
            $mockedTaxProductConnectorToTaxFacadeBridge
        );
    }

    /**
     * @return \Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainerInterface
     */
    protected function createQueryContainer(): TaxProductConnectorQueryContainerInterface
    {
        return new TaxProductConnectorQueryContainer();
    }
}