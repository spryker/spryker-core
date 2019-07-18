<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryImageTransfer;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImage;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet;
use Propel\Runtime\Collection\ObjectCollection;

interface CategoryImageMapperInterface
{
    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet[]|\Propel\Runtime\Collection\ObjectCollection $categoryImageSetEntityCollection
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function mapCategoryImageSetCollection(ObjectCollection $categoryImageSetEntityCollection): array;

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet $categoryImageSetEntity
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer
     */
    public function mapCategoryImageSet(SpyCategoryImageSet $categoryImageSetEntity): CategoryImageSetTransfer;

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImage[]|\Propel\Runtime\Collection\ObjectCollection $categoryImageEntityCollection
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet|null $categoryImageSetEntity
     *
     * @return \Generated\Shared\Transfer\CategoryImageTransfer[]
     */
    public function mapCategoryImageCollection(ObjectCollection $categoryImageEntityCollection, ?SpyCategoryImageSet $categoryImageSetEntity): array;

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImage $categoryImageEntity
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet $categoryImageSetEntity
     *
     * @return \Generated\Shared\Transfer\CategoryImageTransfer
     */
    public function mapCategoryImage(SpyCategoryImage $categoryImageEntity, SpyCategoryImageSet $categoryImageSetEntity): CategoryImageTransfer;

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImage $categoryImageEntity
     * @param \Generated\Shared\Transfer\CategoryImageTransfer $categoryImageTransfer
     *
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImage
     */
    public function mapCategoryImageToEntity(SpyCategoryImage $categoryImageEntity, CategoryImageTransfer $categoryImageTransfer): SpyCategoryImage;

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet $categoryImageSetEntity
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet
     */
    public function mapCategoryImageSetToEntity(SpyCategoryImageSet $categoryImageSetEntity, CategoryImageSetTransfer $categoryImageSetTransfer): SpyCategoryImageSet;
}
