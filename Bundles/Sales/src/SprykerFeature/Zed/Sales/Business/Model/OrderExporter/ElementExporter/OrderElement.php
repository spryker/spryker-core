<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter;

use SprykerFeature\Zed\Sales\Business\Model\OrderExporter;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;

/**
 * Class Order
 * @package SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter
 */
class OrderElement extends AbstractElementExporter
{

    const ELEMENT_EXPORTER_ORDER = 'order-element-exporter';

    /**
     * @return string
     */
    public function getName()
    {
        return self::ELEMENT_EXPORTER_ORDER;
    }

    /**
     * @param SpySalesOrder $order
     *
     * @return SpySalesOrder
     */
    public function getOrderElement(SpySalesOrder $order)
    {
        return $order;
    }
}
