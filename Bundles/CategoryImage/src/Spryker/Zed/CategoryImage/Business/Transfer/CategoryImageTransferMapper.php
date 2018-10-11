<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business\Transfer;

use ArrayObject;
use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryImageTransfer;
use Orm\Zed\CategoryImage\Persistence\Map\SpyCategoryImageSetToCategoryImageTableMap;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImage;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\CategoryImage\Dependency\Facade\CategoryImageToLocaleInterface;

class CategoryImageTransferMapper implements CategoryImageTransferMapperInterface
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
        $this->setCategoryImages($categoryImageSetEntity, $categoryImageSetTransfer);

        return $categoryImageSetTransfer;
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
    protected function setCategoryImages(SpyCategoryImageSet $categoryImageSetEntity, CategoryImageSetTransfer $categoryImageSetTransfer)
    {
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(SpyCategoryImageSetToCategoryImageTableMap::COL_SORT_ORDER);

        $imageEntityCollection = [];
        foreach ($categoryImageSetEntity->getSpyCategoryImageSetToCategoryImagesJoinSpyCategoryImage($criteria) as $entity) {
            $imageEntityCollection[] = $entity->getSpyCategoryImage();
        }

        $imageTransferCollection = $this->mapCategoryImageCollection(new ObjectCollection($imageEntityCollection), $categoryImageSetEntity);
        $categoryImageSetTransfer->setCategoryImages(new ArrayObject($imageTransferCollection));
    }

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImage[]|\Propel\Runtime\Collection\ObjectCollection $categoryImageEntityCollection
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet $categoryImageSetEntity
     *
     * @return \Generated\Shared\Transfer\CategoryImageTransfer[]
     */
    public function mapCategoryImageCollection(ObjectCollection $categoryImageEntityCollection, SpyCategoryImageSet $categoryImageSetEntity): array
    {
        $transferList = [];
        foreach ($categoryImageEntityCollection as $categoryImageEntity) {
            $categoryImageTransfer = $this->mapCategoryImage($categoryImageEntity);

            $categoryImageSetToCategoryImageEntity = $this->getCategoryImageSetToCategoryImageEntity($categoryImageSetEntity, $categoryImageEntity);

            $categoryImageTransfer->setSortOrder($categoryImageSetToCategoryImageEntity->getSortOrder());

            $transferList[] = $categoryImageTransfer;
        }

        return $transferList;
    }

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImage $categoryImageEntity
     *
     * @return \Generated\Shared\Transfer\CategoryImageTransfer
     */
    public function mapCategoryImage(SpyCategoryImage $categoryImageEntity): CategoryImageTransfer
    {
        $productImageTransfer = (new CategoryImageTransfer())
            ->fromArray($categoryImageEntity->toArray(), true);

        return $productImageTransfer;
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
}
