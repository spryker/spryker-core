<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business\Transfer;

use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryImageTransfer;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImage;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet;
use Propel\Runtime\Collection\ObjectCollection;

interface CategoryImageTransferMapperInterface
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
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet $categoryImageSetEntity
     *
     * @return \Generated\Shared\Transfer\CategoryImageTransfer[]
     */
    public function mapCategoryImageCollection(ObjectCollection $categoryImageEntityCollection, SpyCategoryImageSet $categoryImageSetEntity): array;

    /**
     * @param \Orm\Zed\CategoryImage\Persistence\SpyCategoryImage $categoryImageEntity
     *
     * @return \Generated\Shared\Transfer\CategoryImageTransfer
     */
    public function mapCategoryImage(SpyCategoryImage $categoryImageEntity): CategoryImageTransfer;
}
