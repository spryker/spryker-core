<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayoneOmsConnector;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;

class PayoneOmsConnectorConfig extends AbstractBundleConfig
{

    /**
     * @param array $orderItems
     * @param SpySalesOrder $orderEntity
     * @param ReadOnlyArrayObject $data
     * @return string
     */
    public function getNarrativeText(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data) {
        return $orderEntity->getOrderReference();
    }

}
