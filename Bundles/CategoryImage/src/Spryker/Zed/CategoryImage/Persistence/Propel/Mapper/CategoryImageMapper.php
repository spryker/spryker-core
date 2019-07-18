<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryImageTransfer;
use Orm\Zed\CategoryImage\Persistence\Map\SpyCategoryImageSetToCategoryImageTableMap;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImage;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\CategoryImage\Dependency\Facade\CategoryImageToLocaleInterface;

class CategoryImageMapper implements CategoryImageMapperInterface
{
    /**
     * @var \Spryker\Zed\CategoryImage\Dependency\Facade\CategoryImageToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\CategoryImage\Dependency\Facade\CategoryImageToLocaleInterface $localeFacade
     */
    public function __construct(CategoryImageToLocaleInterface $localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet[]|\Propel\Runtime\Collection\ObjectCollection $categoryImageSetEntityCollection
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function mapCategoryImageSetCollection(ObjectCollection $categoryImageSetEntityCollection): array
    {
        $transferList = [];
        foreach ($categoryImageSetEntityCollection as $categoryImageSetEntity) {
            $transferList[] = $this->mapCategoryImageSet($categoryImageSetEntity);
        }

        return $transferList;
    }

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet $categoryImageSetEntity
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer
     */
    public function mapCategoryImageSet(SpyCategoryImageSet $categoryImageSetEntity): CategoryImageSetTransfer
    {
        $categoryImageSetTransfer = (new CategoryImageSetTransfer())
            ->fromArray($categoryImageSetEntity->toArray(), true)
            ->setIdCategory($categoryImageSetEntity->getFkCategory());

        $this->setCategoryImageSetLocale($categoryImageSetEntity, $categoryImageSetTransfer);
        $this->hydrateCategoryImages($categoryImageSetEntity, $categoryImageSetTransfer);

        return $categoryImageSetTransfer;
    }

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImage[]|\Propel\Runtime\Collection\ObjectCollection $categoryImageEntityCollection
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet|null $categoryImageSetEntity
     *
     * @return \Generated\Shared\Transfer\CategoryImageTransfer[]
     */
    public function mapCategoryImageCollection(ObjectCollection $categoryImageEntityCollection, ?SpyCategoryImageSet $categoryImageSetEntity): array
    {
        $transferList = [];

        if ($categoryImageSetEntity !== null) {
            foreach ($categoryImageEntityCollection as $categoryImageEntity) {
                $transferList[] = $this->mapCategoryImage($categoryImageEntity, $categoryImageSetEntity);
            }
        }

        return $transferList;
    }

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImage $categoryImageEntity
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet $categoryImageSetEntity
     *
     * @return \Generated\Shared\Transfer\CategoryImageTransfer
     */
    public function mapCategoryImage(SpyCategoryImage $categoryImageEntity, SpyCategoryImageSet $categoryImageSetEntity): CategoryImageTransfer
    {
        $categoryImageTransfer = (new CategoryImageTransfer())
            ->fromArray($categoryImageEntity->toArray(), true);

        $categoryImageSetToCategoryImageEntity = $this->getCategoryImageSetToCategoryImageEntity($categoryImageSetEntity, $categoryImageEntity);
        $categoryImageTransfer->setSortOrder($categoryImageSetToCategoryImageEntity->getSortOrder());
        $categoryImageTransfer->setIdCategoryImageSetToCategoryImage(
            $categoryImageSetToCategoryImageEntity->getIdCategoryImageSetToCategoryImage()
        );

        return $categoryImageTransfer;
    }

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImage $categoryImageEntity
     * @param \Generated\Shared\Transfer\CategoryImageTransfer $categoryImageTransfer
     *
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImage
     */
    public function mapCategoryImageToEntity(
        SpyCategoryImage $categoryImageEntity,
        CategoryImageTransfer $categoryImageTransfer
    ): SpyCategoryImage {
        $categoryImageEntity->fromArray(
            $categoryImageTransfer->modifiedToArray(false)
        );

        return $categoryImageEntity;
    }

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet $categoryImageSetEntity
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet
     */
    public function mapCategoryImageSetToEntity(
        SpyCategoryImageSet $categoryImageSetEntity,
        CategoryImageSetTransfer $categoryImageSetTransfer
    ): SpyCategoryImageSet {
        $categoryImageSetEntity->fromArray($categoryImageSetTransfer->toArray());
        $categoryImageSetEntity->setFkCategory($categoryImageSetTransfer->getIdCategory());

        if ($categoryImageSetTransfer->getLocale()) {
            $categoryImageSetEntity->setFkLocale($categoryImageSetTransfer->getLocale()->getIdLocale());
        }

        return $categoryImageSetEntity;
    }

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet $categoryImageSetEntity
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImage $categoryImageEntity
     *
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage
     */
    protected function getCategoryImageSetToCategoryImageEntity(SpyCategoryImageSet $categoryImageSetEntity, SpyCategoryImage $categoryImageEntity)
    {
        $criteria = new Criteria();
        $criteria->add(SpyCategoryImageSetToCategoryImageTableMap::COL_FK_CATEGORY_IMAGE_SET, $categoryImageSetEntity->getIdCategoryImageSet());

        $categoryImageSetToCategoryImageEntity = $categoryImageEntity
            ->getSpyCategoryImageSetToCategoryImages($criteria)
            ->getFirst();

        return $categoryImageSetToCategoryImageEntity;
    }

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet $categoryImageSetEntity
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return void
     */
    protected function setCategoryImageSetLocale(SpyCategoryImageSet $categoryImageSetEntity, CategoryImageSetTransfer $categoryImageSetTransfer)
    {
        $fkLocale = $categoryImageSetEntity->getFkLocale();

        if ($fkLocale > 0) {
            $localeTransfer = $this->localeFacade->getLocaleById($fkLocale);
            $categoryImageSetTransfer->setLocale($localeTransfer);
        }
    }

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet $categoryImageSetEntity
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return void
     */
    protected function hydrateCategoryImages(SpyCategoryImageSet $categoryImageSetEntity, CategoryImageSetTransfer $categoryImageSetTransfer)
    {
        $criteria = $this->getCategoryImageSetToCategoryImageCriteria();

        $imageEntityCollection = [];
        foreach ($categoryImageSetEntity->getSpyCategoryImageSetToCategoryImagesJoinSpyCategoryImage($criteria) as $entity) {
            $imageEntityCollection[] = $entity->getSpyCategoryImage();
        }

        $imageTransferCollection = $this->mapCategoryImageCollection(new ObjectCollection($imageEntityCollection), $categoryImageSetEntity);
        $categoryImageSetTransfer->setCategoryImages(new ArrayObject($imageTransferCollection));
    }

    /**
     * @return \Propel\Runtime\ActiveQuery\Criteria
     */
    protected function getCategoryImageSetToCategoryImageCriteria(): Criteria
    {
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(SpyCategoryImageSetToCategoryImageTableMap::COL_SORT_ORDER);
        $criteria->addAscendingOrderByColumn(SpyCategoryImageSetToCategoryImageTableMap::COL_ID_CATEGORY_IMAGE_SET_TO_CATEGORY_IMAGE);

        return $criteria;
    }
}
