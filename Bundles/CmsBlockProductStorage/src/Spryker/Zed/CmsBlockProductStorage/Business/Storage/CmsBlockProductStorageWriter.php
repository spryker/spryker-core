<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductStorage\Business\Storage;

use Generated\Shared\Transfer\CmsBlockProductTransfer;
use Orm\Zed\CmsBlockProductStorage\Persistence\SpyCmsBlockProductStorage;
use Spryker\Zed\CmsBlockProductStorage\Business\CmsBlock\CmsBlockFeatureDetectorInterface;
use Spryker\Zed\CmsBlockProductStorage\Dependency\Service\CmsBlockProductStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\CmsBlockProductStorage\Persistence\CmsBlockProductStorageQueryContainerInterface;

class CmsBlockProductStorageWriter implements CmsBlockProductStorageWriterInterface
{
    protected const KEYS = 'keys';
    protected const NAMES = 'names';
    /**
     * @uses \Spryker\Zed\CmsBlockProductStorage\Persistence\CmsBlockProductStorageQueryContainer::NAME
     */
    protected const COLUMN_BLOCK_NAME = 'name';

    /**
     * @uses \Spryker\Zed\CmsBlockProductStorage\Persistence\CmsBlockProductStorageQueryContainer::BLOCK_KEY
     */
    protected const COLUMN_BLOCK_KEY = 'block_key';

    /**
     * @var \Spryker\Zed\CmsBlockProductStorage\Persistence\CmsBlockProductStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\CmsBlockProductStorage\Dependency\Service\CmsBlockProductStorageToUtilSanitizeServiceInterface
     */
    protected $utilSanitizeService;

    /**
     * @deprecated Use {@link \Spryker\Zed\SynchronizationBehavior\SynchronizationBehaviorConfig::isSynchronizationEnabled()} instead.
     *
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @var \Spryker\Zed\CmsBlockProductStorage\Business\CmsBlock\CmsBlockFeatureDetectorInterface
     */
    protected $cmsBlockFeatureDetector;

    /**
     * @param \Spryker\Zed\CmsBlockProductStorage\Persistence\CmsBlockProductStorageQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\CmsBlockProductStorage\Dependency\Service\CmsBlockProductStorageToUtilSanitizeServiceInterface $utilSanitizeService
     * @param bool $isSendingToQueue
     * @param \Spryker\Zed\CmsBlockProductStorage\Business\CmsBlock\CmsBlockFeatureDetectorInterface $cmsBlockFeatureDetector
     */
    public function __construct(
        CmsBlockProductStorageQueryContainerInterface $queryContainer,
        CmsBlockProductStorageToUtilSanitizeServiceInterface $utilSanitizeService,
        bool $isSendingToQueue,
        CmsBlockFeatureDetectorInterface $cmsBlockFeatureDetector
    ) {
        $this->queryContainer = $queryContainer;
        $this->utilSanitizeService = $utilSanitizeService;
        $this->isSendingToQueue = $isSendingToQueue;
        $this->cmsBlockFeatureDetector = $cmsBlockFeatureDetector;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds): void
    {
        $cmsBlockProductsTransfer = $this->getCmsBlockProductsTransfer($productAbstractIds);
        $spyCmsBlockProductStorageEntities = $this->findCmsBlockProductStorageEntitiesByProductIds($productAbstractIds);
        $this->storeData($cmsBlockProductsTransfer, $spyCmsBlockProductStorageEntities);
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function refreshOrUnpublish(array $productAbstractIds): void
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
    protected function storeData(array $cmsBlockProductsTransfer, array $spyCmsBlockProductStorageEntities): void
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
    protected function storeDataSet(CmsBlockProductTransfer $cmsBlockProductsTransfer, ?SpyCmsBlockProductStorage $spyCmsBlockProductStorage = null): void
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
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    protected function getCmsBlockProductsTransfer(array $productAbstractIds): array
    {
        $mappedCmsBlockProducts = $this->getCmsBlockProducts($productAbstractIds);

        $cmsBlockProductsTransfer = [];
        foreach ($mappedCmsBlockProducts as $productAbstractId => $cmsBlockProduct) {
            $cmsBlockProductTransfer = (new CmsBlockProductTransfer())
                ->setIdProductAbstract($productAbstractId)
                ->setBlockNames($cmsBlockProduct[static::NAMES]);

            if (isset($cmsBlockProduct[static::KEYS])) {
                $cmsBlockProductTransfer->setBlockKeys($cmsBlockProduct[static::KEYS]);
            }

            $cmsBlockProductsTransfer[$productAbstractId] = $cmsBlockProductTransfer;
        }

        return $cmsBlockProductsTransfer;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    protected function getCmsBlockProducts(array $productAbstractIds): array
    {
        $cmsBlockProductEntities = $this->queryContainer->queryCmsBlockProducts($productAbstractIds)->find();
        $mappedCmsBlockProducts = [];
        foreach ($cmsBlockProductEntities as $cmsBlockProductEntity) {
            $mappedCmsBlockProducts[$cmsBlockProductEntity->getFkProductAbstract()][static::NAMES][] =
                $cmsBlockProductEntity->getVirtualColumn(static::COLUMN_BLOCK_NAME);

            if (!$this->cmsBlockFeatureDetector->isCmsBlockKeyPresent()) {
                continue;
            }

            $mappedCmsBlockProducts[$cmsBlockProductEntity->getFkProductAbstract()][static::KEYS][] =
                $cmsBlockProductEntity->getVirtualColumn(static::COLUMN_BLOCK_KEY);
        }

        return $mappedCmsBlockProducts;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    protected function findCmsBlockProductStorageEntitiesByProductIds(array $productAbstractIds): array
    {
        $cmsBlockProductStorageEntities = $this->queryContainer->queryCmsBlockProductStorageByIds($productAbstractIds)->find();
        $cmsBlockProductStorageEntitiesById = [];
        foreach ($cmsBlockProductStorageEntities as $cmsBlockProductStorageEntity) {
            $cmsBlockProductStorageEntitiesById[$cmsBlockProductStorageEntity->getFkProductAbstract()] = $cmsBlockProductStorageEntity;
        }

        return $cmsBlockProductStorageEntitiesById;
    }
}
