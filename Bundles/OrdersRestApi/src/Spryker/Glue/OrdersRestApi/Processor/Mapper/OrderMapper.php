<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi\Processor\Mapper;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer;
use Generated\Shared\Transfer\RestOrdersAttributesTransfer;

class OrderMapper implements OrderMapperInterface
{
    /**
     * @var \Spryker\Glue\OrdersRestApi\Processor\Mapper\OrderShipmentMapperInterface
     */
    protected $orderResourceShipmentMapper;

    /**
     * @param \Spryker\Glue\OrdersRestApi\Processor\Mapper\OrderShipmentMapperInterface $orderResourceShipmentMapper
     */
    public function __construct(OrderShipmentMapperInterface $orderResourceShipmentMapper)
    {
        $this->orderResourceShipmentMapper = $orderResourceShipmentMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrdersAttributesTransfer
     */
    public function mapOrderTransferToRestOrdersAttributesTransfer(OrderTransfer $orderTransfer): RestOrdersAttributesTransfer
    {
        $orderTransfer->requireTotals();
        $orderTransfer->getTotals()->requireTaxTotal();

        $restOrdersAttributesTransfer = (new RestOrdersAttributesTransfer())->fromArray($orderTransfer->toArray(), true);
        $restOrdersAttributesTransfer->getTotals()->setTaxTotal($orderTransfer->getTotals()->getTaxTotal()->getAmount());

        return $restOrdersAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer
     */
    public function mapOrderTransferToRestOrderDetailsAttributesTransfer(OrderTransfer $orderTransfer): RestOrderDetailsAttributesTransfer
    {
        $orderTransfer->requireTotals();
        $orderTransfer->getTotals()->requireTaxTotal();

        $restOrderDetailsAttributesTransfer = (new RestOrderDetailsAttributesTransfer())->fromArray($orderTransfer->toArray(), true);
        $restOrderDetailsAttributesTransfer->getTotals()->setTaxTotal($orderTransfer->getTotals()->getTaxTotal()->getAmount());

        $restOrderDetailsAttributesTransfer->getBillingAddress()->setCountry($orderTransfer->getBillingAddress()->getCountry()->getName());
        $restOrderDetailsAttributesTransfer->getBillingAddress()->setIso2Code($orderTransfer->getBillingAddress()->getCountry()->getIso2Code());

        $restOrderDetailsAttributesTransfer->getShippingAddress()->setCountry($orderTransfer->getShippingAddress()->getCountry()->getName());
        $restOrderDetailsAttributesTransfer->getShippingAddress()->setIso2Code($orderTransfer->getShippingAddress()->getCountry()->getIso2Code());

        $restOrderDetailsAttributesTransfer->setShipments(
            $this->orderResourceShipmentMapper->mapShipmentMethodTransfersToRestOrderShipmentTransfers($orderTransfer)
        );

        return $restOrderDetailsAttributesTransfer;
    }
}
