<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\TaxProductConnector\Business\Calculator\CalculatorInterface;
use Spryker\Zed\TaxProductConnector\Business\Calculator\ProductItemTaxRateCalculator as ProductItemTaxRateCalculatorWithMultipleShipmentTaxRate;
use Spryker\Zed\TaxProductConnector\Business\Model\ProductItemTaxRateCalculator;
use Spryker\Zed\TaxProductConnector\Business\Product\ProductAbstractTaxReader;
use Spryker\Zed\TaxProductConnector\Business\Product\ProductAbstractTaxReaderInterface;
use Spryker\Zed\TaxProductConnector\Business\Product\ProductAbstractTaxSetMapper;
use Spryker\Zed\TaxProductConnector\Business\Product\ProductAbstractTaxWriter;
use Spryker\Zed\TaxProductConnector\Business\StrategyResolver\ProductItemTaxRateCalculatorStrategyResolver;
use Spryker\Zed\TaxProductConnector\Business\StrategyResolver\ProductItemTaxRateCalculatorStrategyResolverInterface;
use Spryker\Zed\TaxProductConnector\TaxProductConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\TaxProductConnector\TaxProductConnectorConfig getConfig()
 * @method \Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorRepositoryInterface getRepository()
 */
class TaxProductConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\TaxProductConnector\Business\Product\ProductAbstractTaxWriter
     */
    public function createProductAbstractTaxWriter()
    {
        return new ProductAbstractTaxWriter($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\TaxProductConnector\Business\Product\ProductAbstractTaxSetMapper
     */
    public function createProductAbstractTaxSetMapper()
    {
        return new ProductAbstractTaxSetMapper($this->getQueryContainer());
    }

    /**
     * @deprecated Use createProductItemTaxRateCalculatorWithMultipleShipmentTaxRate() instead.
     *
     * @return \Spryker\Zed\TaxProductConnector\Business\Calculator\CalculatorInterface
     */
    public function createProductItemTaxRateCalculator()
    {
        return new ProductItemTaxRateCalculator($this->getQueryContainer(), $this->getTaxFacade());
    }

    /**
     * @return \Spryker\Zed\TaxProductConnector\Business\Calculator\CalculatorInterface
     */
    public function createProductItemTaxRateCalculatorWithMultipleShipmentTaxRate(): CalculatorInterface
    {
        return new ProductItemTaxRateCalculatorWithMultipleShipmentTaxRate(
            $this->getQueryContainer(),
            $this->getTaxFacade()
        );
    }

    /**
     * @return \Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxInterface
     */
    protected function getTaxFacade()
    {
        return $this->getProvidedDependency(TaxProductConnectorDependencyProvider::FACADE_TAX);
    }

    /**
     * @return \Spryker\Zed\TaxProductConnector\Business\Product\ProductAbstractTaxReaderInterface
     */
    public function createProductAbstractTaxReader(): ProductAbstractTaxReaderInterface
    {
        return new ProductAbstractTaxReader($this->getRepository());
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only. Use $this->createProductItemTaxRateCalculatorWithMultipleShipmentTaxRate() instead.
     *
     * @return \Spryker\Zed\TaxProductConnector\Business\StrategyResolver\ProductItemTaxRateCalculatorStrategyResolver
     */
    public function createProductItemTaxRateCalculatorStrategyResolver(): ProductItemTaxRateCalculatorStrategyResolverInterface
    {
        $strategyContainer = [];

        $strategyContainer[ProductItemTaxRateCalculatorStrategyResolver::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createProductItemTaxRateCalculator();
        };

        $strategyContainer[ProductItemTaxRateCalculatorStrategyResolver::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createProductItemTaxRateCalculatorWithMultipleShipmentTaxRate();
        };

        return new ProductItemTaxRateCalculatorStrategyResolver($strategyContainer);
    }
}
