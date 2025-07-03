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
     * @var bool
     */
    protected const IS_PRODUCT_OFFER_FILTERED_BY_IS_ACTIVE_FOR_ORDER_AMENDMENT = false;

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

    /**
     * Specification:
     *  - Defines if product offers should be filtered by `isActive` for order amendment.
     *
     * @api
     *
     * @return bool
     */
    public function isProductOfferFilteredByIsActiveForOrderAmendment(): bool
    {
        return static::IS_PRODUCT_OFFER_FILTERED_BY_IS_ACTIVE_FOR_ORDER_AMENDMENT;
    }
}
