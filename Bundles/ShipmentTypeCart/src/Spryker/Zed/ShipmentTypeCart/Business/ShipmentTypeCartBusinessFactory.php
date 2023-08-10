<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeCart\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ShipmentTypeCart\Business\Expander\ShipmentTypeExpander;
use Spryker\Zed\ShipmentTypeCart\Business\Expander\ShipmentTypeExpanderInterface;
use Spryker\Zed\ShipmentTypeCart\Business\Reader\ShipmentTypeReader;
use Spryker\Zed\ShipmentTypeCart\Business\Reader\ShipmentTypeReaderInterface;
use Spryker\Zed\ShipmentTypeCart\Business\StrategyResolver\ShipmentTypeCheckoutValidatorStrategyResolver;
use Spryker\Zed\ShipmentTypeCart\Business\StrategyResolver\ShipmentTypeCheckoutValidatorStrategyResolverInterface;
use Spryker\Zed\ShipmentTypeCart\Business\Validator\ErrorCreator\SalesShipmentTypeValidationErrorCreator;
use Spryker\Zed\ShipmentTypeCart\Business\Validator\ErrorCreator\SalesShipmentTypeValidationErrorCreatorInterface;
use Spryker\Zed\ShipmentTypeCart\Business\Validator\MultiShipmentShipmentTypeCheckoutValidator;
use Spryker\Zed\ShipmentTypeCart\Business\Validator\Rule\ShipmentTypeAvailableCheckoutValidationRule;
use Spryker\Zed\ShipmentTypeCart\Business\Validator\Rule\ShipmentTypeCheckoutValidationRuleInterface;
use Spryker\Zed\ShipmentTypeCart\Business\Validator\Rule\ShipmentTypesHaveRelationWithShipmentMethodsCheckoutValidationRule;
use Spryker\Zed\ShipmentTypeCart\Business\Validator\ShipmentTypeCheckoutValidatorInterface;
use Spryker\Zed\ShipmentTypeCart\Business\Validator\SingleShipmentShipmentTypeCheckoutValidator;
use Spryker\Zed\ShipmentTypeCart\Dependency\Facade\ShipmentTypeCartToShipmentTypeFacadeInterface;
use Spryker\Zed\ShipmentTypeCart\ShipmentTypeCartDependencyProvider;

/**
 * @method \Spryker\Zed\ShipmentTypeCart\ShipmentTypeCartConfig getConfig()
 */
class ShipmentTypeCartBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ShipmentTypeCart\Business\Expander\ShipmentTypeExpanderInterface
     */
    public function createShipmentTypeExpander(): ShipmentTypeExpanderInterface
    {
        return new ShipmentTypeExpander();
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeCart\Business\Validator\ShipmentTypeCheckoutValidatorInterface
     */
    public function createMultiShipmentShipmentTypeCheckoutValidator(): ShipmentTypeCheckoutValidatorInterface
    {
        return new MultiShipmentShipmentTypeCheckoutValidator(
            [
                $this->createShipmentTypesHaveRelationWithShipmentMethodsCheckoutValidationRule(),
                $this->createShipmentTypeAvailableCheckoutValidationRule(),
            ],
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeCart\Business\Validator\Rule\ShipmentTypeCheckoutValidationRuleInterface
     */
    public function createShipmentTypesHaveRelationWithShipmentMethodsCheckoutValidationRule(): ShipmentTypeCheckoutValidationRuleInterface
    {
        return new ShipmentTypesHaveRelationWithShipmentMethodsCheckoutValidationRule(
            $this->createSalesShipmentTypeValidationErrorCreator(),
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeCart\Business\Validator\Rule\ShipmentTypeCheckoutValidationRuleInterface
     */
    public function createShipmentTypeAvailableCheckoutValidationRule(): ShipmentTypeCheckoutValidationRuleInterface
    {
        return new ShipmentTypeAvailableCheckoutValidationRule(
            $this->createShipmentTypeReader(),
            $this->createSalesShipmentTypeValidationErrorCreator(),
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeCart\Business\Validator\ShipmentTypeCheckoutValidatorInterface
     */
    public function createSingleShipmentShipmentTypeCheckoutValidator(): ShipmentTypeCheckoutValidatorInterface
    {
        return new SingleShipmentShipmentTypeCheckoutValidator(
            $this->createShipmentTypeReader(),
            $this->createSalesShipmentTypeValidationErrorCreator(),
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeCart\Business\Reader\ShipmentTypeReaderInterface
     */
    public function createShipmentTypeReader(): ShipmentTypeReaderInterface
    {
        return new ShipmentTypeReader($this->getShipmentTypeFacade());
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeCart\Business\Validator\ErrorCreator\SalesShipmentTypeValidationErrorCreatorInterface
     */
    public function createSalesShipmentTypeValidationErrorCreator(): SalesShipmentTypeValidationErrorCreatorInterface
    {
        return new SalesShipmentTypeValidationErrorCreator();
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeCart\Dependency\Facade\ShipmentTypeCartToShipmentTypeFacadeInterface
     */
    public function getShipmentTypeFacade(): ShipmentTypeCartToShipmentTypeFacadeInterface
    {
        return $this->getProvidedDependency(ShipmentTypeCartDependencyProvider::FACADE_SHIPMENT_TYPE);
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *             Use {@link \Spryker\Zed\ShipmentTypeCart\Business\ShipmentTypeCartBusinessFactory::createMultiShipmentShipmentTypeCheckoutValidator()} instead.
     *
     * @return \Spryker\Zed\ShipmentTypeCart\Business\StrategyResolver\ShipmentTypeCheckoutValidatorStrategyResolverInterface
     */
    public function createShipmentTypeCheckoutValidatorStrategyResolver(): ShipmentTypeCheckoutValidatorStrategyResolverInterface
    {
        $strategyContainer = [];

        $strategyContainer[ShipmentTypeCheckoutValidatorStrategyResolver::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createSingleShipmentShipmentTypeCheckoutValidator();
        };

        $strategyContainer[ShipmentTypeCheckoutValidatorStrategyResolver::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createMultiShipmentShipmentTypeCheckoutValidator();
        };

        return new ShipmentTypeCheckoutValidatorStrategyResolver($strategyContainer);
    }
}
