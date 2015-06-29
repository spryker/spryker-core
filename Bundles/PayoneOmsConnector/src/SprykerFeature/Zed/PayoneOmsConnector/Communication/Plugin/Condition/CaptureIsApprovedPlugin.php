<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayoneOmsConnector\Communication\Plugin\Condition;

use Generated\Shared\Transfer\PayonePaymentTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;
use SprykerFeature\Zed\Payone\Business\PayoneDependencyContainer;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;

/**
 * @method PayoneDependencyContainer getDependencyContainer()
 */
class CaptureIsApprovedPlugin extends AbstractPlugin implements ConditionInterface
{

    /**
     * @var array
     */
    protected static $resultCache = [];


    /**
     * @param SpySalesOrderItem $orderItem
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        //@todo
    }

}
