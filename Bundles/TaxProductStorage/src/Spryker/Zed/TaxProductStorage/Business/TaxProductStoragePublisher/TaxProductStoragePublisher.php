<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Business\TaxProductStoragePublisher;

use Generated\Shared\Transfer\SpyTaxProductStorageEntityTransfer;
use Generated\Shared\Transfer\TaxProductStorageTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\TaxProductStorage\Persistence\SpyTaxProductStorage;
use Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageEntityManagerInterface;
use Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageRepositoryInterface;

class TaxProductStoragePublisher implements TaxProductStoragePublisherInterface
{
    /**
     * @var \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageRepositoryInterface
     */
    protected $taxProductStorageRepository;

    /**
     * @var \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageEntityManagerInterface
     */
    protected $taxProductStorageEntityManager;

    /**
     * @param \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageRepositoryInterface $taxProductStorageRepository
     * @param \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageEntityManagerInterface $taxProductStorageEntityManager
     */
    public function __construct(
        TaxProductStorageRepositoryInterface $taxProductStorageRepository,
        TaxProductStorageEntityManagerInterface $taxProductStorageEntityManager
    ) {
        $this->taxProductStorageRepository = $taxProductStorageRepository;
        $this->taxProductStorageEntityManager = $taxProductStorageEntityManager;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds): void
    {
        $productAbstractEntities = $this->taxProductStorageRepository->findProductAbstractEntitiesByProductAbstractIds($productAbstractIds);
        $indexedTaxProductStorageEntities = $this->taxProductStorageRepository->findTaxProductStorageEntities(
            $productAbstractIds,
            SpyTaxProductStorageEntityTransfer::FK_PRODUCT_ABSTRACT
        );

        $this->storeData($productAbstractEntities, $indexedTaxProductStorageEntities);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract[] $productAbstractEntities
     * @param \Orm\Zed\TaxProductStorage\Persistence\SpyTaxProductStorage[] $indexedTaxProductStorageEntities
     *
     * @return void
     */
    protected function storeData(array $productAbstractEntities, array $indexedTaxProductStorageEntities): void
    {
        foreach ($productAbstractEntities as $productAbstractEntity) {
            $idProductAbstract = $productAbstractEntity->getIdProductAbstract();
            $taxProductStorageEntity = $indexedTaxProductStorageEntities[$idProductAbstract] ?? new SpyTaxProductStorage();

            $this->storeDataSet($productAbstractEntity, $taxProductStorageEntity);
        }
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     * @param \Orm\Zed\TaxProductStorage\Persistence\SpyTaxProductStorage $taxProductStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(SpyProductAbstract $productAbstractEntity, SpyTaxProductStorage $taxProductStorageEntity): void
    {
        $taxProductStorageTransfer = $this->getStorageEntityData($productAbstractEntity);
        $taxProductStorageEntity->setFkProductAbstract($productAbstractEntity->getIdProductAbstract())
            ->setSku($productAbstractEntity->getSku())
            ->setData($taxProductStorageTransfer->toArray());

        $this->taxProductStorageEntityManager->saveTaxProductStorage($taxProductStorageEntity);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return \Generated\Shared\Transfer\TaxProductStorageTransfer
     */
    protected function getStorageEntityData(SpyProductAbstract $productAbstractEntity): TaxProductStorageTransfer
    {
        return (new TaxProductStorageTransfer())
            ->setSku($productAbstractEntity->getSku())
            ->setIdProductAbstract($productAbstractEntity->getIdProductAbstract())
            ->setIdTaxSet($productAbstractEntity->getFkTaxSet());
    }
}
