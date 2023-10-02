<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ClickAndCollectExampleConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Shared\ShipmentType\ShipmentTypeConfig::SHIPMENT_TYPE_PICKUP
     *
     * @var string
     */
    protected const SHIPMENT_TYPE_PICKUP = 'pickup';

    /**
     * @uses \Spryker\Shared\ShipmentType\ShipmentTypeConfig::SHIPMENT_TYPE_DELIVERY
     *
     * @var string
     */
    protected const SHIPMENT_TYPE_DELIVERY = 'delivery';

    /**
     * @api
     *
     * @return string
     */
    public function getPickupShipmentTypeKey(): string
    {
        return static::SHIPMENT_TYPE_PICKUP;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDeliveryShipmentTypeKey(): string
    {
        return static::SHIPMENT_TYPE_DELIVERY;
    }
}
