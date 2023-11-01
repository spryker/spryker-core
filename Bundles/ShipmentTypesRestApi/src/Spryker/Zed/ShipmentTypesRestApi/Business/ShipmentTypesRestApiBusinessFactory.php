<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypesRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ShipmentTypesRestApi\Business\ErrorCreator\ShipmentTypeCheckoutErrorCreator;
use Spryker\Zed\ShipmentTypesRestApi\Business\ErrorCreator\ShipmentTypeCheckoutErrorCreatorInterface;
use Spryker\Zed\ShipmentTypesRestApi\Business\Expander\QuoteItemExpander;
use Spryker\Zed\ShipmentTypesRestApi\Business\Reader\ShipmentMethodReader;
use Spryker\Zed\ShipmentTypesRestApi\Business\Reader\ShipmentMethodReaderInterface;
use Spryker\Zed\ShipmentTypesRestApi\Business\StrategyResolver\ShipmentTypeCheckoutDataValidatorStrategyResolver;
use Spryker\Zed\ShipmentTypesRestApi\Business\StrategyResolver\ShipmentTypeCheckoutDataValidatorStrategyResolverInterface;
use Spryker\Zed\ShipmentTypesRestApi\Business\Validator\MultiShipmentShipmentTypeCheckoutDataValidator;
use Spryker\Zed\ShipmentTypesRestApi\Business\Validator\ShipmentTypeCheckoutDataValidatorInterface;
use Spryker\Zed\ShipmentTypesRestApi\Business\Validator\SingleShipmentShipmentTypeCheckoutDataValidator;
use Spryker\Zed\ShipmentTypesRestApi\Dependency\Facade\ShipmentTypesRestApiToShipmentFacadeInterface;
use Spryker\Zed\ShipmentTypesRestApi\ShipmentTypesRestApiDependencyProvider;

class ShipmentTypesRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ShipmentTypesRestApi\Business\Expander\QuoteItemExpander
     */
    public function createQuoteItemExpander(): QuoteItemExpander
    {
        return new QuoteItemExpander(
            $this->getShipmentFacade(),
        );
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     * Use {@link \Spryker\Zed\ShipmentTypesRestApi\Business\ShipmentTypesRestApiBusinessFactory::createMultiShipmentShipmentTypeCheckoutDataValidator()} instead.
     *
     * @return \Spryker\Zed\ShipmentTypesRestApi\Business\StrategyResolver\ShipmentTypeCheckoutDataValidatorStrategyResolverInterface
     */
    public function createShipmentTypeCheckoutDataValidatorStrategyResolver(): ShipmentTypeCheckoutDataValidatorStrategyResolverInterface
    {
        $strategyContainer = [];
        $strategyContainer[ShipmentTypeCheckoutDataValidatorStrategyResolver::STRATEGY_SINGLE_SHIPMENT] = function () {
            return $this->createSingleShipmentShipmentTypeCheckoutDataValidator();
        };
        $strategyContainer[ShipmentTypeCheckoutDataValidatorStrategyResolver::STRATEGY_MULTI_SHIPMENT] = function () {
            return $this->createMultiShipmentShipmentTypeCheckoutDataValidator();
        };

        return new ShipmentTypeCheckoutDataValidatorStrategyResolver($strategyContainer);
    }

    /**
     * @return \Spryker\Zed\ShipmentTypesRestApi\Business\Validator\ShipmentTypeCheckoutDataValidatorInterface
     */
    public function createMultiShipmentShipmentTypeCheckoutDataValidator(): ShipmentTypeCheckoutDataValidatorInterface
    {
        return new MultiShipmentShipmentTypeCheckoutDataValidator(
            $this->createShipmentMethodReader(),
            $this->createShipmentTypeCheckoutErrorCreator(),
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentTypesRestApi\Business\Validator\ShipmentTypeCheckoutDataValidatorInterface
     */
    public function createSingleShipmentShipmentTypeCheckoutDataValidator(): ShipmentTypeCheckoutDataValidatorInterface
    {
        return new SingleShipmentShipmentTypeCheckoutDataValidator(
            $this->createShipmentMethodReader(),
            $this->createShipmentTypeCheckoutErrorCreator(),
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentTypesRestApi\Business\Reader\ShipmentMethodReaderInterface
     */
    public function createShipmentMethodReader(): ShipmentMethodReaderInterface
    {
        return new ShipmentMethodReader(
            $this->getShipmentFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentTypesRestApi\Business\ErrorCreator\ShipmentTypeCheckoutErrorCreatorInterface
     */
    public function createShipmentTypeCheckoutErrorCreator(): ShipmentTypeCheckoutErrorCreatorInterface
    {
        return new ShipmentTypeCheckoutErrorCreator();
    }

    /**
     * @return \Spryker\Zed\ShipmentTypesRestApi\Dependency\Facade\ShipmentTypesRestApiToShipmentFacadeInterface
     */
    public function getShipmentFacade(): ShipmentTypesRestApiToShipmentFacadeInterface
    {
        return $this->getProvidedDependency(ShipmentTypesRestApiDependencyProvider::FACADE_SHIPMENT);
    }
}
