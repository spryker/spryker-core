<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Business\Storage;

use Generated\Shared\Transfer\CmsBlockCategoriesTransfer;
use Generated\Shared\Transfer\CmsBlockCategoryTransfer;
use Orm\Zed\CmsBlockCategoryStorage\Persistence\SpyCmsBlockCategoryStorage;
use Spryker\Zed\CmsBlockCategoryStorage\Dependency\Service\CmsBlockCategoryStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStorageQueryContainerInterface;

class CmsBlockCategoryStorageWriter implements CmsBlockCategoryStorageWriterInterface
{
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
     * @param \Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStorageQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\CmsBlockCategoryStorage\Dependency\Service\CmsBlockCategoryStorageToUtilSanitizeServiceInterface $utilSanitizeService
     * @param bool $isSendingToQueue
     */
    public function __construct(CmsBlockCategoryStorageQueryContainerInterface $queryContainer, CmsBlockCategoryStorageToUtilSanitizeServiceInterface $utilSanitizeService, $isSendingToQueue)
    {
        $this->queryContainer = $queryContainer;
        $this->utilSanitizeService = $utilSanitizeService;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param array $categoryIds
     *
     * @return void
     */
    public function publish(array $categoryIds)
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
    public function refreshOrUnpublish(array $categoryIds)
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
    protected function storeData(array $cmsBlockCategoriesTransfer, array $spyCmsBlockCategoryStorageEntities)
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
    protected function storeDataSet(CmsBlockCategoriesTransfer $cmsBlockCategoriesTransfer, ?SpyCmsBlockCategoryStorage $spyCmsBlockCategoryStorage = null)
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
    protected function getCmsBlockCategoriesTransfer(array $categoryIds)
    {
        $mappedCmsBlockCategories = $this->getCmsBlockCategories($categoryIds);

        $cmsBlockCategoriesTransfer = [];
        foreach ($mappedCmsBlockCategories as $categoryId => $mappedCmsBlockCategoryPositions) {
            $cmsBlockCategoryTransfer = new CmsBlockCategoriesTransfer();
            $cmsBlockCategoryTransfer->setIdCategory($categoryId);
            foreach ($mappedCmsBlockCategoryPositions as $position => $blockNames) {
                $cmsBlockPositionTransfer = new CmsBlockCategoryTransfer();
                $cmsBlockPositionTransfer->setPosition($position);
                $cmsBlockPositionTransfer->setBlockNames($blockNames);
                $cmsBlockCategoryTransfer->addCmsBlockCategory($cmsBlockPositionTransfer);
            }
            $cmsBlockCategoriesTransfer[$categoryId] = $cmsBlockCategoryTransfer;
        }

        return $cmsBlockCategoriesTransfer;
    }

    /**
     * @param array $categoryIds
     *
     * @return array
     */
    protected function getCmsBlockCategories(array $categoryIds)
    {
        $cmsBlockCategories = $this->queryContainer->queryCmsBlockCategories($categoryIds)->find();
        $mappedCmsBlockCategories = [];
        foreach ($cmsBlockCategories as $cmsBlockCategory) {
            $mappedCmsBlockCategories[$cmsBlockCategory->getFkCategory()][$cmsBlockCategory->getPosition()][] = $cmsBlockCategory->getName();
        }

        return $mappedCmsBlockCategories;
    }

    /**
     * @param array $categoryIds
     *
     * @return array
     */
    protected function findCmsBlockCategoryStorageEntitiesByCategoryIds(array $categoryIds)
    {
        $cmsBlockCategoryStorageEntities = $this->queryContainer->queryCmsBlockCategoryStorageByIds($categoryIds)->find();
        $cmsBlockCategoryStorageEntitiesById = [];
        foreach ($cmsBlockCategoryStorageEntities as $cmsBlockCategoryStorageEntity) {
            $cmsBlockCategoryStorageEntitiesById[$cmsBlockCategoryStorageEntity->getFkCategory()] = $cmsBlockCategoryStorageEntity;
        }

        return $cmsBlockCategoryStorageEntitiesById;
    }
}
