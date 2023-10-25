<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\StrategyResolver;

use Closure;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Expander\ServicePointAddressExpanderInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\ShipmentTypeServicePointValidatorInterface;
use Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
class ShipmentTypeServicePointStrategyResolver implements ShipmentTypeServicePointStrategyResolverInterface
{
    /**
     * @var string
     */
    public const STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT = 'STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT';

    /**
     * @var string
     */
    public const STRATEGY_KEY_WITH_MULTI_SHIPMENT = 'STRATEGY_KEY_WITH_MULTI_SHIPMENT';

    /**
     * @var array<string, \Closure>
     */
    protected array $strategyContainer;

    /**
     * @param array<string, \Closure> $strategyContainer
     */
    public function __construct(array $strategyContainer)
    {
        $this->strategyContainer = $strategyContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Expander\ServicePointAddressExpanderInterface
     */
    public function resolveAddressExpander(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): ServicePointAddressExpanderInterface {
        if ($restCheckoutRequestAttributesTransfer->getShipment() !== null) {
            $this->assertRequiredStrategyContainerItems(static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT);

            return call_user_func($this->strategyContainer[static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT]);
        }

        $this->assertRequiredStrategyContainerItems(static::STRATEGY_KEY_WITH_MULTI_SHIPMENT);

        return call_user_func($this->strategyContainer[static::STRATEGY_KEY_WITH_MULTI_SHIPMENT]);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Validator\ShipmentTypeServicePointValidatorInterface
     */
    public function resolveValidator(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): ShipmentTypeServicePointValidatorInterface {
        if ($restCheckoutRequestAttributesTransfer->getShipment() !== null) {
            $this->assertRequiredStrategyContainerItems(static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT);

            return call_user_func($this->strategyContainer[static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT]);
        }

        $this->assertRequiredStrategyContainerItems(static::STRATEGY_KEY_WITH_MULTI_SHIPMENT);

        return call_user_func($this->strategyContainer[static::STRATEGY_KEY_WITH_MULTI_SHIPMENT]);
    }

    /**
     * @param string $strategyKey
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return void
     */
    protected function assertRequiredStrategyContainerItems(string $strategyKey): void
    {
        if (
            !isset($this->strategyContainer[$strategyKey])
            || !($this->strategyContainer[$strategyKey] instanceof Closure)
        ) {
            throw new ContainerKeyNotFoundException($this, $strategyKey);
        }
    }
}
