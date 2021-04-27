<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockAddress\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\RegionTransfer;
use Generated\Shared\Transfer\StockAddressTransfer;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Country\Persistence\SpyRegion;
use Orm\Zed\StockAddress\Persistence\SpyStockAddress;
use Propel\Runtime\Collection\ObjectCollection;

class StockAddressMapper
{
    /**
     * @param \Orm\Zed\StockAddress\Persistence\SpyStockAddress[]|\Propel\Runtime\Collection\ObjectCollection $stockAddressEntities
     * @param \Generated\Shared\Transfer\StockAddressTransfer[] $stockAddressTransfers
     *
     * @return \Generated\Shared\Transfer\StockAddressTransfer[]
     */
    public function mapStockAddressEntitiesToStockAddressTransfers(ObjectCollection $stockAddressEntities, array $stockAddressTransfers): array
    {
        foreach ($stockAddressEntities as $stockAddressEntity) {
            $stockAddressTransfers[] = $this->mapStockAddressEntityToStockAddressTransfer($stockAddressEntity, new StockAddressTransfer());
        }

        return $stockAddressTransfers;
    }

    /**
     * @param \Orm\Zed\StockAddress\Persistence\SpyStockAddress $stockAddressEntity
     * @param \Generated\Shared\Transfer\StockAddressTransfer $stockAddressTransfer
     *
     * @return \Generated\Shared\Transfer\StockAddressTransfer
     */
    public function mapStockAddressEntityToStockAddressTransfer(
        SpyStockAddress $stockAddressEntity,
        StockAddressTransfer $stockAddressTransfer
    ): StockAddressTransfer {
        $stockAddressTransfer->fromArray($stockAddressEntity->toArray(), true);
        $stockAddressTransfer->setIdStock($stockAddressEntity->getFkStock());

        $countryEntity = $stockAddressEntity->getCountry();
        if ($countryEntity !== null) {
            $countryTransfer = $this->mapCountryEntityToCountryTransfer($countryEntity, new CountryTransfer());
            $stockAddressTransfer->setCountry($countryTransfer);
        }

        $regionEntity = $stockAddressEntity->getRegion();
        if ($regionEntity !== null) {
            $regionTransfer = $this->mapRegionEntityToRegionTransfer($regionEntity, new RegionTransfer());
            $stockAddressTransfer->setRegion($regionTransfer);
        }

        return $stockAddressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StockAddressTransfer $stockAddressTransfer
     * @param \Orm\Zed\StockAddress\Persistence\SpyStockAddress $stockAddressEntity
     *
     * @return \Orm\Zed\StockAddress\Persistence\SpyStockAddress
     */
    public function mapStockAddressTransferToStockAddressEntity(
        StockAddressTransfer $stockAddressTransfer,
        SpyStockAddress $stockAddressEntity
    ): SpyStockAddress {
        $stockAddressEntity->fromArray($stockAddressTransfer->modifiedToArray());
        $stockAddressEntity->setFkStock($stockAddressTransfer->getIdStockOrFail());
        $stockAddressEntity->setFkCountry($stockAddressTransfer->getCountryOrFail()->getIdCountryOrFail());

        if ($stockAddressTransfer->getRegion()) {
            $stockAddressEntity->setFkRegion($stockAddressTransfer->getRegionOrFail()->getIdRegionOrFail());
        }

        return $stockAddressEntity;
    }

    /**
     * @param \Orm\Zed\Country\Persistence\SpyCountry $countryEntity
     * @param \Generated\Shared\Transfer\CountryTransfer $countryTransfer
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    protected function mapCountryEntityToCountryTransfer(SpyCountry $countryEntity, CountryTransfer $countryTransfer): CountryTransfer
    {
        return $countryTransfer->fromArray($countryEntity->toArray(), true);
    }

    /**
     * @param \Orm\Zed\Country\Persistence\SpyRegion $regionEntity
     * @param \Generated\Shared\Transfer\RegionTransfer $regionTransfer
     *
     * @return \Generated\Shared\Transfer\RegionTransfer
     */
    protected function mapRegionEntityToRegionTransfer(SpyRegion $regionEntity, RegionTransfer $regionTransfer): RegionTransfer
    {
        return $regionTransfer->fromArray($regionEntity->toArray(), true);
    }
}
