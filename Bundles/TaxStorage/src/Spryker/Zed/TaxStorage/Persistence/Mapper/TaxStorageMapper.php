ch<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Persistence\Mapper;

use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetStorageTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage;

class TaxStorageMapper implements TaxStorageMapperInterface
{
    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxRate $taxRateEntity
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     *
     * @return \Generated\Shared\Transfer\TaxRateTransfer
     */
    public function mapTaxRateEntityToTransfer(SpyTaxRate $taxRateEntity, TaxRateTransfer $taxRateTransfer): TaxRateTransfer
    {
        return $taxRateTransfer->fromArray(
            $taxRateEntity->toArray(),
            true
        );
    }

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSet $taxSetEntity
     * @param \Generated\Shared\Transfer\TaxSetTransfer $taxSetTransfer
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer
     */
    public function mapTaxSetEntityToTransfer(SpyTaxSet $taxSetEntity, TaxSetTransfer $taxSetTransfer): TaxSetTransfer
    {
        $taxSetTransfer->fromArray($taxSetEntity->toArray(), true);

        foreach ($taxSetEntity->getSpyTaxRates() as $taxRateEntity) {
            $taxSetTransfer->addTaxRate(
                $this->mapTaxRateEntityToTransfer($taxRateEntity, new TaxRateTransfer())
            );
        }

        return $taxSetTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileStorageTransfer $fileStorageTransfer
     * @param \Orm\Zed\FileManagerStorage\Persistence\SpyFileStorage $fileStorage
     *
     * @return \Orm\Zed\FileManagerStorage\Persistence\SpyFileStorage
     */
    public function mapTaxSetStorageTransferToEntity(TaxSetStorageTransfer $taxSetStorageTransfer, SpyTaxSetStorage $spyTaxSetStorage): SpyTaxSetStorage
    {
        $spyTaxSetStorage->fromArray($taxSetStorageTransfer->toArray());
        $spyTaxSetStorage->setData($taxSetStorageTransfer->getData()->toArray());

        return $spyTaxSetStorage;
    }

    public function mapTaxSetStorageEntityToTransfer(SpyTaxSetStorage $spyTaxSetStorage, TaxSetStorageTransfer $taxSetStorageTransfer): TaxSetStorageTransfer
    {
        $taxSetStorageTransfer->fromArray($spyTaxSetStorage->toArray(), true);
        $taxSetStorageTransfer->setId($spyTaxSetStorage->getIdTaxSetStorage());

        return $taxSetStorageTransfer;
    }
}
