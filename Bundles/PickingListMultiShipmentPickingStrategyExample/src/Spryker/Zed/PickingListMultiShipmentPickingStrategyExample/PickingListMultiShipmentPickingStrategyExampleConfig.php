<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PickingListMultiShipmentPickingStrategyExample;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class PickingListMultiShipmentPickingStrategyExampleConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const NAME_MULTI_SHIPMENT_PICKING_STRATEGY_EXAMPLE = 'multi-shipment';

    /**
     * Specification:
     * - Returns strategy name for picking list generation.
     *
     * @api
     *
     * @return string
     */
    public function getPickingListStrategy(): string
    {
        return static::NAME_MULTI_SHIPMENT_PICKING_STRATEGY_EXAMPLE;
    }
}
