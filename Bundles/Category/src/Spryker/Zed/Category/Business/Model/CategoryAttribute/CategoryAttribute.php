<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model\CategoryAttribute;

use Generated\Shared\Transfer\CategoryTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryAttribute;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;

class CategoryAttribute implements CategoryAttributeInterface
{

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     */
    public function __construct(CategoryQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

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

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function update(CategoryTransfer $categoryTransfer)
    {
        $localizedCategoryAttributeTransferCollection = $categoryTransfer->getLocalizedAttributes();

        foreach ($localizedCategoryAttributeTransferCollection as $localizedCategoryAttributesTransfer) {
            $idCategory = $categoryTransfer->getIdCategory();
            $idLocale = $localizedCategoryAttributesTransfer->getLocale()->getIdLocale();

            $localizedCategoryAttributesEntity = $this
                ->queryContainer
                ->queryAttributeByCategoryId($idCategory)
                ->filterByFkLocale($idLocale)
                ->findOneOrCreate();

            $localizedCategoryAttributesEntity->fromArray($localizedCategoryAttributesTransfer->toArray());
            $localizedCategoryAttributesEntity->setFkCategory($idCategory);
            $localizedCategoryAttributesEntity->setFkLocale($idLocale);
            $localizedCategoryAttributesEntity->save();
        }
    }

}
