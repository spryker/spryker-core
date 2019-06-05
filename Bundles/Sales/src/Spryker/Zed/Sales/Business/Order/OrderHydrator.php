<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Order;

use ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Spryker\Zed\Sales\Business\Exception\InvalidSalesOrderException;
use Spryker\Zed\Sales\Business\Model\Order\OrderHydrator as OrderHydratorWithoutMultiShipping;
use Spryker\Zed\Sales\Business\OrderItem\SalesOrderItemGrouperInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Spryker\Zed\Sales\Dependency\Service\SalesToUtilPriceServiceInterface;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class OrderHydrator extends OrderHydratorWithoutMultiShipping
{
    /**
     * @var \Spryker\Zed\Sales\Business\OrderItem\SalesOrderItemGrouperInterface
     */
    protected $orderItemGrouper;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface $omsFacade
     * @param \Spryker\Zed\Sales\Dependency\Service\SalesToUtilPriceServiceInterface $utilPriceService
     * @param \Spryker\Zed\Sales\Business\OrderItem\SalesOrderItemGrouperInterface $orderItemGrouper
     * @param \Spryker\Zed\Sales\Dependency\Plugin\HydrateOrderPluginInterface[] $hydrateOrderPlugins
     */
    public function __construct(
        SalesQueryContainerInterface $queryContainer,
        SalesToOmsInterface $omsFacade,
        SalesToUtilPriceServiceInterface $utilPriceService,
        SalesOrderItemGrouperInterface $orderItemGrouper,
        array $hydrateOrderPlugins = []
    ) {
        parent::__construct($queryContainer, $omsFacade, $utilPriceService, $hydrateOrderPlugins);

        $this->orderItemGrouper = $orderItemGrouper;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \Spryker\Zed\Sales\Business\Exception\InvalidSalesOrderException
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function getOrderEntity(OrderTransfer $orderTransfer)
    {
        $orderTransfer->requireIdSalesOrder()
            ->requireFkCustomer();

        $orderEntity = $this->queryContainer
            ->querySalesOrderDetailsWithoutShippingAddress($orderTransfer->getIdSalesOrder())
            ->filterByFkCustomer($orderTransfer->getFkCustomer())
            ->findOne();

        if ($orderEntity === null) {
            throw new InvalidSalesOrderException(sprintf(
                'Order could not be found for ID %s and customer reference %s',
                $orderTransfer->getIdSalesOrder(),
                $orderTransfer->getCustomerReference()
            ));
        }

        return $orderEntity;
    }

    /**
     * @param int $idSalesOrder
     *
     * @throws \Spryker\Zed\Sales\Business\Exception\InvalidSalesOrderException
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateOrderTransferFromPersistenceByIdSalesOrder($idSalesOrder)
    {
        $orderEntity = $this->queryContainer
            ->querySalesOrderDetailsWithoutShippingAddress($idSalesOrder)
            ->findOne();

        if ($orderEntity === null) {
            throw new InvalidSalesOrderException(
                sprintf(
                    'Order could not be found for ID %s',
                    $idSalesOrder
                )
            );
        }

        return $this->hydrateOrderTransferFromPersistenceBySalesOrder($orderEntity);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function hydrateOrderItemsToOrderTransfer(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer)
    {
        parent::hydrateOrderItemsToOrderTransfer($orderEntity, $orderTransfer);

        $this->setUniqueOrderItems($orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function setUniqueOrderItems(OrderTransfer $orderTransfer): OrderTransfer
    {
        $uniqueOrderItemCollectionTransfer = $this->orderItemGrouper->getUniqueOrderItems($orderTransfer->getItems());

        $orderItemsWithNumericIndexes = new ArrayObject();
        foreach ($uniqueOrderItemCollectionTransfer->getItems() as $itemTransfer) {
            $orderItemsWithNumericIndexes[] = $itemTransfer;
        }

        return $orderTransfer->setItems($orderItemsWithNumericIndexes);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function hydrateBillingAddressToOrderTransfer(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer): void
    {
        $orderTransfer->setBillingAddress(
            $this->createAddressTransfer($orderEntity->getBillingAddress())
        );
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function hydrateShippingAddressToOrderTransfer(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer): void
    {
        $shippingAddress = $orderEntity->getShippingAddress();

        if ($shippingAddress === null) {
            return;
        }

        $orderTransfer->setShippingAddress($this->createAddressTransfer($shippingAddress));
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderAddress $salesOrderAddressEntity
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function createAddressTransfer(SpySalesOrderAddress $salesOrderAddressEntity): AddressTransfer
    {
        $countryEntity = $salesOrderAddressEntity->getCountry();

        $addressTransfer = new AddressTransfer();
        $addressTransfer->fromArray($salesOrderAddressEntity->toArray(), true);
        $addressTransfer->setIso2Code($countryEntity->getIso2Code());

        $countryTransfer = (new CountryTransfer())->fromArray($countryEntity->toArray(), true);
        $addressTransfer->setCountry($countryTransfer);

        return $addressTransfer;
    }

    /**
     * @param string $shipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected function createShipmentMethod(string $shipmentMethod): ShipmentMethodTransfer
    {
        return (new ShipmentMethodTransfer())
            ->setName($shipmentMethod);
    }

    /**
     * @param string $carrierName
     *
     * @return \Generated\Shared\Transfer\ShipmentCarrierTransfer
     */
    protected function createShipmentCarrier(string $carrierName): ShipmentCarrierTransfer
    {
        return (new ShipmentCarrierTransfer())
            ->setName($carrierName);
    }
}
