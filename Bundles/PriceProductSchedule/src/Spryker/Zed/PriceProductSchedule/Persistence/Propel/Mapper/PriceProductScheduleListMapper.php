<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\PriceProductScheduleListMetaDataTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleList;

class PriceProductScheduleListMapper implements PriceProductScheduleListMapperInterface
{
    protected const COL_NUMBER_OF_PRICES = 'numberOfPrices';
    protected const COL_NUMBER_OF_PRODUCTS = 'numberOfProducts';

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleList $priceProductScheduleListEntity
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer
     */
    public function mapPriceProductScheduleListEntityToPriceProductScheduleListTransfer(
        SpyPriceProductScheduleList $priceProductScheduleListEntity,
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): PriceProductScheduleListTransfer {
        $priceProductScheduleListTransfer
            ->fromArray($priceProductScheduleListEntity->toArray(), true);

        return $this->setMetaDataToPriceProductScheduleListTransfer(
            $priceProductScheduleListEntity,
            $priceProductScheduleListTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleList $priceProductScheduleListEntity
     *
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleList
     */
    public function mapPriceProductScheduleListTransferToPriceProductScheduleListEntity(
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer,
        SpyPriceProductScheduleList $priceProductScheduleListEntity
    ): SpyPriceProductScheduleList {
        $priceProductScheduleListEntity
            ->fromArray($priceProductScheduleListTransfer->toArray());

        return $priceProductScheduleListEntity;
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleList $priceProductScheduleListEntity
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer
     */
    protected function setMetaDataToPriceProductScheduleListTransfer(
        SpyPriceProductScheduleList $priceProductScheduleListEntity,
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): PriceProductScheduleListTransfer {
        $priceProductScheduleListMetadataTransfer = new PriceProductScheduleListMetaDataTransfer();
        $priceProductScheduleListMetadataTransfer->setNumberOfPrices($priceProductScheduleListEntity->getVirtualColumn(static::COL_NUMBER_OF_PRICES));
        $priceProductScheduleListMetadataTransfer->setNumberOfProducts($priceProductScheduleListEntity->getVirtualColumn(static::COL_NUMBER_OF_PRODUCTS));

        return $priceProductScheduleListTransfer->setMetaData($priceProductScheduleListMetadataTransfer);
    }
}
