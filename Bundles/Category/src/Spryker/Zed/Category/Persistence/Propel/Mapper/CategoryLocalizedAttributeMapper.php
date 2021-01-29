<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryAttribute;

class CategoryLocalizedAttributeMapper
{
    /**
     * @param \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer
     * @param \Orm\Zed\Category\Persistence\SpyCategoryAttribute $categoryAttributeEntity
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttribute
     */
    public function mapCategoryLocalizedAttributeTransferToCategoryAttributeEntity(
        CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer,
        SpyCategoryAttribute $categoryAttributeEntity
    ): SpyCategoryAttribute {
        $categoryAttributeEntity->fromArray($categoryLocalizedAttributesTransfer->modifiedToArray());
        $categoryAttributeEntity->setFkLocale($categoryLocalizedAttributesTransfer->getLocaleOrFail()->getIdLocaleOrFail());

        return $categoryAttributeEntity;
    }
}
