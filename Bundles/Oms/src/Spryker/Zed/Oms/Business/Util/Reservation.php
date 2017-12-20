<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Util;

use Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface;
use Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;

class Reservation implements ReservationInterface
{
    /**
     * @var \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject
     */
    protected $activeProcesses;

    /**
     * @var \Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface
     */
    protected $builder;

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
     * @var array|\Spryker\Zed\Oms\Dependency\Plugin\ReservationSynchronizationPluginInterface[]
     */
    protected $reservationSynchronizationPlugins;

    /**
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $activeProcesses
     * @param \Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface $builder
     * @param \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Oms\Dependency\Plugin\ReservationHandlerPluginInterface[] $reservationHandlerPlugins
     * @param \Spryker\Zed\Oms\Dependency\Plugin\ReservationSynchronizationPluginInterface[] $reservationSynchronizationPlugins
     * @param \Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        ReadOnlyArrayObject $activeProcesses,
        BuilderInterface $builder,
        OmsQueryContainerInterface $queryContainer,
        array $reservationHandlerPlugins,
        array $reservationSynchronizationPlugins,
        OmsToStoreFacadeInterface $storeFacade
    ) {

        $this->activeProcesses = $activeProcesses;
        $this->builder = $builder;
        $this->queryContainer = $queryContainer;
        $this->reservationHandlerPlugins = $reservationHandlerPlugins;
        $this->reservationSynchronizationPlugins = $reservationSynchronizationPlugins;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param string $sku
     * @param null|string $storeName
     *
     * @return void
     */
    public function updateReservationQuantity($sku, $storeName = null)
    {
        $currentStoreTransfer = $this->storeFacade->getCurrentStore();
        $currentStoreReservationAmount = $this->sumReservedProductQuantitiesForSku($sku, $currentStoreTransfer->getName());

        foreach ($this->storeFacade->getAllStores() as $storeTransfer) {
            if ($currentStoreTransfer->getIdStore() === $storeTransfer->getIdStore()) {
                $this->saveReservation($sku, $storeTransfer->getIdStore(), $currentStoreReservationAmount);
                continue;
            }

            $omsAvailabilityReservationRequest = (new OmsAvailabilityReservationRequestTransfer())
                ->setSku($sku)
                ->setCurrentStore($currentStoreTransfer)
                ->setCurrentStoreReservationAmount($currentStoreReservationAmount)
                ->setSynchronizeToStore($storeTransfer);

            $this->executeReservationSynchronizationPlugins($omsAvailabilityReservationRequest);
        }

        $this->handleReservationPlugins($sku);
    }

    /**
     * @param \Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer $omsAvailabilityReservationRequestTransfer
     *
     * @return void
     */
    public function saveReservationRequest(OmsAvailabilityReservationRequestTransfer $omsAvailabilityReservationRequestTransfer)
    {
        $omsAvailabilityReservationRequestTransfer->requireSynchronizeToStore()->requireSku();

        $storeTransfer = $omsAvailabilityReservationRequestTransfer->getSynchronizeToStore();
        $this->saveReservation(
            $omsAvailabilityReservationRequestTransfer->getSku(),
            $storeTransfer->getIdStore(),
            $omsAvailabilityReservationRequestTransfer->getCurrentStoreReservationAmount()
        );
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     * @return int
     */
    public function sumReservedProductQuantitiesForSku($sku, StoreTransfer $storeTransfer = null)
    {
        return $this->sumProductQuantitiesForSku($this->retrieveReservedStates(), $sku, false, $storeTransfer);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function getOmsReservedProductQuantityForSku($sku, StoreTransfer $storeTransfer)
    {
        $reservationEntity = $this->queryContainer
            ->createOmsProductReservationQuery($sku)
            ->filterByFkStore($storeTransfer->getIdStore())
            ->findOne();

        if ($reservationEntity === null) {
            return 0;
        }

        return $reservationEntity->getReservationQuantity();
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface[] $states
     * @param string $sku
     * @param bool $returnTest
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    protected function sumProductQuantitiesForSku(
        array $states,
        $sku,
        $returnTest = true,
        StoreTransfer $storeTransfer = null
    ) {

        $query = $this->queryContainer
            ->sumProductQuantitiesForAllSalesOrderItemsBySku($states, $sku, $returnTest);

        if ($storeTransfer) {
            $query
                ->useOrderQuery()
                    ->filterByStore($storeTransfer->getName())
                ->endUse();
        }

        return (int)$query->findOne();

    }

    /**
     * @return array
     */
    protected function retrieveReservedStates()
    {
        $reservedStates = [];
        foreach ($this->activeProcesses as $processName) {
            $builder = clone $this->builder;
            $process = $builder->createProcess($processName);
            $reservedStates = array_merge($reservedStates, $process->getAllReservedStates());
        }

        return $reservedStates;
    }

    /**
     * @param string $sku
     * @param int $idStore
     * @param int $reservationQuantity
     *
     * @return void
     */
    protected function saveReservation($sku, $idStore, $reservationQuantity)
    {
        $reservationEntity = $this->queryContainer
            ->createOmsProductReservationQuery($sku)
            ->filterByFkStore($idStore)
            ->findOneOrCreate();

        $reservationEntity->setReservationQuantity($reservationQuantity);
        $reservationEntity->save();
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
     * @param \Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer $omsAvailabilityReservationRequest
     *
     * @return void
     */
    protected function executeReservationSynchronizationPlugins(OmsAvailabilityReservationRequestTransfer $omsAvailabilityReservationRequest)
    {
        foreach ($this->reservationSynchronizationPlugins as $reservationSynchronizationPlugin) {
            $reservationSynchronizationPlugin->synchronize($omsAvailabilityReservationRequest);
        }
    }
}
