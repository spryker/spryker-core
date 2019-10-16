<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentMethod;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface;
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
     * @var array|\Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodFilterPluginInterface[]
     */
    protected $shipmentMethodFilters;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Service\Shipment\ShipmentServiceInterface $shipmentService
     * @param \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodFilterPluginInterface[] $shipmentMethodFilters
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface $shipmentRepository
     * @param \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodAvailabilityCheckerInterface $methodAvailabilityChecker
     * @param \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodPriceReaderInterface $methodPriceReader
     * @param \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodDeliveryTimeReaderInterface $methodDeliveryTimeReader
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface $storeFacade
     */
    public function __construct(
        ShipmentServiceInterface $shipmentService,
        array $shipmentMethodFilters,
        ShipmentRepositoryInterface $shipmentRepository,
        MethodAvailabilityCheckerInterface $methodAvailabilityChecker,
        MethodPriceReaderInterface $methodPriceReader,
        MethodDeliveryTimeReaderInterface $methodDeliveryTimeReader,
        ShipmentToStoreInterface $storeFacade
    ) {
        $this->shipmentService = $shipmentService;
        $this->shipmentMethodFilters = $shipmentMethodFilters;
        $this->shipmentRepository = $shipmentRepository;
        $this->methodAvailabilityChecker = $methodAvailabilityChecker;
        $this->methodPriceReader = $methodPriceReader;
        $this->methodDeliveryTimeReader = $methodDeliveryTimeReader;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param int $idShipmentMethod
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findAvailableMethodById(int $idShipmentMethod, QuoteTransfer $quoteTransfer): ?ShipmentMethodTransfer
    {
        $idStore = $this->getIdStoreFromQuote($quoteTransfer);
        $shipmentMethodTransfer = $this->shipmentRepository->findShipmentMethodByIdAndIdStore($idShipmentMethod, $idStore);
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
        $idStore = $this->getIdStoreFromQuote($quoteTransfer);
        $activeShipmentMethodTransfers = $this->shipmentRepository->getActiveShipmentMethodsForStore($idStore);

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
            if ($shipmentMethodsTransfer->getMethods()->count() === 0) {
                continue;
            }

            $this->applyShipmentFilters($quoteTransfer, $shipmentMethodsTransfer, $shipmentGroupCollection);

            $shipmentMethodsCollectionTransfer->getShipmentMethods()->offsetSet($index, $shipmentMethodsTransfer);
        }

        return $shipmentMethodsCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     * @param iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     *
     * @return void
     */
    protected function applyShipmentFilters(
        QuoteTransfer $quoteTransfer,
        ShipmentMethodsTransfer $shipmentMethodsTransfer,
        iterable $shipmentGroupCollection
    ): void {
        foreach ($this->shipmentMethodFilters as $shipmentMethodFilter) {
            $shipmentMethodCollection = $this->applyShipmentFilterWithShipmentGroup(
                $quoteTransfer,
                $shipmentMethodsTransfer,
                $shipmentGroupCollection,
                $shipmentMethodFilter
            );
            $shipmentMethodsTransfer->setMethods($shipmentMethodCollection);

            if (count($shipmentMethodsTransfer->getMethods()) < 1) {
                break;
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     * @param iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     * @param \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodFilterPluginInterface $shipmentMethodFilter
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    protected function applyShipmentFilterWithShipmentGroup(
        QuoteTransfer $quoteTransfer,
        ShipmentMethodsTransfer $shipmentMethodsTransfer,
        iterable $shipmentGroupCollection,
        ShipmentMethodFilterPluginInterface $shipmentMethodFilter
    ): ArrayObject {
        $shipmentGroupTransfer = $this->getShipmentGroupByHash(
            $shipmentGroupCollection,
            $shipmentMethodsTransfer->getShipmentHash()
        );

        $shipmentGroupTransfer->setAvailableShipmentMethods($shipmentMethodsTransfer);

        return $shipmentMethodFilter->filterShipmentMethods(
            $shipmentGroupTransfer,
            $quoteTransfer
        );
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

        return $shipmentMethodTransfer->setCurrencyIsoCode($currencyTransfer->getCode());
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     * @param string $shipmentHash
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    protected function getShipmentGroupByHash(iterable $shipmentGroupCollection, string $shipmentHash): ShipmentGroupTransfer
    {
        foreach ($shipmentGroupCollection as $shipmentGroupTransfer) {
            if ($shipmentGroupTransfer->getHash() === $shipmentHash) {
                return $shipmentGroupTransfer;
            }
        }

        return new ShipmentGroupTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getIdStoreFromQuote(QuoteTransfer $quoteTransfer): int
    {
        $quoteTransfer->requireStore();
        $storeTransfer = $quoteTransfer->getStore();

        if ($storeTransfer->getIdStore() === null) {
            $storeTransfer->requireName();
            $storeTransfer = $this->storeFacade->getStoreByName($storeTransfer->getName());
        }
        $storeTransfer->requireIdStore();

        return $storeTransfer->getIdStore();
    }
}
