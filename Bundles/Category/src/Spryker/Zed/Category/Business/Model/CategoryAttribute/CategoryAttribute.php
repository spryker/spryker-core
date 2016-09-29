<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model\CategoryAttribute;

use Generated\Shared\Transfer\CategoryTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryAttribute;

class CategoryAttribute implements CategoryAttributeInterface
{

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function create(CategoryTransfer $categoryTransfer)
    {
        $localizedCategoryAttributeTransferCollection = $categoryTransfer->getLocalizedAttributes();

        foreach ($localizedCategoryAttributeTransferCollection as $localizedCategoryAttributesTransfer) {
            $localizedCategoryAttributeEntity = new SpyCategoryAttribute();
            $localizedCategoryAttributeEntity->fromArray($localizedCategoryAttributesTransfer->toArray());
            $localizedCategoryAttributeEntity->setFkCategory($categoryTransfer->getIdCategory());
            $localizedCategoryAttributeEntity->setFkLocale($localizedCategoryAttributesTransfer->getLocale()->getIdLocale());

            $localizedCategoryAttributeEntity->save();
        }
    }

}
