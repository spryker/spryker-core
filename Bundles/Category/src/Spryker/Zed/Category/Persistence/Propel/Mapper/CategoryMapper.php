<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Propel\Runtime\Collection\ObjectCollection;

class CategoryMapper implements CategoryMapperInterface
{
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
        $categoryTransfer = $this->mapCategoryNodes($spyCategory, $categoryTransfer);
        $categoryTransfer = $this->mapParentCategoryNodes($spyCategory, $categoryTransfer);
        $categoryTransfer = $this->mapLocalizedAttributes($spyCategory, $categoryTransfer);

        return $categoryTransfer;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $spyCategoryNode
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    public function mapCategoryNode(SpyCategoryNode $spyCategoryNode, NodeTransfer $nodeTransfer): NodeTransfer
    {
        return $nodeTransfer->fromArray($spyCategoryNode->toArray(), true);
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory[]|\Propel\Runtime\Collection\ObjectCollection $categoryEntities
     * @param \Generated\Shared\Transfer\CategoryCollectionTransfer $categoryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function mapCategoryCollection(ObjectCollection $categoryEntities, CategoryCollectionTransfer $categoryCollectionTransfer): CategoryCollectionTransfer
    {
        foreach ($categoryEntities as $categoryEntity) {
            $categoryTransfer = $this->mapCategory($categoryEntity, new CategoryTransfer());
            $categoryTransfer = $this->mapLocalizedAttributes($categoryEntity, $categoryTransfer);

            foreach ($categoryTransfer->getLocalizedAttributes() as $localizedAttribute) {
                $categoryTransfer->fromArray($localizedAttribute->toArray(), true);
            }

            $nodeCollectionTransfer = $this->mapNodeCollection($categoryEntity->getNodes(), new NodeCollectionTransfer());
            $categoryTransfer->setNodeCollection($nodeCollectionTransfer);

            $categoryCollectionTransfer->addCategory($categoryTransfer);
        }

        return $categoryCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode[]|\Propel\Runtime\Collection\ObjectCollection $nodeEntities
     * @param \Generated\Shared\Transfer\NodeCollectionTransfer $nodeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\NodeCollectionTransfer
     */
    public function mapNodeCollection(ObjectCollection $nodeEntities, NodeCollectionTransfer $nodeCollectionTransfer): NodeCollectionTransfer
    {
        foreach ($nodeEntities as $nodeEntity) {
            $nodeCollectionTransfer->addNode($this->mapCategoryNode($nodeEntity, new NodeTransfer()));
        }

        return $nodeCollectionTransfer;
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
            $categoryTransfer->setCategoryNode($this->mapCategoryNode($categoryNodeEntity, new NodeTransfer()));
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

            if ($parentCategoryNodeEntity == null) {
                continue;
            }

            if ($categoryNodeEntity->isMain()) {
                $categoryTransfer->setParentCategoryNode($this->mapCategoryNode($parentCategoryNodeEntity, new NodeTransfer()));

                continue;
            }

            $categoryTransfer->addExtraParent($this->mapCategoryNode($parentCategoryNodeEntity, new NodeTransfer()));
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
}
