<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CategoryTemplateTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryTemplate;

class CategoryTemplateMapper
{
    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryTemplate $categoryTemplateEntity
     * @param \Generated\Shared\Transfer\CategoryTemplateTransfer $categoryTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTemplateTransfer
     */
    public function mapCategoryTemplateEntityToCategoryTemplateTransfer(
        SpyCategoryTemplate $categoryTemplateEntity,
        CategoryTemplateTransfer $categoryTemplateTransfer
    ): CategoryTemplateTransfer {
        return $categoryTemplateTransfer->fromArray($categoryTemplateEntity->toArray(), true);
    }
}
