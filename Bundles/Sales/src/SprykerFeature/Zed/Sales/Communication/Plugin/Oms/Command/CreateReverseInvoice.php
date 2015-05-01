<?php

namespace SprykerFeature\Zed\Sales\Communication\Plugin\Oms\Command;

use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\AbstractCommand;
use SprykerFeature\Zed\Oms\Business\Model\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;

class CreateReverseInvoice extends AbstractCommand implements
    CommandByOrderInterface
{

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $orderEntity
     * @param \SprykerFeature_Zed_Sales_Business_Interface_ContextCollection $context
     */
    public function run(array $orderItems, \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
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
