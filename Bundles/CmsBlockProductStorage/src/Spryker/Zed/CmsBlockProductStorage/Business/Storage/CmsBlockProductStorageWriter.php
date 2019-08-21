<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductStorage\Business\Storage;

use Generated\Shared\Transfer\CmsBlockProductTransfer;
use Orm\Zed\CmsBlockProductStorage\Persistence\SpyCmsBlockProductStorage;
use Spryker\Zed\CmsBlockProductStorage\Dependency\Service\CmsBlockProductStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\CmsBlockProductStorage\Persistence\CmsBlockProductStorageQueryContainerInterface;

class CmsBlockProductStorageWriter implements CmsBlockProductStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\CmsBlockProductStorage\Persistence\CmsBlockProductStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\CmsBlockProductStorage\Dependency\Service\CmsBlockProductStorageToUtilSanitizeServiceInterface
     */
    protected $utilSanitizeService;

    /**
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param \Spryker\Zed\CmsBlockProductStorage\Persistence\CmsBlockProductStorageQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\CmsBlockProductStorage\Dependency\Service\CmsBlockProductStorageToUtilSanitizeServiceInterface $utilSanitizeService
     * @param bool $isSendingToQueue
     */
    public function __construct(CmsBlockProductStorageQueryContainerInterface $queryContainer, CmsBlockProductStorageToUtilSanitizeServiceInterface $utilSanitizeService, $isSendingToQueue)
    {
        $this->queryContainer = $queryContainer;
        $this->utilSanitizeService = $utilSanitizeService;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $cmsBlockProductsTransfer = $this->getCmsBlockProductsTransfer($productAbstractIds);
        $spyCmsBlockProductStorageEntities = $this->findCmsBlockProductStorageEntitiesByProductIds($productAbstractIds);
        $this->storeData($cmsBlockProductsTransfer, $spyCmsBlockProductStorageEntities);
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function refreshOrUnpublish(array $productAbstractIds)
    {
        $cmsBlockProductsTransfer = $this->getCmsBlockProductsTransfer($productAbstractIds);
        $spyCmsBlockProductStorageEntities = $this->findCmsBlockProductStorageEntitiesByProductIds($productAbstractIds);

        foreach ($spyCmsBlockProductStorageEntities as $spyCmsBlockProductStorageEntity) {
            if (isset($cmsBlockProductsTransfer[$spyCmsBlockProductStorageEntity->getFkProductAbstract()])) {
                $this->storeData($cmsBlockProductsTransfer, $spyCmsBlockProductStorageEntities);

                continue;
            }

            $spyCmsBlockProductStorageEntity->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockProductTransfer[] $cmsBlockProductsTransfer
     * @param array $spyCmsBlockProductStorageEntities
     *
     * @return void
     */
    protected function storeData(array $cmsBlockProductsTransfer, array $spyCmsBlockProductStorageEntities)
    {
        foreach ($cmsBlockProductsTransfer as $cmsBlockProductTransfer) {
            if (isset($spyCmsBlockProductStorageEntities[$cmsBlockProductTransfer->getIdProductAbstract()])) {
                $this->storeDataSet($cmsBlockProductTransfer, $spyCmsBlockProductStorageEntities[$cmsBlockProductTransfer->getIdProductAbstract()]);

                continue;
            }

            $this->storeDataSet($cmsBlockProductTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockProductTransfer $cmsBlockProductsTransfer
     * @param \Orm\Zed\CmsBlockProductStorage\Persistence\SpyCmsBlockProductStorage|null $spyCmsBlockProductStorage
     *
     * @return void
     */
    protected function storeDataSet(CmsBlockProductTransfer $cmsBlockProductsTransfer, ?SpyCmsBlockProductStorage $spyCmsBlockProductStorage = null)
    {
        if ($spyCmsBlockProductStorage === null) {
            $spyCmsBlockProductStorage = new SpyCmsBlockProductStorage();
        }

        $data = $this->utilSanitizeService->arrayFilterRecursive($cmsBlockProductsTransfer->toArray());
        $spyCmsBlockProductStorage->setFkProductAbstract($cmsBlockProductsTransfer->getIdProductAbstract());
        $spyCmsBlockProductStorage->setData($data);
        $spyCmsBlockProductStorage->setIsSendingToQueue($this->isSendingToQueue);
        $spyCmsBlockProductStorage->save();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function getCmsBlockProductsTransfer(array $productAbstractIds)
    {
        $mappedCmsBlockProducts = $this->getCmsBlockProducts($productAbstractIds);

        $cmsBlockProductsTransfer = [];
        foreach ($mappedCmsBlockProducts as $productAbstractId => $cmsBlockProduct) {
            $cmsBlockProductTransfer = new CmsBlockProductTransfer();
            $cmsBlockProductTransfer->setIdProductAbstract($productAbstractId);
            $cmsBlockProductTransfer->setBlockNames($cmsBlockProduct['names']);
            $cmsBlockProductTransfer->setBlockKeys($cmsBlockProduct['keys']);
            $cmsBlockProductsTransfer[$productAbstractId] = $cmsBlockProductTransfer;
        }

        return $cmsBlockProductsTransfer;
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function getCmsBlockProducts(array $productAbstractIds)
    {
        $cmsBlockProductEntities = $this->queryContainer->queryCmsBlockProducts($productAbstractIds)->find();
        $mappedCmsBlockProducts = [];
        foreach ($cmsBlockProductEntities as $cmsBlockProductEntity) {
            $mappedCmsBlockProducts[$cmsBlockProductEntity->getFkProductAbstract()]['names'][] = $cmsBlockProductEntity->getName();
            $mappedCmsBlockProducts[$cmsBlockProductEntity->getFkProductAbstract()]['keys'][] = $cmsBlockProductEntity->getKey();
        }

        return $mappedCmsBlockProducts;
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findCmsBlockProductStorageEntitiesByProductIds(array $productAbstractIds)
    {
        $cmsBlockProductStorageEntities = $this->queryContainer->queryCmsBlockProductStorageByIds($productAbstractIds)->find();
        $cmsBlockProductStorageEntitiesById = [];
        foreach ($cmsBlockProductStorageEntities as $cmsBlockProductStorageEntity) {
            $cmsBlockProductStorageEntitiesById[$cmsBlockProductStorageEntity->getFkProductAbstract()] = $cmsBlockProductStorageEntity;
        }

        return $cmsBlockProductStorageEntitiesById;
    }
}
