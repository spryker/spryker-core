<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model\CategoryAttribute;

use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
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
        $localizedAttributesTransferCollection = $categoryTransfer->getLocalizedAttributes();

        foreach ($localizedAttributesTransferCollection as $localizedAttributesTransfer) {
            $localizedCategoryAttributeEntity = new SpyCategoryAttribute();

            $localizedCategoryAttributeEntity = $this->updateEntity(
                $localizedCategoryAttributeEntity,
                $localizedAttributesTransfer,
                $categoryTransfer->getIdCategory()
            );

            $localizedCategoryAttributeEntity->save();
        }
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryAttribute $categoryAttributeEntity
     * @param \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $localizedAttributesTransfer
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttribute
     */
    protected function updateEntity(
        SpyCategoryAttribute $categoryAttributeEntity,
        CategoryLocalizedAttributesTransfer $localizedAttributesTransfer,
        $idCategory
    ) {
        $categoryAttributeEntity->fromArray($localizedAttributesTransfer->toArray());
        $categoryAttributeEntity->setFkCategory($idCategory);

        $localeTransfer = $localizedAttributesTransfer->requireLocale()->getLocale();
        $idLocale = $localeTransfer->requireIdLocale()->getIdLocale();
        $categoryAttributeEntity->setFkLocale($idLocale);

        return $categoryAttributeEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function update(CategoryTransfer $categoryTransfer)
    {
        $idCategory = $categoryTransfer->requireIdCategory()->getIdCategory();
        $localizedAttributesTransferCollection = $categoryTransfer->getLocalizedAttributes();

        foreach ($localizedAttributesTransferCollection as $localizedAttributesTransfer) {
            $localeTransfer = $localizedAttributesTransfer->requireLocale()->getLocale();
            $idLocale = $localeTransfer->requireIdLocale()->getIdLocale();

            $localizedCategoryAttributesEntity = $this
                ->queryContainer
                ->queryAttributeByCategoryId($idCategory)
                ->filterByFkLocale($idLocale)
                ->findOneOrCreate();

            $localizedCategoryAttributesEntity = $this->updateEntity(
                $localizedCategoryAttributesEntity,
                $localizedAttributesTransfer,
                $idCategory
            );

            $localizedCategoryAttributesEntity->save();
        }
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function delete($idCategory)
    {
        $this
            ->queryContainer
            ->queryAttributeByCategoryId($idCategory)
            ->delete();
    }
}
