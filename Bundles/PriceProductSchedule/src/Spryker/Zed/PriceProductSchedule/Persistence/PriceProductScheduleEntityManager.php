<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Persistence;

use DateTime;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleList;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\PriceProductSchedule\Persistence\Exception\PriceProductScheduleListNotFoundException;

/**
 * @method \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductSchedulePersistenceFactory getFactory()
 */
class PriceProductScheduleEntityManager extends AbstractEntityManager implements PriceProductScheduleEntityManagerInterface
{
    protected const PATTERN_MINUS_DAYS = '-%s days';

    /**
     * @param int $daysRetained
     *
     * @return void
     */
    public function deleteOldScheduledPrices(int $daysRetained): void
    {
        $priceProductScheduleQuery = $this->getFactory()
            ->createPriceProductScheduleQuery();

        $filterTo = (new DateTime(sprintf(static::PATTERN_MINUS_DAYS, $daysRetained)));

        $priceProductScheduleQuery
            ->filterByActiveTo(['max' => $filterTo], Criteria::LESS_THAN)
            ->filterByIsCurrent(false)
            ->find()
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    public function savePriceProductSchedule(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): PriceProductScheduleTransfer {
        $priceProductScheduleQuery = $this->getFactory()
            ->createPriceProductScheduleQuery();

        $priceProductScheduleEntity = $priceProductScheduleQuery
            ->filterByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule())
            ->findOneOrCreate();

        $priceProductScheduleEntity = $this->getFactory()
            ->createPriceProductScheduleMapper()
            ->mapPriceProductScheduleTransferToPriceProductScheduleEntity(
                $priceProductScheduleTransfer,
                $priceProductScheduleEntity
            );

        $priceProductScheduleEntity->save();

        $priceProductScheduleTransfer->setIdPriceProductSchedule((int)$priceProductScheduleEntity->getIdPriceProductSchedule());

        return $priceProductScheduleTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    public function createPriceProductSchedule(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): PriceProductScheduleTransfer {
        $priceProductScheduleTransfer->requirePriceProductScheduleList()->getPriceProductScheduleList()->requireIdPriceProductScheduleList();
        $priceProductScheduleTransfer->getPriceProduct()->requireMoneyValue();

        $priceProductScheduleEntity = $this->getFactory()
            ->createPriceProductScheduleMapper()
            ->mapPriceProductScheduleTransferToPriceProductScheduleEntity(
                $priceProductScheduleTransfer,
                new SpyPriceProductSchedule()
            );

        $priceProductScheduleEntity->save();

        return $priceProductScheduleTransfer->setIdPriceProductSchedule(
            (int)$priceProductScheduleEntity->getIdPriceProductSchedule()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer
     */
    public function createPriceProductScheduleList(
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): PriceProductScheduleListTransfer {
        $priceProductScheduleListTransfer->requireName();

        $priceProductScheduleListEntity = $this->getFactory()
            ->createPriceProductScheduleListMapper()
            ->mapPriceProductScheduleListTransferToPriceProductScheduleListEntity(
                $priceProductScheduleListTransfer,
                new SpyPriceProductScheduleList()
            );

        $priceProductScheduleListEntity->save();

        $priceProductScheduleListTransfer->setIdPriceProductScheduleList((int)$priceProductScheduleListEntity->getIdPriceProductScheduleList());

        return $priceProductScheduleListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @throws \Spryker\Zed\PriceProductSchedule\Persistence\Exception\PriceProductScheduleListNotFoundException
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer
     */
    public function updatePriceProductScheduleList(
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): PriceProductScheduleListTransfer {
        $priceProductScheduleListTransfer->requireIdPriceProductScheduleList();

        $priceProductScheduleListEntity = $this->getFactory()
            ->createPriceProductScheduleListQuery()
            ->filterByIdPriceProductScheduleList($priceProductScheduleListTransfer->getIdPriceProductScheduleList())
            ->findOne();

        if ($priceProductScheduleListEntity === null) {
            throw new PriceProductScheduleListNotFoundException(
                sprintf(
                    'Price product schedule list was not found by given id %s',
                    $priceProductScheduleListTransfer->getIdPriceProductScheduleList()
                )
            );
        }

        $priceProductScheduleListEntity = $this->getFactory()
            ->createPriceProductScheduleListMapper()
            ->mapPriceProductScheduleListTransferToPriceProductScheduleListEntity(
                $priceProductScheduleListTransfer,
                $priceProductScheduleListEntity
            );

        $priceProductScheduleListEntity->save();

        return $priceProductScheduleListTransfer;
    }

    /**
     * @param int $idPriceProductSchedule
     *
     * @return void
     */
    public function deletePriceProductScheduleById(int $idPriceProductSchedule): void
    {
        $this->getFactory()
            ->createPriceProductScheduleQuery()
            ->filterByIdPriceProductSchedule($idPriceProductSchedule)
            ->find()
            ->delete();
    }

    /**
     * @param int $idPriceProductScheduleList
     *
     * @return void
     */
    public function deletePriceProductScheduleListById(int $idPriceProductScheduleList): void
    {
        $this->getFactory()
            ->createPriceProductScheduleListQuery()
            ->filterByIdPriceProductScheduleList($idPriceProductScheduleList)
            ->find()
            ->delete();
    }
}
