<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Util;

use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface;
use Spryker\Zed\Oms\Dependency\Plugin\ReservationStoreAwareHandlerPluginInterface;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;
use Spryker\Zed\Store\Business\StoreFacade;

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
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $activeProcesses
     * @param \Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface $builder
     * @param \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Oms\Dependency\Plugin\ReservationHandlerPluginInterface[] $reservationHandlerPlugins
     */
    public function __construct(
        ReadOnlyArrayObject $activeProcesses,
        BuilderInterface $builder,
        OmsQueryContainerInterface $queryContainer,
        array $reservationHandlerPlugins
    ) {

        $this->activeProcesses = $activeProcesses;
        $this->builder = $builder;
        $this->queryContainer = $queryContainer;
        $this->reservationHandlerPlugins = $reservationHandlerPlugins;
    }

    /**
     * @param string $sku
     * @param null|string $storeName
     *
     * @return void
     */
    public function updateReservationQuantity($sku, $storeName = null)
    {
        $store = SpyStoreQuery::create()
            ->filterByName($storeName)
            ->findOne();

        $storeTransfer = new StoreTransfer();
        $storeTransfer->setIdStore($store->getIdStore());

        $this->saveReservation($sku, $store->getIdStore());
        $this->handleReservationPlugins($sku, $storeTransfer);
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    public function sumReservedProductQuantitiesForSku($sku)
    {
        return $this->sumProductQuantitiesForSku($this->retrieveReservedStates(), $sku, false);
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
     *
     * @return int
     */
    protected function sumProductQuantitiesForSku(array $states, $sku, $returnTest = true)
    {
        return (int)$this->queryContainer
            ->sumProductQuantitiesForAllSalesOrderItemsBySku($states, $sku, $returnTest)
            ->findOne();
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
     *
     * @return void
     */
    protected function saveReservation($sku, $idStore)
    {
        $reservationQuantity = $this->sumReservedProductQuantitiesForSku($sku);
        $reservationEntity = $this->queryContainer
            ->createOmsProductReservationQuery($sku)
            ->filterByFkStore($idStore)
            ->findOneOrCreate();

        $reservationEntity->setReservationQuantity($reservationQuantity);
        $reservationEntity->save();
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    protected function handleReservationPlugins($sku, StoreTransfer $storeTransfer)
    {
        foreach ($this->reservationHandlerPlugins as $reservationHandlerPluginInterface) {
            if ($reservationHandlerPluginInterface instanceof ReservationStoreAwareHandlerPluginInterface) {
                $reservationHandlerPluginInterface->handleStock($sku, $storeTransfer);
            } else {
                $reservationHandlerPluginInterface->handle($sku);
            }
        }
    }
}
