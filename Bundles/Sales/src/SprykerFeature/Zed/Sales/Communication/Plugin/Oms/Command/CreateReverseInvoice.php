<?php

namespace SprykerFeature\Zed\Sales\Communication\Plugin\Oms\Command;

use SprykerFeature\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\AbstractCommand;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;

class CreateReverseInvoice extends AbstractCommand implements
    CommandByOrderInterface
{

    /**
     * @param array $orderItems
     * @param SpySalesOrder $orderEntity
     * @param ReadOnlyArrayObject $data
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $invoiceEntity = $this->facadeInvoice->createInvoice(
            $orderEntity,
            \SprykerFeature\Zed\Invoice\Persistence\Propel\Map\PacInvoiceTableMap::COL_TYPE_REVERSE_INVOICE
        );

        $invoiceDocument = $this->facadeInvoice->createInvoiceDocument(
            $invoiceEntity,
            $this->facadeInvoice->getCollectedInvoiceData($orderEntity, $invoiceEntity, $context->getOrderItems())
        );

        $this->addNote(
            'Reverse Invoice (' . $invoiceEntity->getInvoiceNumber() . ') created. Document ID: '
            . $invoiceDocument->getPrimaryKey(),
            $orderEntity,
            true
        );
    }
}
