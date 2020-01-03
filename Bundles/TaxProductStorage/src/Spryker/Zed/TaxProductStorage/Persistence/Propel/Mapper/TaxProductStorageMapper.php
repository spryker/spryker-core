<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Generated\Shared\Transfer\TaxProductStorageTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstract;

class TaxProductStorageMapper
{
    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract[] $spyProductAbstracts
     *
     * @return \Generated\Shared\Transfer\TaxProductStorageTransfer[]
     */
    public function mapSpyProductAbstractsToTaxProductStorageTransfers(array $spyProductAbstracts): array
    {
        $taxProductStorageTransfers = [];
        foreach ($spyProductAbstracts as $spyProductAbstract) {
            $taxProductStorageTransfers[] = $this->mapSpyProductAbstractToTaxProductStorageTransfer(
                $spyProductAbstract,
                new TaxProductStorageTransfer()
            );
        }

        return $taxProductStorageTransfers;
    }

    /**
     * @param \Orm\Zed\TaxProductStorage\Persistence\SpyTaxProductStorage[] $taxProductStorageEntities
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function mapSpyTaxProductStorageToSynchronizationDataTransfer(array $taxProductStorageEntities): array
    {
        $synchronizationDataTransfers = [];

        foreach ($taxProductStorageEntities as $taxProductStorageEntity) {
            /** @var string $data */
            $data = $taxProductStorageEntity->getData();
            $synchronizationDataTransfers[] = (new SynchronizationDataTransfer())
                ->setData($data)
                ->setKey($taxProductStorageEntity->getKey());
        }

        return $synchronizationDataTransfers;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $spyProductAbstract
     * @param \Generated\Shared\Transfer\TaxProductStorageTransfer $taxProductStorageTransfer
     *
     * @return \Generated\Shared\Transfer\TaxProductStorageTransfer
     */
    protected function mapSpyProductAbstractToTaxProductStorageTransfer(
        SpyProductAbstract $spyProductAbstract,
        TaxProductStorageTransfer $taxProductStorageTransfer
    ): TaxProductStorageTransfer {
        return $taxProductStorageTransfer
            ->setSku($spyProductAbstract->getSku())
            ->setIdProductAbstract($spyProductAbstract->getIdProductAbstract())
            ->setIdTaxSet($spyProductAbstract->getFkTaxSet());
    }
}
