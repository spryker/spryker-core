<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Util;

use Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface;
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
     *
     * @return void
     */
    public function updateReservationQuantity($sku)
    {
        $this->saveReservation($sku);
        $this->handleReservationPlugins($sku);
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
     *
     * @return int
     */
    public function getOmsReservedProductQuantitiesForSku($sku)
    {
        $reservationEntity = $this->queryContainer->createOmsProductReservationQuery($sku)->findOne();
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
     *
     * @return void
     */
    protected function saveReservation($sku)
    {
        $reservationQuantity = $this->sumReservedProductQuantitiesForSku($sku);
        $reservationEntity = $this->queryContainer->createOmsProductReservationQuery($sku)->findOneOrCreate();
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
}
