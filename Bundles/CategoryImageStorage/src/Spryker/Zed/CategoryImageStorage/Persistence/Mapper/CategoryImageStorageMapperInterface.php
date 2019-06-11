<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Persistence\Mapper;

use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryImageStorageItemTransfer;
use Generated\Shared\Transfer\CategoryImageTransfer;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage;
use Orm\Zed\CategoryImageStorage\Persistence\SpyCategoryImageStorage;

interface CategoryImageStorageMapperInterface
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
    ): CategoryImageStorageItemTransfer;

    /**
     * @param \Generated\Shared\Transfer\CategoryImageStorageItemTransfer $categoryImageStorageItemItemTransfer
     * @param \Orm\Zed\CategoryImageStorage\Persistence\SpyCategoryImageStorage $categoryImageStorageEntity
     *
     * @return \Orm\Zed\CategoryImageStorage\Persistence\SpyCategoryImageStorage
     */
    public function mapCategoryImageStorageItemTransferToCategoryImageStorageEntity(
        CategoryImageStorageItemTransfer $categoryImageStorageItemItemTransfer,
        SpyCategoryImageStorage $categoryImageStorageEntity
    ): SpyCategoryImageStorage;

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet $categoryImageSetEntity
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer
     */
    public function mapCategoryImageSetEntityToCategoryImageSetTransfer(
        SpyCategoryImageSet $categoryImageSetEntity,
        CategoryImageSetTransfer $categoryImageSetTransfer
    ): CategoryImageSetTransfer;

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImage $categoryImageSetToCategoryImageEntity
     * @param \Generated\Shared\Transfer\CategoryImageTransfer $categoryImageTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryImageTransfer
     */
    public function mapCategoryImageEntityToCategoryImageTransfer(
        SpyCategoryImageSetToCategoryImage $categoryImageSetToCategoryImageEntity,
        CategoryImageTransfer $categoryImageTransfer
    ): CategoryImageTransfer;
}
