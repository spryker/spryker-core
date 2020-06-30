<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SalesInvoice\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\OrderInvoiceBuilder;
use Generated\Shared\Transfer\OrderInvoiceTransfer;
use Orm\Zed\SalesInvoice\Persistence\SpySalesOrderInvoice;
use SprykerTest\Zed\SalesInvoice\SalesInvoiceConfigMock;

class SalesInvoiceHelper extends Module
{
    /**
     * @param int $idSalesOrder
     * @param array $seeds
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceTransfer
     */
    public function haveOrderInvoice(int $idSalesOrder, array $seeds = []): OrderInvoiceTransfer
    {
        $orderInvoiceTransfer = (new OrderInvoiceBuilder())
            ->seed($seeds)
            ->build();

        $orderInvoiceEntity = (new SpySalesOrderInvoice())
            ->setFkSalesOrder($idSalesOrder)
            ->setTemplatePath((new SalesInvoiceConfigMock())->getOrderInvoiceTemplatePath());

        $orderInvoiceEntity->fromArray($orderInvoiceTransfer->toArray());

        $orderInvoiceEntity->setFkSalesOrder($idSalesOrder)
            ->setTemplatePath((new SalesInvoiceConfigMock())->getOrderInvoiceTemplatePath());

        $orderInvoiceEntity->save();

        return (new OrderInvoiceTransfer())
            ->fromArray($orderInvoiceEntity->toArray(), true)
            ->setIdSalesOrder($orderInvoiceEntity->getIdSalesOrderInvoice());
    }
}
