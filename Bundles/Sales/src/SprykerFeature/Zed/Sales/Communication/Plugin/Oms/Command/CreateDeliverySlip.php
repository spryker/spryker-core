<?php

namespace SprykerFeature\Zed\Sales\Communication\Plugin\Oms\Command;

use SprykerFeature\Zed\Document\Business\Model\Document;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\AbstractCommand;
use SprykerFeature\Zed\Oms\Business\Model\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;

class CreateDeliverySlip extends AbstractCommand implements
    CommandByOrderInterface
{

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $orderEntity
     * @param \SprykerFeature_Zed_Sales_Business_Interface_ContextCollection $context
     * @throws \ErrorException
     */
    public function run(array $orderItems, \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
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
