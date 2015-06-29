<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter;

use SprykerFeature\Zed\Sales\Business\Model\OrderExporter;

/**
 * Class OrderAddressElement
 * @package SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter
 */
class OrderAddressElement extends AbstractElementExporter
{
    const ELEMENT_EXPORTER_ORDER_ADDRESS = 'order-address-element-exporter';

    /**
     * @return string
     */
    public function getName()
    {
        return self::ELEMENT_EXPORTER_ORDER_ADDRESS;
    }

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order
     * @return OrderExporter|\SprykerFeature_Zed_Library_Propel_BaseObject
     */
    public function getOrderElement(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order)
    {
        return $order->getShippingAddress();
    }
}
