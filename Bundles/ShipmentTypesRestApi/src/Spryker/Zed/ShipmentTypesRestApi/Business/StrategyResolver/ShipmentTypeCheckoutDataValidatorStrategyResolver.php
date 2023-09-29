<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypesRestApi\Business\StrategyResolver;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Spryker\Zed\ShipmentTypesRestApi\Business\Validator\ShipmentTypeCheckoutDataValidatorInterface;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
class ShipmentTypeCheckoutDataValidatorStrategyResolver implements ShipmentTypeCheckoutDataValidatorStrategyResolverInterface
{
    /**
     * @var string
     */
    public const STRATEGY_MULTI_SHIPMENT = 'STRATEGY_MULTI_SHIPMENT';

    /**
     * @var string
     */
    public const STRATEGY_SINGLE_SHIPMENT = 'STRATEGY_SINGLE_SHIPMENT';

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
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Spryker\Zed\ShipmentTypesRestApi\Business\Validator\ShipmentTypeCheckoutDataValidatorInterface
     */
    public function resolve(CheckoutDataTransfer $checkoutDataTransfer): ShipmentTypeCheckoutDataValidatorInterface
    {
        if ($checkoutDataTransfer->getShipment()) {
            return call_user_func($this->strategyContainer[static::STRATEGY_SINGLE_SHIPMENT]);
        }

        return call_user_func($this->strategyContainer[static::STRATEGY_MULTI_SHIPMENT]);
    }
}
