<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Reader;

use Generated\Shared\Transfer\OmsProcessTransfer;
use Generated\Shared\Transfer\OmsStateCollectionTransfer;
use Generated\Shared\Transfer\OmsStateTransfer;
use Generated\Shared\Transfer\ReservationRequestTransfer;
use Generated\Shared\Transfer\ReservationResponseTransfer;
use Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Oms\Business\Util\ActiveProcessFetcherInterface;
use Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface;
use Spryker\Zed\Oms\Persistence\OmsRepositoryInterface;

class ReservationReader implements ReservationReaderInterface
{
    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface
     */
    protected $omsRepository;

    /**
     * @var \Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Oms\Business\Util\ActiveProcessFetcherInterface
     */
    protected $activeProcessFetcher;

    /**
     * @var \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationReaderStrategyPluginInterface[]
     */
    protected $omsReservationReaderStrategyPlugins;

    /**
     * @deprecated Use {@link omsReservationAggregationPlugins} instead.
     *
     * @var \Spryker\Zed\OmsExtension\Dependency\Plugin\ReservationAggregationStrategyPluginInterface[]
     */
    protected $reservationAggregationPlugins;

    /**
     * @var \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationAggregationPluginInterface[]
     */
    protected $omsReservationAggregationPlugins;

    /**
     * @param \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface $omsRepository
     * @param \Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Oms\Business\Util\ActiveProcessFetcherInterface $activeProcessFetcher
     * @param \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationReaderStrategyPluginInterface[] $omsReservationReaderStrategyPlugins
     * @param \Spryker\Zed\OmsExtension\Dependency\Plugin\ReservationAggregationStrategyPluginInterface[] $reservationAggregationPlugins
     * @param \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationAggregationPluginInterface[] $omsReservationAggregationPlugins
     */
    public function __construct(
        OmsRepositoryInterface $omsRepository,
        OmsToStoreFacadeInterface $storeFacade,
        ActiveProcessFetcherInterface $activeProcessFetcher,
        array $omsReservationReaderStrategyPlugins,
        array $reservationAggregationPlugins,
        array $omsReservationAggregationPlugins
    ) {
        $this->omsRepository = $omsRepository;
        $this->storeFacade = $storeFacade;
        $this->activeProcessFetcher = $activeProcessFetcher;
        $this->omsReservationReaderStrategyPlugins = $omsReservationReaderStrategyPlugins;
        $this->reservationAggregationPlugins = $reservationAggregationPlugins;
        $this->omsReservationAggregationPlugins = $omsReservationAggregationPlugins;
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
        $reservationQuantity = $this->omsRepository->findProductReservationQuantity($sku, $idStore);
        $reservationQuantity = $reservationQuantity->add(
            $this->getReservationsFromOtherStores($sku, $storeTransfer)
        );

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
        $reservationResponseTransfers = $this->omsRepository->findProductReservationStores($sku);

        foreach ($reservationResponseTransfers as $reservationResponseTransfer) {
            if ($reservationResponseTransfer->getStoreName() === $currentStoreTransfer->getName()) {
                continue;
            }

            $reservationQuantity = $reservationQuantity->add(
                $reservationResponseTransfer->getReservationQuantity()
            );
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
     * @deprecated Use {@link sumReservedProductQuantities()} instead.
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

    /**
     * @param \Generated\Shared\Transfer\ReservationRequestTransfer $reservationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemStateAggregationTransfer[]
     */
    protected function aggregateReservations(
        ReservationRequestTransfer $reservationRequestTransfer
    ): array {
        foreach ($this->omsReservationAggregationPlugins as $omsReservationAggregationPlugin) {
            return $omsReservationAggregationPlugin->aggregateReservations($reservationRequestTransfer);
        }

        return $this->aggregateSalesOrderItemReservations(
            $reservationRequestTransfer->getReservedStates(),
            $reservationRequestTransfer->getSku(),
            $reservationRequestTransfer->getStore()
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
}
