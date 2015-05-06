<?php
namespace SprykerFeature\Zed\Sales\Communication\Plugin\Oms\Command;

use SprykerFeature\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\AbstractCommand;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;

class CreateInvoice extends AbstractCommand implements
    CommandByOrderInterface
{

    /**
     * @param array $orderItems
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $orderEntity
     * @param ReadOnlyArrayObject $data
     * @return array|void
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $invoiceEntity = $this->facadeInvoice->createInvoice($orderEntity);
        $invoiceDocument = $this->facadeInvoice->createInvoiceDocument(
            $invoiceEntity,
            $this->facadeInvoice->getCollectedInvoiceData(
                $orderEntity,
                $invoiceEntity,
                new \ArrayIterator($orderItems)
            )
        );

        $this->addNote(
            'Invoice (' . $invoiceEntity->getInvoiceNumber() . ') created. Document ID: '
            . $invoiceDocument->getPrimaryKey(),
            $orderEntity,
            true
        );
    }
}
