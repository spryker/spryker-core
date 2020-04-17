<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Util;

use Generated\Shared\Transfer\OmsProcessTransfer;
use Generated\Shared\Transfer\OmsStateCollectionTransfer;
use Generated\Shared\Transfer\OmsStateTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\ReservationResponseTransfer;
use Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface;
use Spryker\Zed\Oms\Persistence\OmsEntityManagerInterface;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;
use Spryker\Zed\Oms\Persistence\OmsRepositoryInterface;

class Reservation implements ReservationInterface
{
    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Oms\Dependency\Plugin\ReservationHandlerPluginInterface[]
     */
    protected $reservationHandlerPlugins;

    /**
     * @var \Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Oms\Business\Util\ActiveProcessFetcherInterface
     */
    protected $activeProcessFetcher;

    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface
     */
    protected $omsRepository;

    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsEntityManagerInterface
     */
    protected $omsEntityManager;

    /**
     * @var \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationReaderStrategyPluginInterface[]
     */
    protected $omsReservationReaderStrategyPlugins;

    /**
     * @var \Spryker\Zed\OmsExtension\Dependency\Plugin\ReservationAggregationStrategyPluginInterface[]
     */
    protected $reservationAggregationPlugins;

    /**
     * @var \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationAggregationStrategyPluginInterface[]
     */
    protected $omsReservationAggregationStrategyPlugins;

    /**
     * @var \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationWriterStrategyPluginInterface[]
     */
    protected $omsReservationWriterStrategyPlugins;

    /**
     * @var \Spryker\Zed\OmsExtension\Dependency\Plugin\ReservationHandlerTerminationAwareStrategyPluginInterface[]
     */
    protected $reservationHandlerTerminationAwareStrategyPlugins;

    /**
     * @param \Spryker\Zed\Oms\Business\Util\ActiveProcessFetcherInterface $activeProcessFetcher
     * @param \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Oms\Dependency\Plugin\ReservationHandlerPluginInterface[] $reservationHandlerPlugins
     * @param \Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface $omsRepository
     * @param \Spryker\Zed\Oms\Persistence\OmsEntityManagerInterface $omsEntityManager
     * @param \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationReaderStrategyPluginInterface[] $omsReservationReaderStrategyPlugins
     * @param \Spryker\Zed\OmsExtension\Dependency\Plugin\ReservationAggregationStrategyPluginInterface[] $reservationAggregationPlugins
     * @param \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationAggregationStrategyPluginInterface[] $omsReservationAggregationStrategyPlugins
     * @param \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationWriterStrategyPluginInterface[] $omsReservationWriterStrategyPlugins
     * @param \Spryker\Zed\OmsExtension\Dependency\Plugin\ReservationHandlerTerminationAwareStrategyPluginInterface[] $reservationHandlerTerminationAwareStrategyPlugins
     */
    public function __construct(
        ActiveProcessFetcherInterface $activeProcessFetcher,
        OmsQueryContainerInterface $queryContainer,
        array $reservationHandlerPlugins,
        OmsToStoreFacadeInterface $storeFacade,
        OmsRepositoryInterface $omsRepository,
        OmsEntityManagerInterface $omsEntityManager,
        array $omsReservationReaderStrategyPlugins,
        array $reservationAggregationPlugins,
        array $omsReservationAggregationStrategyPlugins,
        array $omsReservationWriterStrategyPlugins,
        array $reservationHandlerTerminationAwareStrategyPlugins
    ) {
        $this->activeProcessFetcher = $activeProcessFetcher;
        $this->queryContainer = $queryContainer;
        $this->reservationHandlerPlugins = $reservationHandlerPlugins;
        $this->storeFacade = $storeFacade;
        $this->omsRepository = $omsRepository;
        $this->omsEntityManager = $omsEntityManager;
        $this->reservationAggregationPlugins = $reservationAggregationPlugins;
        $this->omsReservationReaderStrategyPlugins = $omsReservationReaderStrategyPlugins;
        $this->omsReservationAggregationStrategyPlugins = $omsReservationAggregationStrategyPlugins;
        $this->omsReservationWriterStrategyPlugins = $omsReservationWriterStrategyPlugins;
        $this->reservationHandlerTerminationAwareStrategyPlugins = $reservationHandlerTerminationAwareStrategyPlugins;
    }

    /**
     * @deprecated @deprecated Use `\Spryker\Zed\Oms\Business\Util\Reservation::updateReservation()` instead.
     *
     * @param string $sku
     *
     * @return void
     */
    public function updateReservationQuantity($sku)
    {
        $reservationAmount = $this->sumReservedProductQuantitiesForSku($sku);
        $currentStoreTransfer = $this->storeFacade->getCurrentStore();
        $this->saveReservation($sku, $currentStoreTransfer, $reservationAmount);
        foreach ($currentStoreTransfer->getStoresWithSharedPersistence() as $storeName) {
            $storeTransfer = $this->storeFacade->getStoreByName($storeName);
            $this->saveReservation($sku, $storeTransfer, $reservationAmount);
        }

        $this->handleReservationPlugins($sku);
    }

    /**
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    public function updateReservation(ReservationRequestTransfer $reservationRequestTransfer): void
    {
        $reservationQuantity = $this->sumReservedProductQuantities($reservationRequestTransfer);
        $currentStoreTransfer = $this->storeFacade->getCurrentStore();
        $reservationRequestTransfer->setReservationQuantity($reservationQuantity)
            ->setStore($currentStoreTransfer);
        $this->saveReservationQuantity($reservationRequestTransfer);

        foreach ($currentStoreTransfer->getStoresWithSharedPersistence() as $storeName) {
            $storeTransfer = $this->storeFacade->getStoreByName($storeName);
            $reservationRequestTransfer->setStore($storeTransfer);
            $this->saveReservationQuantity($reservationRequestTransfer);
        }

        foreach ($this->reservationHandlerTerminationAwareStrategyPlugins as $reservationHandlerTerminationAwareStrategyPlugin) {
            if ($reservationHandlerTerminationAwareStrategyPlugin->isTerminated($reservationRequestTransfer)) {
                break;
            }

            if (!$reservationHandlerTerminationAwareStrategyPlugin->isApplicable($reservationRequestTransfer)) {
                continue;
            }

            $reservationHandlerTerminationAwareStrategyPlugin->handle($reservationRequestTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function sumReservedProductQuantities(ReservationRequestTransfer $reservationRequestTransfer): Decimal
    {
        $reservedStates = $this->getOmsReservedStateCollection();

        $reservationRequestTransfer->setReservedStates($reservedStates)
            ->setStore($this->storeFacade->getCurrentStore());
        $salesOrderItemStateAggregationTransfers = $this->aggregateReservations($reservationRequestTransfer);

        return $this->calculateReservationQuantity(
            $reservedStates,
            $salesOrderItemStateAggregationTransfers
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function sumReservedProductQuantitiesForSku(string $sku, ?StoreTransfer $storeTransfer = null): Decimal
    {
        $reservedStates = $this->getOmsReservedStateCollection();
        $salesAggregationTransfers = $this->aggregateSalesOrderItemReservations($reservedStates, $sku, $storeTransfer);

        return $this->calculateReservationQuantity(
            $reservedStates,
            $salesAggregationTransfers
        );
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getOmsReservedProductQuantityForSku(string $sku, StoreTransfer $storeTransfer): Decimal
    {
        $idStore = $this->getIdStore($storeTransfer);

        $reservationEntity = $this->queryContainer
            ->queryProductReservationBySkuAndStore($sku, $idStore)
            ->findOne();

        $reservationQuantity = new Decimal(0);
        if ($reservationEntity !== null) {
            $reservationQuantity = new Decimal($reservationEntity->getReservationQuantity());
        }

        $reservationQuantity = $reservationQuantity->add($this->getReservationsFromOtherStores($sku, $storeTransfer));

        return $reservationQuantity;
    }

    /**
     * @param string[] $skus
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getOmsReservedProductQuantityForSkus(array $skus, StoreTransfer $storeTransfer): Decimal
    {
        $idStore = $this->getIdStore($storeTransfer);

        return $this->omsRepository->getSumOmsReservedProductQuantityByConcreteProductSkusForStore($skus, $idStore);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $currentStoreTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getReservationsFromOtherStores(string $sku, StoreTransfer $currentStoreTransfer): Decimal
    {
        $reservationQuantity = new Decimal(0);
        $reservationStores = $this->queryContainer
            ->queryOmsProductReservationStoreBySku($sku)
            ->find();

        foreach ($reservationStores as $omsProductReservationStoreEntity) {
            if ($omsProductReservationStoreEntity->getStore() === $currentStoreTransfer->getName()) {
                continue;
            }
            $reservationQuantity = $reservationQuantity->add($omsProductReservationStoreEntity->getReservationQuantity());
        }

        return $reservationQuantity;
    }

    /**
     * @return \Generated\Shared\Transfer\OmsStateCollectionTransfer
     */
    public function getOmsReservedStateCollection(): OmsStateCollectionTransfer
    {
        $reservedStatesTransfer = new OmsStateCollectionTransfer();
        $stateProcessMap = [];
        foreach ($this->activeProcessFetcher->getReservedStatesFromAllActiveProcesses() as $reservedState) {
            $stateProcessMap[$reservedState->getName()][] = $reservedState->getProcess()->getName();
        }

        foreach ($stateProcessMap as $reservedStateName => $stateProcesses) {
            $stateTransfer = (new OmsStateTransfer())->setName($reservedStateName);
            foreach ($stateProcesses as $processName) {
                $stateTransfer->addProcess($processName, (new OmsProcessTransfer())->setName($processName));
            }

            $reservedStatesTransfer->addState($reservedStateName, $stateTransfer);
        }

        return $reservedStatesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReservationResponseTransfer
     */
    public function getOmsReservedProductQuantity(ReservationRequestTransfer $reservationRequestTransfer): ReservationResponseTransfer
    {
        foreach ($this->omsReservationReaderStrategyPlugins as $omsReservationReaderStrategyPlugin) {
            if ($omsReservationReaderStrategyPlugin->isApplicable($reservationRequestTransfer)) {
                return $omsReservationReaderStrategyPlugin->getReservationQuantity($reservationRequestTransfer);
            }
        }

        $reservationQuantity = $this->getOmsReservedProductQuantityForSku(
            $reservationRequestTransfer->requireSku()->getSku(),
            $reservationRequestTransfer->requireStore()->getStore()
        );

        return (new ReservationResponseTransfer())->setReservationQuantity($reservationQuantity);
    }

    /**
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[]
     */
    protected function aggregateReservations(
        ReservationRequestTransfer $reservationRequestTransfer
    ): array {
        foreach ($this->omsReservationAggregationStrategyPlugins as $omsReservationAggregationPlugin) {
            if (!$omsReservationAggregationPlugin->isApplicable($reservationRequestTransfer)) {
                continue;
            }

            $salesAggregationTransfers = $omsReservationAggregationPlugin->aggregateReservations($reservationRequestTransfer);

            if ($salesAggregationTransfers) {
                return $salesAggregationTransfers;
            }
        }

        return $this->aggregateSalesOrderItemReservations(
            $reservationRequestTransfer->getReservedStates(),
            $reservationRequestTransfer->getSku(),
            $reservationRequestTransfer->getStore()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OmsStateCollectionTransfer $reservedStates
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[]
     */
    protected function aggregateSalesOrderItemReservations(
        OmsStateCollectionTransfer $reservedStates,
        string $sku,
        ?StoreTransfer $storeTransfer = null
    ): array {
        foreach ($this->reservationAggregationPlugins as $reservationAggregationPlugin) {
            $salesAggregationTransfers = $reservationAggregationPlugin->aggregateReservations(
                $sku,
                $reservedStates,
                $storeTransfer
            );

            if ($salesAggregationTransfers !== []) {
                return $salesAggregationTransfers;
            }
        }

        return $this->omsRepository->getSalesOrderAggregationBySkuAndStatesNames(
            array_keys($reservedStates->getStates()->getArrayCopy()),
            $sku,
            $storeTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OmsStateCollectionTransfer $reservedStates
     * @param \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[] $salesAggregationTransfers
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function calculateReservationQuantity(OmsStateCollectionTransfer $reservedStates, array $salesAggregationTransfers): Decimal
    {
        $sumQuantity = new Decimal(0);
        foreach ($salesAggregationTransfers as $salesAggregationTransfer) {
            $this->assertAggregationTransfer($salesAggregationTransfer);
            if (!$this->assertStateAndProcessExists($reservedStates, $salesAggregationTransfer->getStateName(), $salesAggregationTransfer->getProcessName())) {
                continue;
            }

            $salesAggregationTransfer->requireSumAmount();
            $sumQuantity = $sumQuantity->add($salesAggregationTransfer->getSumAmount());
        }

        return $sumQuantity;
    }

    /**
     * @param \Generated\Shared\Transfer\OmsStateCollectionTransfer $statesCollection
     * @param string $stateName
     * @param string $processName
     *
     * @return bool
     */
    protected function assertStateAndProcessExists(OmsStateCollectionTransfer $statesCollection, string $stateName, string $processName): bool
    {
        return $statesCollection->getStates()->offsetExists($stateName) &&
            $statesCollection->getStates()[$stateName]->getProcesses()->offsetExists($processName);
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer $salesAggregationTransfer
     *
     * @return void
     */
    protected function assertAggregationTransfer(SalesOrderItemStateAggregationTransfer $salesAggregationTransfer): void
    {
        $salesAggregationTransfer
            ->requireSku()
            ->requireProcessName()
            ->requireStateName();
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Spryker\DecimalObject\Decimal $reservationQuantity
     *
     * @return void
     */
    public function saveReservation(string $sku, StoreTransfer $storeTransfer, Decimal $reservationQuantity): void
    {
        $storeTransfer->requireIdStore();

        $reservationEntity = $this->queryContainer
            ->queryProductReservationBySkuAndStore($sku, $storeTransfer->getIdStore())
            ->findOneOrCreate();

        $reservationEntity->setReservationQuantity($reservationQuantity);
        $reservationEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    protected function saveReservationQuantity(ReservationRequestTransfer $reservationRequestTransfer): void
    {
        foreach ($this->omsReservationWriterStrategyPlugins as $omsReservationWriterStrategyPlugin) {
            if ($omsReservationWriterStrategyPlugin->isApplicable($reservationRequestTransfer)) {
                $omsReservationWriterStrategyPlugin->saveReservation($reservationRequestTransfer);

                return;
            }
        }

        $this->omsEntityManager->saveReservation($reservationRequestTransfer);
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    protected function handleReservationPlugins($sku)
    {
        foreach ($this->reservationHandlerPlugins as $reservationHandlerPluginInterface) {
            $reservationHandlerPluginInterface->handle($sku);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    protected function getIdStore(StoreTransfer $storeTransfer)
    {
        if ($storeTransfer->getIdStore()) {
            return $storeTransfer->getIdStore();
        }

        $storeTransfer->requireName();

        return $this->storeFacade
            ->getStoreByName($storeTransfer->getName())
            ->getIdStore();
    }
}
