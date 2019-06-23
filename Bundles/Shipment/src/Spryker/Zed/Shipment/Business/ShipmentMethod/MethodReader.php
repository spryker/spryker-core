<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentMethod;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer;
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
     * @param \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodFilterPluginInterface[]|\Spryker\Zed\Shipment\Dependency\Plugin\ShipmentMethodFilterPluginInterface[] $shipmentMethodFilters
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
     * @param int $idShipmentMethod
     *
     * @return bool
     */
    public function hasMethod(int $idShipmentMethod): bool
    {
        return $this->shipmentRepository->hasShipmentMethodByIdShipmentMethod($idShipmentMethod);
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return bool
     */
    public function isShipmentMethodActive(int $idShipmentMethod): bool
    {
        return $this->shipmentRepository->hasActiveShipmentMethodByIdShipmentMethod($idShipmentMethod);
    }

    /**
     * @param int $idMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findShipmentMethodTransferById(int $idMethod): ?ShipmentMethodTransfer
    {
        return $this->shipmentRepository->findShipmentMethodByIdWithPricesAndCarrier($idMethod);
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    public function getShipmentMethodTransfers(): array
    {
        return $this->shipmentRepository->getActiveShipmentMethods();
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

        $storeCurrencyPrice = $this->methodPriceReader
            ->findShipmentGroupShippingPrice($shipmentMethodTransfer, $quoteTransfer);
        if ($storeCurrencyPrice === null) {
            return null;
        }

        return $this->transformShipmentMethod($quoteTransfer, $shipmentMethodTransfer, $storeCurrencyPrice);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer
     */
    public function getAvailableMethodsByShipment(QuoteTransfer $quoteTransfer): ShipmentMethodsCollectionTransfer
    {
        $shipmentGroupCollection = $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());
        $shipmentMethodsCollectionTransfer = $this->getAvailableMethodsCollection($quoteTransfer, $shipmentGroupCollection);
        $shipmentMethodsCollectionTransfer = $this->applyFilters($shipmentMethodsCollectionTransfer, $shipmentGroupCollection, $quoteTransfer);

        return $shipmentMethodsCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer
     */
    protected function getAvailableMethodsCollection(QuoteTransfer $quoteTransfer, iterable $shipmentGroupCollection): ShipmentMethodsCollectionTransfer
    {
        $shipmentMethodsCollection = new ShipmentMethodsCollectionTransfer();
        $activeShipmentMethodTransfers = $this->shipmentRepository->getActiveShipmentMethods();

        foreach ($shipmentGroupCollection as $shipmentGroupTransfer) {
            $shipmentMethodsTransfer = $this->getAvailableMethodsTransfer(
                $activeShipmentMethodTransfers,
                $quoteTransfer,
                $shipmentGroupTransfer
            );

            $shipmentMethodsTransfer->setShipmentHash($shipmentGroupTransfer->getHash());
            $shipmentMethodsCollection->addShipmentMethods($shipmentMethodsTransfer);
        }

        return $shipmentMethodsCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer $shipmentMethodsCollectionTransfer
     * @param iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer
     */
    protected function applyFilters(
        ShipmentMethodsCollectionTransfer $shipmentMethodsCollectionTransfer,
        iterable $shipmentGroupCollection,
        QuoteTransfer $quoteTransfer
    ): ShipmentMethodsCollectionTransfer {
        foreach ($shipmentMethodsCollectionTransfer->getShipmentMethods() as $index => $shipmentMethodsTransfer) {
            if (count($shipmentMethodsTransfer->getMethods()) < 1) {
                continue;
            }

            foreach ($this->shipmentMethodFilters as $shipmentMethodFilter) {
                if ($shipmentMethodFilter instanceof ShipmentMethodFilterPluginInterface) {
                    $shipmentGroupTransfer = $this->getShipmentGroupByHash($shipmentGroupCollection, $shipmentMethodsTransfer->getShipmentHash());

                    $shipmentGroupTransfer->setAvailableShipmentMethods($shipmentMethodsTransfer);
                    $shipmentMethodCollection = $shipmentMethodFilter->filterShipmentMethods($shipmentGroupTransfer, $quoteTransfer);
                    $shipmentMethodsTransfer->setMethods($shipmentMethodCollection);
                } else {
                    /**
                     * @deprecated Exists for Backward Compatibility reasons only.
                     */
                    $shipmentMethodCollection = $shipmentMethodsTransfer->getMethods();
                    $shipmentMethodCollection = $shipmentMethodFilter->filterShipmentMethods($shipmentMethodCollection, $quoteTransfer);
                    $shipmentMethodsTransfer->setMethods($shipmentMethodCollection);
                }

                if (count($shipmentMethodsTransfer->getMethods()) < 1) {
                    break;
                }
            }

            $shipmentMethodsCollectionTransfer->getShipmentMethods()->offsetSet($index, $shipmentMethodsTransfer);
        }

        return $shipmentMethodsCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer[] $activeShipmentMethodTransfers
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer|null $shipmentGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    protected function getAvailableMethodsTransfer(
        array $activeShipmentMethodTransfers,
        QuoteTransfer $quoteTransfer,
        ?ShipmentGroupTransfer $shipmentGroupTransfer = null
    ): ShipmentMethodsTransfer {
        $shipmentMethodsTransfer = new ShipmentMethodsTransfer();
        foreach ($activeShipmentMethodTransfers as $shipmentMethodTransfer) {
            $shipmentMethodTransfer = $this->prepareAvailableShipmentMethod(
                $shipmentMethodTransfer,
                $quoteTransfer,
                $shipmentGroupTransfer
            );

            if ($shipmentMethodTransfer === null) {
                continue;
            }

            $shipmentMethodsTransfer->addMethod($shipmentMethodTransfer);
        }

        return $shipmentMethodsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer|null $shipmentGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    protected function prepareAvailableShipmentMethod(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        QuoteTransfer $quoteTransfer,
        ?ShipmentGroupTransfer $shipmentGroupTransfer = null
    ): ?ShipmentMethodTransfer {
        $isShipmentMethodAvailable = $this->methodAvailabilityChecker
            ->isShipmentMethodAvailableForShipmentGroup(
                $shipmentMethodTransfer,
                $quoteTransfer,
                $shipmentGroupTransfer
            );
        if (!$isShipmentMethodAvailable) {
            return null;
        }

        $storeCurrencyPrice = $this->methodPriceReader
            ->findShipmentGroupShippingPrice($shipmentMethodTransfer, $quoteTransfer, $shipmentGroupTransfer);

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
     * @param iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     * @param string $shipmentHash
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    public function getShipmentGroupByHash(iterable $shipmentGroupCollection, string $shipmentHash): ShipmentGroupTransfer
    {
        foreach ($shipmentGroupCollection as $shipmentGroupTransfer) {
            if ($shipmentGroupTransfer->getHash() === $shipmentHash) {
                return $shipmentGroupTransfer;
            }
        }

        return new ShipmentGroupTransfer();
    }
}
