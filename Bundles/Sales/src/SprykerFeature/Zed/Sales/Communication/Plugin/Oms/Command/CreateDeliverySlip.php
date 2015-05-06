<?php

namespace SprykerFeature\Zed\Sales\Communication\Plugin\Oms\Command;

use SprykerFeature\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\AbstractCommand;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;

class CreateDeliverySlip extends AbstractCommand implements
    CommandByOrderInterface
{

    /**
     * @param array $orderItems
     * @param SpySalesOrder $orderEntity
     * @param ReadOnlyArrayObject $data
     * @throws \ErrorException
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $documentTypeEntity = \SprykerFeature\Zed\Document\Persistence\Propel\PacDocumentTypeQuery::create()
            ->findOneByType(Document::DOCUMENT_TYPE_TYPE_DELIVERY_SLIP);

        if (! $documentTypeEntity) {
            throw new \ErrorException(
                'Could not load document type (' . Document::DOCUMENT_TYPE_TYPE_DELIVERY_SLIP .')'
            );
        }

        $documentConfigurationEntity = \SprykerFeature\Zed\Document\Persistence\Propel\PacDocumentConfigurationQuery::create()
            ->findOneByFkDocumentType($documentTypeEntity->getPrimaryKey());

        $invoiceEntity = \SprykerFeature\Zed\Invoice\Persistence\Propel\PacInvoiceQuery::create()
            ->filterByFkSalesOrder($orderEntity->getPrimaryKey())
            ->findOneByType(\SprykerFeature\Zed\Invoice\Persistence\Propel\Map\PacInvoiceTableMap::COL_TYPE_INVOICE);

        $data = $this->facadeInvoice->getCollectedInvoiceData(
            $orderEntity,
            $invoiceEntity,
            new \ArrayIterator($context->getOrderItems())
        );

        $documentEntity = $this->facadeDocument->createDocument(
            $documentConfigurationEntity,
            $data,
            $orderEntity->getPrimaryKey()
        );

        $this->addNote(
            'Delivery Slip Document created ID:' . $documentEntity->getPrimaryKey(),
            $orderEntity,
            true
        );
    }
}
