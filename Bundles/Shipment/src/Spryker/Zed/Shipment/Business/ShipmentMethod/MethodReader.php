<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentMethod;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupCollectionTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface;
use Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodFilterPluginInterface;

class MethodReader implements MethodReaderInterface
{
    /**
     * @var \Spryker\Service\Shipment\ShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface
     */
    protected $shipmentRepository;

    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodAvailabilityCheckerInterface
     */
    protected $methodAvailabilityChecker;

    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodPriceReaderInterface
     */
    protected $methodPriceReader;

    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodDeliveryTimeReaderInterface
     */
    protected $methodDeliveryTimeReader;

    /**
     * @var array|\Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodFilterPluginInterface[]|\Spryker\Zed\Shipment\Dependency\Plugin\ShipmentMethodFilterPluginInterface[]
     */
    protected $shipmentMethodFilters;

    /**
     * @param \Spryker\Service\Shipment\ShipmentServiceInterface $shipmentService
     * @param \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodFilterPluginInterface[] $shipmentMethodFilters
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface $shipmentRepository
     * @param \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodAvailabilityCheckerInterface $methodAvailabilityChecker
     * @param \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodPriceReaderInterface $methodPriceReader
     * @param \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodDeliveryTimeReaderInterface $methodDeliveryTimeReader
     */
    public function __construct(
        ShipmentServiceInterface $shipmentService,
        array $shipmentMethodFilters,
        ShipmentRepositoryInterface $shipmentRepository,
        MethodAvailabilityCheckerInterface $methodAvailabilityChecker,
        MethodPriceReaderInterface $methodPriceReader,
        MethodDeliveryTimeReaderInterface $methodDeliveryTimeReader
    ) {
        $this->shipmentService = $shipmentService;
        $this->shipmentMethodFilters = $shipmentMethodFilters;
        $this->shipmentRepository = $shipmentRepository;
        $this->methodAvailabilityChecker = $methodAvailabilityChecker;
        $this->methodPriceReader = $methodPriceReader;
        $this->methodDeliveryTimeReader = $methodDeliveryTimeReader;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupCollectionTransfer
     */
    public function getAvailableMethodsByShipmentWithoutMultiShipment(
        QuoteTransfer $quoteTransfer
    ): ShipmentGroupCollectionTransfer {
        $shipmentGroupTransfer = (new ShipmentGroupTransfer())
            ->setAvailableShipmentMethods($this->getAvailableMethods($quoteTransfer));

        $shipmentGroupCollection = (new ArrayObject());
        $shipmentGroupCollection->offsetSet($shipmentGroupTransfer->getHash(), $shipmentGroupTransfer);

        return (new ShipmentGroupCollectionTransfer())->setShipmentGroups($shipmentGroupCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isMultiShipmentQuote(QuoteTransfer $quoteTransfer): bool
    {
        if ($quoteTransfer->getItems()->count() === 0) {
            return false;
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() === null) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupCollectionTransfer
     */
    public function getShipmentGroupWithAvailableMethods(QuoteTransfer $quoteTransfer): ShipmentGroupCollectionTransfer
    {
        $shipmentMethodTransfers = $this->shipmentRepository->getActiveShipmentMethods();
        $quoteShipmentGroupCollection = $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());

        $shipmentGroupCollection = new ArrayObject();
        foreach ($quoteShipmentGroupCollection as $shipmentGroupTransfer) {
            $shipmentMethodsTransfer = $this->getAvailableMethodForShipmentGroup(
                $shipmentGroupTransfer,
                $shipmentMethodTransfers,
                $quoteTransfer
            );

            $shipmentGroupTransfer->setAvailableShipmentMethods($shipmentMethodsTransfer);
            $shipmentGroupCollection[$shipmentGroupTransfer->getHash()] = $shipmentGroupTransfer;
        }

        return (new ShipmentGroupCollectionTransfer())->setShipmentGroups($shipmentGroupCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupCollectionTransfer $shipmentGroupCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupCollectionTransfer
     */
    public function applyFiltersByShipment(
        ShipmentGroupCollectionTransfer $shipmentGroupCollectionTransfer,
        QuoteTransfer $quoteTransfer
    ): ShipmentGroupCollectionTransfer {
        foreach ($shipmentGroupCollectionTransfer->getShipmentGroups() as $shipmentGroupTransfer) {
            $shipmentMethods = $shipmentGroupTransfer->getAvailableShipmentMethods();

            foreach ($this->shipmentMethodFilters as $shipmentMethodFilter) {
                if ($shipmentMethodFilter instanceof ShipmentMethodFilterPluginInterface) {
                    $shipmentMethods = $shipmentMethodFilter->filterShipmentMethods($shipmentGroupTransfer, $quoteTransfer);
                } else {
                    /**
                     * @deprecated Exists for Backward Compatibility reasons only.
                     */
                    $shipmentMethods = $shipmentMethodFilter->filterShipmentMethods($shipmentMethods->getMethods(), $quoteTransfer);
                }
            }

            $shipmentMethodsTransfer = (new ShipmentMethodsTransfer())->setMethods($shipmentMethods);
            $shipmentGroupTransfer->setAvailableShipmentMethods($shipmentMethodsTransfer);
        }

        return $shipmentGroupCollectionTransfer;
    }

    /**
     * @param int $idMethod
     *
     * @return bool
     */
    public function hasMethod(int $idMethod): bool
    {
        return $this->shipmentRepository->hasShipmentMethodByIdShipmentMethod($idMethod);
    }

    /**
     * @param int $idMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findShipmentMethodById(int $idMethod): ?ShipmentMethodTransfer
    {
        return $this->shipmentRepository->findShipmentMethodById($idMethod);
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    public function getActiveShipmentMethods(): array
    {
        return $this->shipmentRepository->getActiveShipmentMethods();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer): ShipmentMethodsTransfer
    {
        $shipmentMethodsTransfer = $this->getAvailableMethodsTransfer($quoteTransfer);
        $shipmentMethodsTransfer = $this->applyFilters($shipmentMethodsTransfer, $quoteTransfer);

        return $shipmentMethodsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    protected function getAvailableMethodsTransfer(QuoteTransfer $quoteTransfer): ShipmentMethodsTransfer
    {
        $shipmentMethodsTransfer = new ShipmentMethodsTransfer();
        $activeShipmentMethodTransfers = $this->shipmentRepository->getActiveShipmentMethods();

        foreach ($activeShipmentMethodTransfers as $activeShipmentMethodTransfer) {
            $shipmentMethodTransfer = $this->prepareAvailableShipmentMethod($activeShipmentMethodTransfer, $quoteTransfer);
            if ($shipmentMethodTransfer === null) {
                continue;
            }

            $shipmentMethodsTransfer->addMethod($shipmentMethodTransfer);
        }

        return $shipmentMethodsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    protected function applyFilters(
        ShipmentMethodsTransfer $shipmentMethodsTransfer,
        QuoteTransfer $quoteTransfer
    ): ShipmentMethodsTransfer {
        $shipmentMethods = $shipmentMethodsTransfer->getMethods();

        foreach ($this->shipmentMethodFilters as $shipmentMethodFilter) {
            $shipmentMethods = $shipmentMethodFilter->filterShipmentMethods($shipmentMethods, $quoteTransfer);
        }

        return $shipmentMethodsTransfer->setMethods($shipmentMethods);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethodTransfers
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    protected function getAvailableMethodForShipmentGroup(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        array $shipmentMethodTransfers,
        QuoteTransfer $quoteTransfer
    ): ShipmentMethodsTransfer {
        $shipmentMethodsTransfer = new ShipmentMethodsTransfer();
        foreach ($shipmentMethodTransfers as $shipmentMethodTransfer) {
            $isShipmentMethodAvailableForShipmentGroup = $this->methodAvailabilityChecker
                ->isShipmentMethodAvailableForShipmentGroup(
                    $shipmentMethodTransfer,
                    $quoteTransfer,
                    $shipmentGroupTransfer
                );
            if (!$isShipmentMethodAvailableForShipmentGroup) {
                continue;
            }

            $storeCurrencyPrice = $this->methodPriceReader
                ->getShipmentGroupShippingPrice($shipmentMethodTransfer, $quoteTransfer, $shipmentGroupTransfer);
            if ($storeCurrencyPrice === null) {
                continue;
            }

            $shipmentMethodTransfer = $this->transformShipmentMethod(
                $quoteTransfer,
                $shipmentMethodTransfer,
                $storeCurrencyPrice,
                $shipmentGroupTransfer
            );
            $shipmentMethodsTransfer->addMethod($shipmentMethodTransfer);
        }

        return $shipmentMethodsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    protected function prepareAvailableShipmentMethod(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        QuoteTransfer $quoteTransfer
    ): ?ShipmentMethodTransfer {
        if (!$this->methodAvailabilityChecker
            ->isShipmentMethodAvailableForShipmentGroup($shipmentMethodTransfer, $quoteTransfer)) {
            return null;
        }

        $storeCurrencyPrice = $this->findStoreCurrencyPriceAmountByShipmentMethodTransfer(
            $shipmentMethodTransfer,
            $quoteTransfer
        );
        if ($storeCurrencyPrice === null) {
            return null;
        }

        return $this->transformShipmentMethod(
            $quoteTransfer,
            $shipmentMethodTransfer,
            $storeCurrencyPrice
        );
    }

    /**
     * @param int $idShipmentMethod
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findAvailableMethodById(int $idShipmentMethod, QuoteTransfer $quoteTransfer): ?ShipmentMethodTransfer
    {
        $shipmentMethodTransfer = $this->shipmentRepository->findShipmentMethodById($idShipmentMethod);
        if ($shipmentMethodTransfer === null) {
            return null;
        }

        $storeCurrencyPrice = $this->findStoreCurrencyPriceAmountByShipmentMethodTransfer($shipmentMethodTransfer, $quoteTransfer);
        if ($storeCurrencyPrice === null) {
            return null;
        }

        return $this->transformShipmentMethod($quoteTransfer, $shipmentMethodTransfer, $storeCurrencyPrice);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param int|null $storeCurrencyPrice
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer|null $shipmentGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected function transformShipmentMethod(
        QuoteTransfer $quoteTransfer,
        ShipmentMethodTransfer $shipmentMethodTransfer,
        ?int $storeCurrencyPrice = null,
        ?ShipmentGroupTransfer $shipmentGroupTransfer = null
    ): ShipmentMethodTransfer {
        $deliveryTimeForShippingGroup = $this->methodDeliveryTimeReader->getDeliveryTimeForShippingGroup(
            $shipmentMethodTransfer,
            $quoteTransfer,
            $shipmentGroupTransfer
        );

        $shipmentMethodTransfer
            ->setStoreCurrencyPrice($storeCurrencyPrice)
            ->setDeliveryTime($deliveryTimeForShippingGroup);

        $currencyTransfer = $quoteTransfer->getCurrency();
        if ($currencyTransfer === null) {
            return $shipmentMethodTransfer;
        }

        $shipmentMethodTransfer->setCurrencyIsoCode($currencyTransfer->getCode());

        return $shipmentMethodTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int|null
     */
    protected function findStoreCurrencyPriceAmountByShipmentMethodTransfer(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        QuoteTransfer $quoteTransfer
    ): ?int {
        return $this->methodPriceReader
            ->getShipmentGroupShippingPrice($shipmentMethodTransfer, $quoteTransfer);
    }
}
