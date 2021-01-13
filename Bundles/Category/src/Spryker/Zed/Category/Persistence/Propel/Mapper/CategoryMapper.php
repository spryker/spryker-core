<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTemplateTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Orm\Zed\Category\Persistence\SpyCategoryTemplate;
use Propel\Runtime\Collection\ObjectCollection;

class CategoryMapper implements CategoryMapperInterface
{
    /**
     * @var \Spryker\Zed\Category\Persistence\Propel\Mapper\CategoryNodeMapper
     */
    protected $categoryNodeMapper;

    /**
     * @param \Spryker\Zed\Category\Persistence\Propel\Mapper\CategoryNodeMapper $categoryNodeMapper
     */
    public function __construct(CategoryNodeMapper $categoryNodeMapper)
    {
        $this->categoryNodeMapper = $categoryNodeMapper;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $spyCategory
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function mapCategory(SpyCategory $spyCategory, CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        return $categoryTransfer->fromArray($spyCategory->toArray(), true);
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $spyCategory
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function mapCategoryWithRelations(SpyCategory $spyCategory, CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        $categoryTransfer = $this->mapCategory($spyCategory, $categoryTransfer);
        $categoryTransfer = $this->mapParentCategoryNodes($spyCategory, $categoryTransfer);
        $categoryTransfer = $this->mapLocalizedAttributes($spyCategory, $categoryTransfer);
        $categoryTransfer->setCategoryTemplate($this->mapCategoryTemplateEntityToCategoryTemplateTransfer(
            $spyCategory->getCategoryTemplate(),
            new CategoryTemplateTransfer()
        ));
        $categoryTransfer = $this->mapCategoryNodes($spyCategory, $categoryTransfer);

        return $categoryTransfer;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $nodeEntity
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    public function mapCategoryNodeEntityToNodeTransferWithCategoryRelation(SpyCategoryNode $nodeEntity, NodeTransfer $nodeTransfer): NodeTransfer
    {
        $nodeTransfer = $this->categoryNodeMapper->mapCategoryNode($nodeEntity, $nodeTransfer);
        $categoryEntity = $nodeEntity->getCategory();
        $categoryTransfer = $this->mapCategory($categoryEntity, new CategoryTransfer());
        $categoryTransfer = $this->mapLocalizedAttributes($categoryEntity, $categoryTransfer);
        $categoryTemplateTransfer = $this->mapCategoryTemplateEntityToCategoryTemplateTransfer(
            $categoryEntity->getCategoryTemplate(),
            new CategoryTemplateTransfer()
        );
        $categoryTransfer->setCategoryTemplate($categoryTemplateTransfer);

        return $nodeTransfer->setCategory($categoryTransfer);
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory[]|\Propel\Runtime\Collection\ObjectCollection $categoryEntities
     * @param \Generated\Shared\Transfer\CategoryCollectionTransfer $categoryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function mapCategoryCollection(
        ObjectCollection $categoryEntities,
        CategoryCollectionTransfer $categoryCollectionTransfer
    ): CategoryCollectionTransfer {
        foreach ($categoryEntities as $categoryEntity) {
            $categoryTransfer = $this->mapCategory($categoryEntity, new CategoryTransfer());
            $categoryTransfer = $this->mapLocalizedAttributes($categoryEntity, $categoryTransfer);

            foreach ($categoryTransfer->getLocalizedAttributes() as $localizedAttribute) {
                $categoryTransfer->fromArray($localizedAttribute->toArray(), true);
            }

            $nodeCollectionTransfer = $this->categoryNodeMapper->mapNodeCollection(
                $categoryEntity->getNodes(),
                new NodeCollectionTransfer()
            );
            $categoryTransfer->setNodeCollection($nodeCollectionTransfer);

            $categoryCollectionTransfer->addCategory($categoryTransfer);
        }

        return $categoryCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategory
     */
    public function mapCategoryTransferToCategoryEntity(CategoryTransfer $categoryTransfer, SpyCategory $categoryEntity): SpyCategory
    {
        $categoryEntity->fromArray($categoryTransfer->modifiedToArray());

        return $categoryEntity;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function mapCategoryNodes(SpyCategory $categoryEntity, CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        foreach ($categoryEntity->getNodes() as $categoryNodeEntity) {
            if (!$categoryNodeEntity->isMain()) {
                continue;
            }
            $nodeTransfer = $this->categoryNodeMapper->mapCategoryNode($categoryNodeEntity, new NodeTransfer());
            $nodeTransfer->setCategory(clone $categoryTransfer);
            $categoryTransfer->setCategoryNode($nodeTransfer);
        }

        return $categoryTransfer;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function mapParentCategoryNodes(SpyCategory $categoryEntity, CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        foreach ($categoryEntity->getNodes() as $categoryNodeEntity) {
            $parentCategoryNodeEntity = $categoryNodeEntity->getParentCategoryNode();

            if ($parentCategoryNodeEntity === null) {
                continue;
            }

            if ($categoryNodeEntity->isMain()) {
                $categoryTransfer->setParentCategoryNode($this->categoryNodeMapper->mapCategoryNode($parentCategoryNodeEntity, new NodeTransfer()));

                continue;
            }

            $categoryTransfer->addExtraParent($this->categoryNodeMapper->mapCategoryNode($parentCategoryNodeEntity, new NodeTransfer()));
        }

        return $categoryTransfer;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function mapLocalizedAttributes(SpyCategory $categoryEntity, CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        foreach ($categoryEntity->getAttributes() as $attribute) {
            $localeTransfer = new LocaleTransfer();
            $localeTransfer->fromArray($attribute->getLocale()->toArray(), true);

            $categoryLocalizedAttributesTransfer = new CategoryLocalizedAttributesTransfer();
            $categoryLocalizedAttributesTransfer->fromArray($attribute->toArray(), true);
            $categoryLocalizedAttributesTransfer->setLocale($localeTransfer);

            $categoryTransfer->addLocalizedAttributes($categoryLocalizedAttributesTransfer);
        }

        return $categoryTransfer;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryTemplate $categoryTemplateEntity
     * @param \Generated\Shared\Transfer\CategoryTemplateTransfer $categoryTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTemplateTransfer
     */
    protected function mapCategoryTemplateEntityToCategoryTemplateTransfer(
        SpyCategoryTemplate $categoryTemplateEntity,
        CategoryTemplateTransfer $categoryTemplateTransfer
    ): CategoryTemplateTransfer {
        return $categoryTemplateTransfer->fromArray($categoryTemplateEntity->toArray(), true);
    }
}
