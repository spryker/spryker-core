<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrdersRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer;
use Generated\Shared\Transfer\RestOrderItemsAttributesTransfer;
use Generated\Shared\Transfer\RestOrdersAttributesTransfer;

class OrderMapper implements OrderMapperInterface
{
    /**
     * @var \Spryker\Glue\OrdersRestApiExtension\Dependency\Plugin\RestOrderItemsAttributesMapperPluginInterface[]
     */
    protected $restOrderItemsAttributesMapperPlugins;

    /**
     * @var \Spryker\Glue\OrdersRestApi\Processor\Mapper\OrderShipmentMapperInterface
     */
    protected $orderShipmentMapper;

    /**
     * @var \Spryker\Glue\OrdersRestApiExtension\Dependency\Plugin\RestOrderDetailsAttributesMapperPluginInterface[]
     */
    protected $restOrderDetailsAttributesMapperPlugins;

    /**
     * @param \Spryker\Glue\OrdersRestApi\Processor\Mapper\OrderShipmentMapperInterface $orderShipmentMapper
     * @param \Spryker\Glue\OrdersRestApiExtension\Dependency\Plugin\RestOrderItemsAttributesMapperPluginInterface[] $restOrderItemsAttributesMapperPlugins
     * @param \Spryker\Glue\OrdersRestApiExtension\Dependency\Plugin\RestOrderDetailsAttributesMapperPluginInterface[] $restOrderDetailsAttributesMapperPlugins
     */
    public function __construct(
        OrderShipmentMapperInterface $orderShipmentMapper,
        array $restOrderItemsAttributesMapperPlugins,
        array $restOrderDetailsAttributesMapperPlugins
    ) {
        $this->orderShipmentMapper = $orderShipmentMapper;
        $this->restOrderItemsAttributesMapperPlugins = $restOrderItemsAttributesMapperPlugins;
        $this->restOrderDetailsAttributesMapperPlugins = $restOrderDetailsAttributesMapperPlugins;
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

        $restOrderDetailsAttributesTransfer->setShipments(
            $this->orderShipmentMapper->mapOrderTransferToRestOrderShipmentTransfers($orderTransfer, new ArrayObject())
        );

        $restOrderItemsAttributesTransfers = [];
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $restOrderItemsAttributesTransfers[] = $this->mapItemTransferToRestOrderItemsAttributesTransfer(
                $itemTransfer,
                new RestOrderItemsAttributesTransfer()
            );
        }

        $restOrderDetailsAttributesTransfer->setItems(new ArrayObject($restOrderItemsAttributesTransfers));

        return $this->executeRestOrderDetailsAttributesMapperPlugins($orderTransfer, $restOrderDetailsAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\RestOrderItemsAttributesTransfer $restOrderItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderItemsAttributesTransfer
     */
    public function mapItemTransferToRestOrderItemsAttributesTransfer(
        ItemTransfer $itemTransfer,
        RestOrderItemsAttributesTransfer $restOrderItemsAttributesTransfer
    ): RestOrderItemsAttributesTransfer {
        $restOrderItemsAttributesTransfer = $restOrderItemsAttributesTransfer->fromArray($itemTransfer->toArray(), true);

        foreach ($this->restOrderItemsAttributesMapperPlugins as $restOrderItemsAttributesMapperPlugin) {
            $restOrderItemsAttributesTransfer = $restOrderItemsAttributesMapperPlugin->mapItemTransferToRestOrderItemsAttributesTransfer(
                $itemTransfer,
                $restOrderItemsAttributesTransfer
            );
        }

        return $restOrderItemsAttributesTransfer;
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
        if (!$orderTransfer->getShippingAddress()) {
            return $restOrderDetailsAttributesTransfer;
        }
        $countryTransfer = $this->findItemLevelShippingAddressCountry($orderTransfer);
        $countryName = $countryTransfer ? $countryTransfer->getName() : null;
        $countryIso2Code = $countryTransfer ? $countryTransfer->getIso2Code() : null;

        $restOrderDetailsAttributesTransfer->getShippingAddress()
            ->setCountry($countryName)
            ->setIso2Code($countryIso2Code);

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

        /** @var \Generated\Shared\Transfer\ItemTransfer $firstItemTransfer */
        $firstItemTransfer = $orderTransfer->getItems()->getIterator()->current();
        if (
            $firstItemTransfer->getShipment() === null
            || $firstItemTransfer->getShipment()->getShippingAddress() === null
        ) {
            return null;
        }

        return $firstItemTransfer->getShipment()
            ->getShippingAddress()
            ->getCountry();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer $restOrderDetailsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderDetailsAttributesTransfer
     */
    protected function executeRestOrderDetailsAttributesMapperPlugins(
        OrderTransfer $orderTransfer,
        RestOrderDetailsAttributesTransfer $restOrderDetailsAttributesTransfer
    ): RestOrderDetailsAttributesTransfer {
        foreach ($this->restOrderDetailsAttributesMapperPlugins as $restOrderDetailsAttributesMapperPlugin) {
            $restOrderDetailsAttributesTransfer = $restOrderDetailsAttributesMapperPlugin
                ->mapOrderTransferToRestOrderDetailsAttributesTransfer($orderTransfer, $restOrderDetailsAttributesTransfer);
        }

        return $restOrderDetailsAttributesTransfer;
    }
}
