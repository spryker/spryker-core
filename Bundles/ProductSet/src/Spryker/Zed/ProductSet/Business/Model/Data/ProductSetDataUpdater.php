<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model\Data;

use Generated\Shared\Transfer\LocalizedProductSetTransfer;
use Generated\Shared\Transfer\ProductSetDataTransfer;
use Orm\Zed\ProductSet\Persistence\SpyProductSetData;
use Spryker\Zed\ProductSet\Business\Model\Data\Url\ProductSetUrlUpdaterInterface;
use Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductSetDataUpdater implements ProductSetDataUpdaterInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface
     */
    protected $productSetQueryContainer;

    /**
     * @var \Spryker\Zed\ProductSet\Business\Model\Data\Url\ProductSetUrlUpdaterInterface
     */
    protected $productSetUrlUpdater;

    /**
     * @param \Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface $productSetQueryContainer
     * @param \Spryker\Zed\ProductSet\Business\Model\Data\Url\ProductSetUrlUpdaterInterface $productSetUrlUpdater
     */
    public function __construct(ProductSetQueryContainerInterface $productSetQueryContainer, ProductSetUrlUpdaterInterface $productSetUrlUpdater)
    {
        $this->productSetQueryContainer = $productSetQueryContainer;
        $this->productSetUrlUpdater = $productSetUrlUpdater;
    }

    /**
     * @param \Generated\Shared\Transfer\LocalizedProductSetTransfer $localizedProductSetTransfer
     * @param int $idProductSet
     *
     * @return \Generated\Shared\Transfer\LocalizedProductSetTransfer
     */
    public function updateProductSetData(LocalizedProductSetTransfer $localizedProductSetTransfer, $idProductSet)
    {
        return $this->handleDatabaseTransaction(function () use ($localizedProductSetTransfer, $idProductSet) {
            return $this->executeUpdateProductSetDataTransaction($localizedProductSetTransfer, $idProductSet);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\LocalizedProductSetTransfer $localizedProductSetTransfer
     * @param int $idProductSet
     *
     * @return \Generated\Shared\Transfer\LocalizedProductSetTransfer
     */
    protected function executeUpdateProductSetDataTransaction(LocalizedProductSetTransfer $localizedProductSetTransfer, $idProductSet)
    {
        $this->assertLocalizedProductSetForUpdate($localizedProductSetTransfer);

        $productSetDataTransfer = $this->getProductSetDataTransfer($localizedProductSetTransfer, $idProductSet);
        $productSetDataTransfer = $this->updateProductSetDataEntity($productSetDataTransfer);
        $localizedProductSetTransfer->setProductSetData($productSetDataTransfer);

        $localizedProductSetTransfer = $this->productSetUrlUpdater->updateUrl($localizedProductSetTransfer, $idProductSet);

        return $localizedProductSetTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LocalizedProductSetTransfer $localizedProductSetTransfer
     *
     * @return void
     */
    protected function assertLocalizedProductSetForUpdate(LocalizedProductSetTransfer $localizedProductSetTransfer)
    {
        $localizedProductSetTransfer
            ->requireLocale()
            ->requireProductSetData();

        $localizedProductSetTransfer->getLocale()->requireIdLocale();
    }

    /**
     * @param \Generated\Shared\Transfer\LocalizedProductSetTransfer $localizedProductSetTransfer
     * @param int $idProductSet
     *
     * @return \Generated\Shared\Transfer\ProductSetDataTransfer
     */
    protected function getProductSetDataTransfer(LocalizedProductSetTransfer $localizedProductSetTransfer, $idProductSet)
    {
        $productSetDataTransfer = $localizedProductSetTransfer->getProductSetData();
        $productSetDataTransfer
            ->setFkProductSet($idProductSet)
            ->setFkLocale($localizedProductSetTransfer->getLocale()->getIdLocale());

        return $productSetDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetDataTransfer $productSetDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetDataTransfer
     */
    protected function updateProductSetDataEntity(ProductSetDataTransfer $productSetDataTransfer)
    {
        $productSetDataEntity = $this->productSetQueryContainer
            ->queryProductSetDataByProductSetId($productSetDataTransfer->getFkProductSet(), $productSetDataTransfer->getFkLocale())
            ->findOneOrCreate();

        $productSetDataEntity = $this->mapProductSetData($productSetDataTransfer, $productSetDataEntity);
        $productSetDataEntity->save();

        return $this->mapProductSetDataEntityToTransfer($productSetDataEntity, $productSetDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetDataTransfer $productSetDataTransfer
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSetData $productSetDataEntity
     *
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductSetData
     */
    protected function mapProductSetData(ProductSetDataTransfer $productSetDataTransfer, SpyProductSetData $productSetDataEntity)
    {
        $productSetDataEntity->fromArray($productSetDataTransfer->modifiedToArray());

        return $productSetDataEntity;
    }

    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSetData $productSetDataEntity
     * @param \Generated\Shared\Transfer\ProductSetDataTransfer $productSetDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetDataTransfer
     */
    protected function mapProductSetDataEntityToTransfer(SpyProductSetData $productSetDataEntity, ProductSetDataTransfer $productSetDataTransfer)
    {
        $productSetDataTransfer->fromArray($productSetDataEntity->toArray(), true);

        return $productSetDataTransfer;
    }
}
