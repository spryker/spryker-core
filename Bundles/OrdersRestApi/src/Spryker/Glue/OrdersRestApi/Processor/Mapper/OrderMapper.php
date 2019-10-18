<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer;
use Generated\Shared\Transfer\RestOrdersAttributesTransfer;

class OrderMapper implements OrderMapperInterface
{
    /**
     * @var \Spryker\Glue\OrdersRestApi\Processor\Mapper\OrderShipmentMapperInterface
     */
    protected $orderShipmentMapper;

    /**
     * @param \Spryker\Glue\OrdersRestApi\Processor\Mapper\OrderShipmentMapperInterface $orderShipmentMapper
     */
    public function __construct(OrderShipmentMapperInterface $orderShipmentMapper)
    {
        $this->orderShipmentMapper = $orderShipmentMapper;
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

        $restOrderDetailsAttributesTransfer = $this->mapOrderShippingAddressTransferToRestOrderDetailsAttributesTransfer($orderTransfer, $restOrderDetailsAttributesTransfer);

        return $restOrderDetailsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer $restOrderDetailsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer
     */
    public function mapOrderShippingAddressTransferToRestOrderDetailsAttributesTransfer(
        OrderTransfer $orderTransfer,
        RestOrderDetailsAttributesTransfer $restOrderDetailsAttributesTransfer
    ): RestOrderDetailsAttributesTransfer {
        $countryTransfer = $this->findItemLevelShippingAddressCountry($orderTransfer);
        $countryName = $countryTransfer ? $countryTransfer->getName() : null;
        $countryIso2Code = $countryTransfer ? $countryTransfer->getIso2Code() : null;

        $restOrderDetailsAttributesTransfer->getShippingAddress()->setCountry($countryName);
        $restOrderDetailsAttributesTransfer->getShippingAddress()->setIso2Code($countryIso2Code);

        $restOrderDetailsAttributesTransfer->setShipments(
            $this->orderShipmentMapper->mapOrderTransferToRestOrderShipmentTransfers($orderTransfer, new ArrayObject())
        );

        return $restOrderDetailsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\CountryTransfer|null
     */
    protected function findItemLevelShippingAddressCountry(OrderTransfer $orderTransfer): ?CountryTransfer
    {
        if ($orderTransfer->getItems()->count() === 0) {
            return null;
        }

        $firstItemTransfer = current($orderTransfer->getItems());
        if ($firstItemTransfer->getShipment() === null
            || $firstItemTransfer->getShipment()->getShippingAddress() === null
        ) {
            return null;
        }

        return $firstItemTransfer->getShipment()
            ->getShippingAddress()
            ->getCountry();
    }
}
