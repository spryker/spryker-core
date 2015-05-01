<?php

namespace SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter;

use SprykerFeature\Zed\Sales\Business\Model\OrderExporter;

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
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order
     * @return OrderExporter|\SprykerFeature_Zed_Library_Propel_BaseObject
     */
    public function getOrderElement(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order)
    {
        return $order;
    }
}
