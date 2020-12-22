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
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Orm\Zed\Category\Persistence\SpyCategoryTemplate;
use Orm\Zed\Store\Persistence\SpyStore;
use Orm\Zed\Url\Persistence\SpyUrl;
use phpDocumentor\Reflection\Types\Iterable_;
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
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode[] $categoryNodeEntities
     * @param \Generated\Shared\Transfer\NodeTransfer[] $nodeTransfers
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function mapCategoryNodeEntitiesToNodeTransfersIndexedByIdCategoryNode(array $categoryNodeEntities, array $nodeTransfers): array
    {
        foreach ($categoryNodeEntities as $categoryNodeEntity) {
            $nodeTransfers[$categoryNodeEntity->getIdCategoryNode()] = $this->mapCategoryNodeEntityToNodeTransferWithCategoryRelation(
                $categoryNodeEntity,
                new NodeTransfer()
            );
        }

        return $nodeTransfers;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $nodeEntity
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    public function mapCategoryNodeEntityToNodeTransferWithCategoryRelation(SpyCategoryNode $nodeEntity, NodeTransfer $nodeTransfer): NodeTransfer
    {
        $nodeTransfer = $this->mapCategoryNode($nodeEntity, $nodeTransfer);
        $categoryEntity = $nodeEntity->getCategory();

        $categoryTransfer = $this->mapCategory($categoryEntity, new CategoryTransfer());
        $categoryTransfer = $this->mapLocalizedAttributes($categoryEntity, $categoryTransfer, $nodeEntity->getSpyUrls());
        $storeRelationTransfer = $this->mapCategoryStoreEntitiesToStoreRelationTransfer(
            $categoryEntity->getSpyCategoryStores(),
            (new StoreRelationTransfer())->setIdEntity($categoryEntity->getIdCategory())
        );
        $categoryTransfer->setStoreRelation($storeRelationTransfer);

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
            $nodeTransfer = $this->mapCategoryNode($categoryNodeEntity, new NodeTransfer());
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
                $categoryTransfer->setParentCategoryNode($this->mapCategoryNode($parentCategoryNodeEntity, new NodeTransfer()));

                continue;
            }

            $categoryTransfer->addExtraParent($this->mapCategoryNode($parentCategoryNodeEntity, new NodeTransfer()));
        }

        return $categoryTransfer;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     * @param \Orm\Zed\Url\Persistence\SpyUrl[]|\Propel\Runtime\Collection\ObjectCollection|null $urlEntities
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function mapLocalizedAttributes(
        SpyCategory $categoryEntity,
        CategoryTransfer $categoryTransfer,
        ?ObjectCollection $urlEntities = null
    ): CategoryTransfer {
        foreach ($categoryEntity->getAttributes() as $attribute) {
            $localeTransfer = new LocaleTransfer();
            $localeTransfer->fromArray($attribute->getLocale()->toArray(), true);

            $categoryLocalizedAttributesTransfer = new CategoryLocalizedAttributesTransfer();
            $categoryLocalizedAttributesTransfer->fromArray($attribute->toArray(), true);
            $categoryLocalizedAttributesTransfer->setLocale($localeTransfer);

            if ($urlEntities) {
                $categoryLocalizedAttributesTransfer = $this->mapUrlEntitiesToCategoryLocalizedAttributesTransfer(
                    $urlEntities,
                    $categoryLocalizedAttributesTransfer
                );
            }

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

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryStore[]|\Propel\Runtime\Collection\ObjectCollection $categoryStoreEntities
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    protected function mapCategoryStoreEntitiesToStoreRelationTransfer(
        ObjectCollection $categoryStoreEntities,
        StoreRelationTransfer $storeRelationTransfer
    ): StoreRelationTransfer {
        foreach ($categoryStoreEntities as $categoryStoreEntity) {
            $storeTransfer = $this->mapStoreEntityToStoreTransfer($categoryStoreEntity->getSpyStore(), new StoreTransfer());
            $storeRelationTransfer->addStores($storeTransfer);
            $storeRelationTransfer->addIdStores($storeTransfer->getIdStore());
        }

        return $storeRelationTransfer;
    }

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function mapStoreEntityToStoreTransfer(SpyStore $storeEntity, StoreTransfer $storeTransfer): StoreTransfer
    {
        return $storeTransfer->fromArray($storeEntity->toArray(), true);
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl[]|\Propel\Runtime\Collection\ObjectCollection $urlEntities
     * @param \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer
     */
    protected function mapUrlEntitiesToCategoryLocalizedAttributesTransfer(
        ObjectCollection $urlEntities,
        CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer
    ): CategoryLocalizedAttributesTransfer {
        $urlEntity = $this->findUrlForLocale($urlEntities, $categoryLocalizedAttributesTransfer->getLocale());
        if (!$urlEntity) {
            return $categoryLocalizedAttributesTransfer;
        }

        return $categoryLocalizedAttributesTransfer->setUrl($urlEntity->getUrl());
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl[]|\Propel\Runtime\Collection\ObjectCollection $urlEntities
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl|null
     */
    protected function findUrlForLocale(ObjectCollection $urlEntities, LocaleTransfer $localeTransfer): ?SpyUrl
    {
        foreach ($urlEntities as $urlEntity) {
            if ($urlEntity->getFkLocale() === $localeTransfer->getIdLocale()) {
                return $urlEntity;
            }
        }

        return null;
    }
}
