<?php

namespace SprykerFeature\Zed\PayoneOmsConnector\Communication\Plugin\Condition;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;
use SprykerFeature\Zed\Payone\Business\PayoneDependencyContainer;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;

/**
 * @method PayoneDependencyContainer getDependencyContainer()
 */
class IsAuthorizationSuccess extends AbstractPlugin implements ConditionInterface
{

    public function check(SpySalesOrderItem $orderItem)
    {
        //FIXME Pseudo Code Example

        $this->getDependencyContainer()->createPayoneFacade()->authorize();
    }

}
