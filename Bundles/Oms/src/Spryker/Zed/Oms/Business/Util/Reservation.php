<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Util;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;

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
     * @param \Spryker\Zed\Oms\Business\Util\ActiveProcessFetcherInterface $activeProcessFetcher
     * @param \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Oms\Dependency\Plugin\ReservationHandlerPluginInterface[] $reservationHandlerPlugins
     * @param \Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        ActiveProcessFetcherInterface $activeProcessFetcher,
        OmsQueryContainerInterface $queryContainer,
        array $reservationHandlerPlugins,
        OmsToStoreFacadeInterface $storeFacade
    ) {
        $this->activeProcessFetcher = $activeProcessFetcher;
        $this->queryContainer = $queryContainer;
        $this->reservationHandlerPlugins = $reservationHandlerPlugins;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    public function updateReservationQuantity($sku)
    {
        $currentStoreReservationAmount = $this->sumReservedProductQuantitiesForSku($sku);

        $currentStoreTransfer = $this->storeFacade->getCurrentStore();
        $this->saveReservation($sku, $currentStoreTransfer->getIdStore(), $currentStoreReservationAmount);
        foreach ($currentStoreTransfer->getStoresWithSharedPersistence() as $storeName) {
            $storeTransfer = $this->storeFacade->getStoreByName($storeName);
            $this->saveReservation($sku, $storeTransfer->getIdStore(), $currentStoreReservationAmount);
        }

        $this->handleReservationPlugins($sku);
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return int
     */
    public function sumReservedProductQuantitiesForSku($sku, ?StoreTransfer $storeTransfer = null)
    {
        return $this->sumProductQuantitiesForSku(
            $this->activeProcessFetcher->getReservedStatesFromAllActiveProcesses(),
            $sku,
            false,
            $storeTransfer
        );
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function getOmsReservedProductQuantityForSku($sku, StoreTransfer $storeTransfer)
    {
        $storeTransfer->requireName();

        $idStore = $this->getIdStore($storeTransfer);

        $reservationEntity = $this->queryContainer
            ->queryProductReservationBySkuAndStore($sku, $idStore)
            ->findOne();

        $reservationQuantity = 0;
        if ($reservationEntity !== null) {
            $reservationQuantity = $reservationEntity->getReservationQuantity();
        }

        $reservationQuantity += $this->getReservationsFromOtherStores($sku, $storeTransfer);

        return $reservationQuantity;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $currentStoreTransfer
     *
     * @return int
     */
    public function getReservationsFromOtherStores($sku, StoreTransfer $currentStoreTransfer)
    {
        $reservationQuantity = 0;
        $reservationStores = $this->queryContainer
            ->queryOmsProductReservationStoreBySku($sku)
            ->find();

        foreach ($reservationStores as $omsProductReservationStoreEntity) {
            if ($omsProductReservationStoreEntity->getStore() === $currentStoreTransfer->getName()) {
                continue;
            }
            $reservationQuantity += $omsProductReservationStoreEntity->getReservationQuantity();
        }
        return $reservationQuantity;
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface[] $states
     * @param string $sku
     * @param bool $returnTest
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return int
     */
    protected function sumProductQuantitiesForSku(
        array $states,
        $sku,
        $returnTest = true,
        ?StoreTransfer $storeTransfer = null
    ) {

        if ($storeTransfer) {
            return (int)$this->queryContainer
                ->sumProductQuantitiesForAllSalesOrderItemsBySkuForStore(
                    $states,
                    $sku,
                    $storeTransfer->getName(),
                    $returnTest
                )
                ->findOne();
        }

        return (int)$this->queryContainer
            ->sumProductQuantitiesForAllSalesOrderItemsBySku($states, $sku, $returnTest)
            ->findOne();
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
            ->queryProductReservationBySkuAndStore($sku, $idStore)
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
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    protected function getIdStore(StoreTransfer $storeTransfer)
    {
        if ($storeTransfer->getIdStore()) {
            return $storeTransfer->getIdStore();
        }

        return $this->storeFacade
            ->getStoreByName($storeTransfer->getName())
            ->getIdStore();
    }
}
