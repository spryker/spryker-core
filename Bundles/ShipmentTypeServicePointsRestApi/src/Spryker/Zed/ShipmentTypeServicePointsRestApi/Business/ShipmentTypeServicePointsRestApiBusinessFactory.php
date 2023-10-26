<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePointsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Mapper\MultiShipmentQuoteMapper;
use Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Mapper\QuoteMapperInterface;
use Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Mapper\SingleShipmentQuoteMapper;
use Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Reader\CustomerReader;
use Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Reader\CustomerReaderInterface;
use Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Reader\ShipmentTypeReader;
use Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Reader\ShipmentTypeReaderInterface;
use Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\StrategyResolver\QuoteMapperStrategyResolver;
use Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\StrategyResolver\QuoteMapperStrategyResolverInterface;
use Spryker\Zed\ShipmentTypeServicePointsRestApi\Dependency\Facade\ShipmentTypeServicePointsRestApiToCustomerFacadeInterface;
use Spryker\Zed\ShipmentTypeServicePointsRestApi\Dependency\Facade\ShipmentTypeServicePointsRestApiToShipmentFacadeInterface;
use Spryker\Zed\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiDependencyProvider;

/**
 * @method \Spryker\Zed\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiConfig getConfig()
 */
class ShipmentTypeServicePointsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Mapper\QuoteMapperInterface
     */
    public function createMultiShipmentQuoteMapper(): QuoteMapperInterface
    {
        return new MultiShipmentQuoteMapper(
            $this->createShipmentTypeReader(),
            $this->createCustomerReader(),
            $this->getShipmentFacade(),
        );
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *             Use {@link \Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\ShipmentTypeServicePointsRestApiBusinessFactory::createMultiShipmentQuoteMapper()} instead.
     *
     * @return \Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Mapper\QuoteMapperInterface
     */
    public function createSingleShipmentQuoteMapper(): QuoteMapperInterface
    {
        return new SingleShipmentQuoteMapper(
            $this->createShipmentTypeReader(),
            $this->createCustomerReader(),
            $this->getShipmentFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Reader\ShipmentTypeReaderInterface
     */
    public function createShipmentTypeReader(): ShipmentTypeReaderInterface
    {
        return new ShipmentTypeReader(
            $this->getConfig(),
            $this->getShipmentFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Reader\CustomerReaderInterface
     */
    public function createCustomerReader(): CustomerReaderInterface
    {
        return new CustomerReader($this->getCustomerFacade());
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeServicePointsRestApi\Dependency\Facade\ShipmentTypeServicePointsRestApiToShipmentFacadeInterface
     */
    public function getShipmentFacade(): ShipmentTypeServicePointsRestApiToShipmentFacadeInterface
    {
        return $this->getProvidedDependency(ShipmentTypeServicePointsRestApiDependencyProvider::FACADE_SHIPMENT);
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeServicePointsRestApi\Dependency\Facade\ShipmentTypeServicePointsRestApiToCustomerFacadeInterface
     */
    public function getCustomerFacade(): ShipmentTypeServicePointsRestApiToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(ShipmentTypeServicePointsRestApiDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *             Use {@link \Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\ShipmentTypeServicePointsRestApiBusinessFactory::createMultiShipmentQuoteMapper()} instead.
     *
     * @return \Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\StrategyResolver\QuoteMapperStrategyResolverInterface
     */
    public function createQuoteMapperStrategyResolver(): QuoteMapperStrategyResolverInterface
    {
        $strategyContainer = [];
        $strategyContainer[QuoteMapperStrategyResolver::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createSingleShipmentQuoteMapper();
        };
        $strategyContainer[QuoteMapperStrategyResolver::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createMultiShipmentQuoteMapper();
        };

        return new QuoteMapperStrategyResolver($strategyContainer);
    }
}
