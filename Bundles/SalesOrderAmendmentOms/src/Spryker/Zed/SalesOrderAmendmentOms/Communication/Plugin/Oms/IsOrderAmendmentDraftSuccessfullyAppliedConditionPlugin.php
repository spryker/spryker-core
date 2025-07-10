<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Communication\Plugin\Oms;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;

/**
 * @method \Spryker\Zed\SalesOrderAmendmentOms\Business\SalesOrderAmendmentOmsFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesOrderAmendmentOms\Business\SalesOrderAmendmentOmsBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\SalesOrderAmendmentOms\Communication\SalesOrderAmendmentOmsCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig getConfig()
 */
class IsOrderAmendmentDraftSuccessfullyAppliedConditionPlugin extends AbstractPlugin implements ConditionInterface
{
    /**
     * {@inheritDoc}
     * - Retrieves the order reference from the provided `SpySalesOrderItem`.
     * - Finds the corresponding sales order amendment quote using the order reference.
     * - Returns `true` if the sales order amendment quote is not found.
     * - Returns `true` if the sales order amendment quote has no errors, indicating that the order amendment draft was successfully applied.
     * - Returns `false` if the sales order amendment quote has errors, indicating that the order amendment draft was not successfully applied.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem): bool
    {
        $orderReference = $orderItem->getOrder()->getOrderReference();

        return $this->getBusinessFactory()
            ->createConditionChecker()
            ->isOrderAmendmentDraftSuccessfullyApplied($orderReference);
    }
}
