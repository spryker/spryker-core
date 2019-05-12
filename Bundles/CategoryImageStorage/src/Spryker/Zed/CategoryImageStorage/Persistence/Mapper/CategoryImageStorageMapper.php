<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryImageStorageItemTransfer;
use Generated\Shared\Transfer\CategoryImageTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage;
use Orm\Zed\CategoryImageStorage\Persistence\SpyCategoryImageStorage;

class CategoryImageStorageMapper implements CategoryImageStorageMapperInterface
{
    /**
     * @param \Orm\Zed\CategoryImageStorage\Persistence\SpyCategoryImageStorage $categoryImageStorageEntity
     * @param \Generated\Shared\Transfer\CategoryImageStorageItemTransfer $categoryImageStorageItemTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageStorageItemTransfer
     */
    public function mapCategoryImageStorageEntityToCategoryImageStorageItemTransfer(
        SpyCategoryImageStorage $categoryImageStorageEntity,
        CategoryImageStorageItemTransfer $categoryImageStorageItemTransfer
    ): CategoryImageStorageItemTransfer {
        $categoryImageStorageItemTransfer->fromArray($categoryImageStorageEntity->toArray(), true);

        return $categoryImageStorageItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageStorageItemTransfer $categoryImageStorageItemTransfer
     * @param \Orm\Zed\CategoryImageStorage\Persistence\SpyCategoryImageStorage $categoryImageStorageEntity
     *
     * @return \Orm\Zed\CategoryImageStorage\Persistence\SpyCategoryImageStorage
     */
    public function mapCategoryImageStorageItemTransferToCategoryImageStorageEntity(
        CategoryImageStorageItemTransfer $categoryImageStorageItemTransfer,
        SpyCategoryImageStorage $categoryImageStorageEntity
    ): SpyCategoryImageStorage {
        $categoryImageStorageEntity->fromArray($categoryImageStorageItemTransfer->toArray());
        $categoryImageStorageEntity->setData($categoryImageStorageItemTransfer->getData()->toArray());

        return $categoryImageStorageEntity;
    }

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet $categoryImageSetEntity
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer
     */
    public function mapCategoryImageSetEntityToCategoryImageSetTransfer(
        SpyCategoryImageSet $categoryImageSetEntity,
        CategoryImageSetTransfer $categoryImageSetTransfer
    ): CategoryImageSetTransfer {
        $categoryImageSetTransfer->setName(
            $categoryImageSetEntity->getName()
        );
        $categoryImageSetTransfer->setIdCategory(
            $categoryImageSetEntity->getFkCategory()
        );
        $categoryImageSetTransfer->setIdCategoryImageSet(
            $categoryImageSetEntity->getIdCategoryImageSet()
        );

        if ($categoryImageSetEntity->getSpyLocale() !== null) {
            $categoryImageSetTransfer->setLocale(
                (new LocaleTransfer())->fromArray(
                    $categoryImageSetEntity->getSpyLocale()->toArray()
                )
            );
        }

        $categoryImageTransfers = [];

        foreach ($categoryImageSetEntity->getSpyCategoryImageSetToCategoryImages() as $categoryImageSetToCategoryImageEntity) {
            $categoryImageTransfers[] = $this->mapCategoryImageEntityToCategoryImageTransfer(
                $categoryImageSetToCategoryImageEntity,
                new CategoryImageTransfer()
            );
        }

        $categoryImageSetTransfer->setCategoryImages(new ArrayObject($categoryImageTransfers));

        return $categoryImageSetTransfer;
    }

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage $categoryImageSetToCategoryImageEntity
     * @param \Generated\Shared\Transfer\CategoryImageTransfer $categoryImageTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageTransfer
     */
    public function mapCategoryImageEntityToCategoryImageTransfer(
        SpyCategoryImageSetToCategoryImage $categoryImageSetToCategoryImageEntity,
        CategoryImageTransfer $categoryImageTransfer
    ): CategoryImageTransfer {
        $categoryImageTransfer->fromArray(
            $categoryImageSetToCategoryImageEntity->getSpyCategoryImage()->toArray(),
            true
        );
        $categoryImageTransfer->setSortOrder(
            $categoryImageSetToCategoryImageEntity->getSortOrder()
        );

        return $categoryImageTransfer;
    }
}
