<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Communication\Plugin\Oms\Command;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \Spryker\Zed\TaxApp\Business\TaxAppFacadeInterface getFacade()
 * @method \Spryker\Zed\TaxApp\Communication\TaxAppCommunicationFactory getFactory()
 * @method \Spryker\Zed\TaxApp\TaxAppConfig getConfig()
 */
class SubmitPaymentTaxInvoicePlugin extends AbstractPlugin implements CommandByOrderInterface
{
    /**
     * {@inheritDoc}
     * - Sends SubmitPaymentTaxInvoice message to the message broker `tax-commands` channel.
     *
     * @api
     *
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data): array
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer->setIdSalesOrder($orderEntity->getIdSalesOrder());
        $this->getFacade()->sendSubmitPaymentTaxInvoiceMessage($orderTransfer);

        return [];
    }
}
