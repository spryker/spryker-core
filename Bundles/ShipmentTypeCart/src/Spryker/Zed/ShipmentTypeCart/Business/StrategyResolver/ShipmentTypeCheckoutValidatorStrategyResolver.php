<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeCart\Business\StrategyResolver;

use ArrayObject;
use Closure;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException;
use Spryker\Zed\ShipmentTypeCart\Business\Validator\ShipmentTypeCheckoutValidatorInterface;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
class ShipmentTypeCheckoutValidatorStrategyResolver implements ShipmentTypeCheckoutValidatorStrategyResolverInterface
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\ShipmentTypeCart\Business\Validator\ShipmentTypeCheckoutValidatorInterface
     */
    public function resolve(QuoteTransfer $quoteTransfer): ShipmentTypeCheckoutValidatorInterface
    {
        $itemTransfers = $quoteTransfer->getItems();
        if ($itemTransfers->count() === 0 || $this->hasItemWithEmptyShipment($itemTransfers)) {
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

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return bool
     */
    protected function hasItemWithEmptyShipment(ArrayObject $itemTransfers): bool
    {
        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getShipment() === null) {
                return true;
            }
        }

        return false;
    }
}
