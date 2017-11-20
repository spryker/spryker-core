<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Communication\Plugin\Oms\Command;

use Generated\Shared\Transfer\TransactionMetaTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \Spryker\Zed\Braintree\Business\BraintreeFacadeInterface getFacade()
 * @method \Spryker\Zed\Braintree\Communication\BraintreeCommunicationFactory getFactory()
 */
class RevertPlugin extends AbstractPlugin implements CommandByOrderInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $transactionMetaTransfer = new TransactionMetaTransfer();
        $transactionMetaTransfer->setIdSalesOrder($orderEntity->getIdSalesOrder());

        $this->getFacade()->revertPayment($transactionMetaTransfer);

        return [];
    }
}
