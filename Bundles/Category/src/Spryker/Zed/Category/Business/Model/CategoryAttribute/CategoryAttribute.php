<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model\CategoryAttribute;

use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
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
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function read($idCategory, CategoryTransfer $categoryTransfer)
    {
        $attributeEntityCollection = $this
            ->queryContainer
            ->queryAttributeByCategoryId($idCategory)
            ->find();

        foreach ($attributeEntityCollection as $attributeEntity) {
            $attributeTransfer = $this->createLocalizedAttributesTransferFromEntity($attributeEntity);
            $attributeTransfer->setLocale($this->createLocaleTransferFromEntity($attributeEntity));
            $categoryTransfer->addLocalizedAttributes($attributeTransfer);
        }

        return $categoryTransfer;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryAttribute $attributeEntity
     *
     * @return \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer
     */
    protected function createLocalizedAttributesTransferFromEntity(SpyCategoryAttribute $attributeEntity)
    {
        $attributeTransfer = new CategoryLocalizedAttributesTransfer();
        $attributeTransfer->fromArray($attributeEntity->toArray(), true);

        return $attributeTransfer;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryAttribute $attributeEntity
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function createLocaleTransferFromEntity(SpyCategoryAttribute $attributeEntity)
    {
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->fromArray($attributeEntity->getLocale()->toArray());

        return $localeTransfer;
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

            $localizedCategoryAttributeEntity = $this->updateEntity(
                $localizedCategoryAttributeEntity,
                $localizedCategoryAttributesTransfer,
                $categoryTransfer->getIdCategory()
            );

            $localizedCategoryAttributeEntity->save();
        }
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryAttribute $categoryAttributeEntity
     * @param \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $localizedCategoryAttributesTransfer
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttribute
     */
    protected function updateEntity(
        SpyCategoryAttribute $categoryAttributeEntity,
        CategoryLocalizedAttributesTransfer $localizedCategoryAttributesTransfer,
        $idCategory
    ) {
        $categoryAttributeEntity->fromArray($localizedCategoryAttributesTransfer->toArray());
        $categoryAttributeEntity->setFkCategory($idCategory);

        $idLocale = $localizedCategoryAttributesTransfer->getLocale()->getIdLocale();
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
        $localizedCategoryAttributeTransferCollection = $categoryTransfer->getLocalizedAttributes();

        foreach ($localizedCategoryAttributeTransferCollection as $localizedCategoryAttributesTransfer) {
            $idCategory = $categoryTransfer->getIdCategory();
            $idLocale = $localizedCategoryAttributesTransfer->getLocale()->getIdLocale();

            $localizedCategoryAttributesEntity = $this
                ->queryContainer
                ->queryAttributeByCategoryId($idCategory)
                ->filterByFkLocale($idLocale)
                ->findOneOrCreate();

            $localizedCategoryAttributesEntity = $this->updateEntity(
                $localizedCategoryAttributesEntity,
                $localizedCategoryAttributesTransfer,
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
