<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business\Reclamation;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\SalesReclamation\Persistence\Map\SpySalesReclamationTableMap;
use Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation;

class Creator
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int[] ...$idsOrderItem
     *
     * @return null|\Orm\Zed\SalesReclamation\Persistence\SpySalesReclamation
     */
    public function createReclamation(OrderTransfer $orderTransfer, int ... $idsOrderItem): ?SpySalesReclamation
    {
        if (!$idsOrderItem) {
            return null;
        }

        $salutation = $orderTransfer->getSalutation();

        $customer = sprintf(
            '%s%s %s',
            $salutation ? $salutation . ' ' : '',
            $orderTransfer->getFirstName(),
            $orderTransfer->getLastName()
        );

        $spySaleReclamation = new SpySalesReclamation();
        $spySaleReclamation->setFkSalesOrder($orderTransfer->getIdSalesOrder());
        $spySaleReclamation->setCustomerName($customer);
        $spySaleReclamation->setState(SpySalesReclamationTableMap::COL_STATE_OPEN);

        $spySaleReclamation->save();

        return $spySaleReclamation;
    }
}
