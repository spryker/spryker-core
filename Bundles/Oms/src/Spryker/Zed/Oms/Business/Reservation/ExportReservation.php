<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Reservation;

use DateTime;
use Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservationLastExportedVersion;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface;
use Spryker\Zed\Oms\Persistence\OmsQueryContainer;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;

class ExportReservation implements ExportReservationInterface
{
    public const CURRENT_DATE_TIME_FORMAT = 'Y-m-d H:i:s';
    /**
     * @var \Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface
     */
    protected $omsQueryContainer;

    /**
     * @var \Spryker\Zed\Oms\Dependency\Plugin\ReservationExportPluginInterface[]
     */
    protected $reservationExportPlugins;

    /**
     * @param \Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface $omsQueryContainer
     * @param \Spryker\Zed\Oms\Dependency\Plugin\ReservationExportPluginInterface[] $reservationExportPlugins
     */
    public function __construct(
        OmsToStoreFacadeInterface $storeFacade,
        OmsQueryContainerInterface $omsQueryContainer,
        array $reservationExportPlugins
    ) {
        $this->storeFacade = $storeFacade;
        $this->omsQueryContainer = $omsQueryContainer;
        $this->reservationExportPlugins = $reservationExportPlugins;
    }

    /**
     * @return void
     */
    public function exportReservation()
    {
        $maxVersion = $this->getMaxVersion();
        $lastExportedVersion = $this->getLastExportedVersion();

        $currentStoreTransfer = $this->storeFacade->getCurrentStore();
        $reservations = $this->findReservations($lastExportedVersion, $maxVersion);

        if (count($reservations) === 0) {
            return;
        }

        $this->exportReservations($reservations->toArray(), $currentStoreTransfer);
        $this->storeLastExportedDate($maxVersion);
    }

    /**
     * @return int
     */
    public function getLastExportedVersion()
    {
        $queryResult = $this->omsQueryContainer
            ->queryOmsProductReservationLastExportedVersion()
            ->orderByUpdatedAt(Criteria::DESC)
            ->findOne();

        $lastExportedVersion = 0;
        if ($queryResult !== null) {
            $lastExportedVersion = (int)$queryResult->getVersion();
        }

        return $lastExportedVersion;
    }

    /**
     * @param \Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer $reservationRequestTransfer
     *
     * @return void
     */
    protected function executeExportReservationPlugins(OmsAvailabilityReservationRequestTransfer $reservationRequestTransfer)
    {
        foreach ($this->reservationExportPlugins as $reservationExportPlugin) {
            $reservationExportPlugin->export($reservationRequestTransfer);
        }
    }

    /**
     * @return int
     */
    protected function getMaxVersion()
    {
        $queryResult = $this->omsQueryContainer
            ->queryMaxReservationChangeVersion()
            ->findOne();

        $version = 0;
        if ($queryResult) {
            $version = (int)$queryResult;
        }

        return $version;
    }

    /**
     * @param int $version
     *
     * @return void
     */
    protected function storeLastExportedDate($version)
    {
        $lastExportedVersion = $this->omsQueryContainer
            ->queryOmsProductReservationLastExportedVersion()
            ->findOneOrCreate();

        if ($lastExportedVersion->isNew()) {
            $lastExportedVersion
                ->setVersion($version)
                ->save();

            return;
        }

        $currentDate = (new DateTime())->format(static::CURRENT_DATE_TIME_FORMAT);

        (new SpyOmsProductReservationLastExportedVersion())
            ->setVersion($version)
            ->setUpdatedAt($currentDate)
            ->save();
    }

    /**
     * @param array $reservations
     * @param \Generated\Shared\Transfer\StoreTransfer $currentStoreTransfer
     *
     * @return void
     */
    protected function exportReservations(array $reservations, StoreTransfer $currentStoreTransfer)
    {
        foreach ($reservations as $reservation) {
            $this->executeExportReservationPlugins(
                $this->mapReservationRequestTransfer($currentStoreTransfer, $reservation)
            );
        }
    }

    /**
     * @param int $lastExportedVersion
     * @param int $maxVisibleVersion
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationChangeVersion[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findReservations($lastExportedVersion, $maxVisibleVersion)
    {
        return $this->omsQueryContainer
            ->queryReservationChangeVersion($lastExportedVersion, $maxVisibleVersion)
            ->find();
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $currentStoreTransfer
     * @param array $reservation
     *
     * @return \Generated\Shared\Transfer\OmsAvailabilityReservationRequestTransfer
     */
    protected function mapReservationRequestTransfer(StoreTransfer $currentStoreTransfer, array $reservation)
    {
        return (new OmsAvailabilityReservationRequestTransfer())
            ->setVersion($reservation[OmsQueryContainer::VERSION])
            ->setSku($reservation[OmsQueryContainer::SKU])
            ->setReservationAmount($reservation[OmsQueryContainer::RESERVATION_QUANTITY])
            ->setOriginStore($currentStoreTransfer);
    }
}
