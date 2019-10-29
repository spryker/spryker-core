<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Business\Storage;

use Generated\Shared\Transfer\CmsBlockCategoriesTransfer;
use Generated\Shared\Transfer\CmsBlockCategoryTransfer;
use Orm\Zed\CmsBlockCategoryStorage\Persistence\SpyCmsBlockCategoryStorage;
use Spryker\Zed\CmsBlockCategoryStorage\Business\CmsBlock\CmsBlockFeatureDetectorInterface;
use Spryker\Zed\CmsBlockCategoryStorage\Dependency\Service\CmsBlockCategoryStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStorageQueryContainerInterface;

class CmsBlockCategoryStorageWriter implements CmsBlockCategoryStorageWriterInterface
{
    protected const KEYS = 'keys';
    protected const NAMES = 'names';
    /**
     * @uses \Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStorageQueryContainer::NAME
     */
    protected const COLUMN_BLOCK_NAME = 'name';

    /**
     * @uses \Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStorageQueryContainer::KEY
     */
    protected const COLUMN_BLOCK_KEY = 'key';

    /**
     * @var \Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\CmsBlockCategoryStorage\Dependency\Service\CmsBlockCategoryStorageToUtilSanitizeServiceInterface
     */
    protected $utilSanitizeService;

    /**
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @var \Spryker\Zed\CmsBlockCategoryStorage\Business\CmsBlock\CmsBlockFeatureDetectorInterface
     */
    protected $cmsBlockFeatureDetector;

    /**
     * @param \Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStorageQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\CmsBlockCategoryStorage\Dependency\Service\CmsBlockCategoryStorageToUtilSanitizeServiceInterface $utilSanitizeService
     * @param bool $isSendingToQueue
     * @param \Spryker\Zed\CmsBlockCategoryStorage\Business\CmsBlock\CmsBlockFeatureDetectorInterface $cmsBlockFeatureDetector
     */
    public function __construct(
        CmsBlockCategoryStorageQueryContainerInterface $queryContainer,
        CmsBlockCategoryStorageToUtilSanitizeServiceInterface $utilSanitizeService,
        $isSendingToQueue,
        CmsBlockFeatureDetectorInterface $cmsBlockFeatureDetector
    ) {
        $this->queryContainer = $queryContainer;
        $this->utilSanitizeService = $utilSanitizeService;
        $this->isSendingToQueue = $isSendingToQueue;
        $this->cmsBlockFeatureDetector = $cmsBlockFeatureDetector;
    }

    /**
     * @param array $categoryIds
     *
     * @return void
     */
    public function publish(array $categoryIds): void
    {
        $cmsBlockCategoriesTransfer = $this->getCmsBlockCategoriesTransfer($categoryIds);
        $spyCmsBlockCategoryStorageEntities = $this->findCmsBlockCategoryStorageEntitiesByCategoryIds($categoryIds);
        $this->storeData($cmsBlockCategoriesTransfer, $spyCmsBlockCategoryStorageEntities);
    }

    /**
     * @param array $categoryIds
     *
     * @return void
     */
    public function refreshOrUnpublish(array $categoryIds): void
    {
        $cmsBlockCategoriesTransfer = $this->getCmsBlockCategoriesTransfer($categoryIds);
        $spyCmsBlockCategoryStorageEntities = $this->findCmsBlockCategoryStorageEntitiesByCategoryIds($categoryIds);

        foreach ($spyCmsBlockCategoryStorageEntities as $spyCmsBlockCategoryStorageEntity) {
            if (isset($cmsBlockCategoriesTransfer[$spyCmsBlockCategoryStorageEntity->getFkCategory()])) {
                $this->storeData($cmsBlockCategoriesTransfer, $spyCmsBlockCategoryStorageEntities);

                continue;
            }

            $spyCmsBlockCategoryStorageEntity->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockCategoriesTransfer[] $cmsBlockCategoriesTransfer
     * @param array $spyCmsBlockCategoryStorageEntities
     *
     * @return void
     */
    protected function storeData(array $cmsBlockCategoriesTransfer, array $spyCmsBlockCategoryStorageEntities): void
    {
        foreach ($cmsBlockCategoriesTransfer as $cmsBlockCategoryTransfer) {
            if (isset($spyCmsBlockCategoryStorageEntities[$cmsBlockCategoryTransfer->getIdCategory()])) {
                $this->storeDataSet($cmsBlockCategoryTransfer, $spyCmsBlockCategoryStorageEntities[$cmsBlockCategoryTransfer->getIdCategory()]);

                continue;
            }

            $this->storeDataSet($cmsBlockCategoryTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockCategoriesTransfer $cmsBlockCategoriesTransfer
     * @param \Orm\Zed\CmsBlockCategoryStorage\Persistence\SpyCmsBlockCategoryStorage|null $spyCmsBlockCategoryStorage
     *
     * @return void
     */
    protected function storeDataSet(CmsBlockCategoriesTransfer $cmsBlockCategoriesTransfer, ?SpyCmsBlockCategoryStorage $spyCmsBlockCategoryStorage = null): void
    {
        if ($spyCmsBlockCategoryStorage === null) {
            $spyCmsBlockCategoryStorage = new SpyCmsBlockCategoryStorage();
        }

        $data = $this->utilSanitizeService->arrayFilterRecursive($cmsBlockCategoriesTransfer->toArray());
        $spyCmsBlockCategoryStorage->setFkCategory($cmsBlockCategoriesTransfer->getIdCategory());
        $spyCmsBlockCategoryStorage->setData($data);
        $spyCmsBlockCategoryStorage->setIsSendingToQueue($this->isSendingToQueue);
        $spyCmsBlockCategoryStorage->save();
    }

    /**
     * @param array $categoryIds
     *
     * @return array
     */
    protected function getCmsBlockCategoriesTransfer(array $categoryIds): array
    {
        $cmsBlocksGroupedByCategoryPosition = $this->getCmsBlocksGroupedByCategoryPosition($categoryIds);

        $cmsBlockCategoriesTransfer = [];
        foreach ($cmsBlocksGroupedByCategoryPosition as $idCategory => $cmsBlockPositions) {
            $cmsBlockCategoriesTransfer = new CmsBlockCategoriesTransfer();
            $cmsBlockCategoriesTransfer->setIdCategory($idCategory);
            foreach ($cmsBlockPositions as $position => $cmsBlocks) {
                $cmsBlockCategoryTransfer = (new CmsBlockCategoryTransfer())
                    ->setPosition($position)
                    ->setBlockNames($cmsBlocks[static::NAMES])
                    ->setBlockKeys($cmsBlocks[static::KEYS] ?? null);

                $cmsBlockCategoriesTransfer->addCmsBlockCategory($cmsBlockCategoryTransfer);
            }

            $cmsBlockCategoriesTransfer[$idCategory] = $cmsBlockCategoriesTransfer;
        }

        return $cmsBlockCategoriesTransfer;
    }

    /**
     * @param array $categoryIds
     *
     * @return array
     */
    protected function getCmsBlocksGroupedByCategoryPosition(array $categoryIds): array
    {
        $cmsBlockCategoryEntities = $this->queryContainer->queryCmsBlockCategories($categoryIds)->find();
        $mappedCmsBlockCategories = [];
        foreach ($cmsBlockCategoryEntities as $cmsBlockCategoryEntity) {
            $mappedCmsBlockCategories[$cmsBlockCategoryEntity->getFkCategory()][$cmsBlockCategoryEntity->getPosition()][static::NAMES][] =
                $cmsBlockCategoryEntity->getVirtualColumn(static::COLUMN_BLOCK_NAME);

            if (!$this->cmsBlockFeatureDetector->isCmsBlockKeyPresent()) {
                continue;
            }

            $mappedCmsBlockCategories[$cmsBlockCategoryEntity->getFkCategory()][$cmsBlockCategoryEntity->getPosition()][static::KEYS][] =
                $cmsBlockCategoryEntity->getVirtualColumn(static::COLUMN_BLOCK_KEY);
        }

        return $mappedCmsBlockCategories;
    }

    /**
     * @param array $categoryIds
     *
     * @return array
     */
    protected function findCmsBlockCategoryStorageEntitiesByCategoryIds(array $categoryIds): array
    {
        $cmsBlockCategoryStorageEntities = $this->queryContainer->queryCmsBlockCategoryStorageByIds($categoryIds)->find();
        $cmsBlockCategoryStorageEntitiesById = [];
        foreach ($cmsBlockCategoryStorageEntities as $cmsBlockCategoryStorageEntity) {
            $cmsBlockCategoryStorageEntitiesById[$cmsBlockCategoryStorageEntity->getFkCategory()] = $cmsBlockCategoryStorageEntity;
        }

        return $cmsBlockCategoryStorageEntitiesById;
    }
}
