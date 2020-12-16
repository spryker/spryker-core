<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTemplateTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryAttribute;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Orm\Zed\Category\Persistence\SpyCategoryTemplate;
use Orm\Zed\Store\Persistence\SpyStore;
use Orm\Zed\Url\Persistence\SpyUrl;
use Propel\Runtime\Collection\ObjectCollection;

class CategoryNodeMapper
{
    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode[] $categoryNodeEntities
     * @param \Generated\Shared\Transfer\NodeTransfer[] $nodeTransfers
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function mapCategoryNodeEntitiesToNodeTransfersIndexedByIdCategoryNode(array $categoryNodeEntities, array $nodeTransfers): array
    {
        foreach ($categoryNodeEntities as $categoryNodeEntity) {
            $nodeTransfers[$categoryNodeEntity->getIdCategoryNode()] = $this->mapCategoryNodeEntityToNodeTransfer(
                $categoryNodeEntity,
                new NodeTransfer()
            );
        }

        return $nodeTransfers;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $categoryNodeEntity
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    public function mapCategoryNodeEntityToNodeTransfer(SpyCategoryNode $categoryNodeEntity, NodeTransfer $nodeTransfer): NodeTransfer
    {
        $categoryEntity = $categoryNodeEntity->getCategory();
        $categoryTransfer = $this->mapCategoryEntityToCategoryTransfer($categoryNodeEntity->getCategory(), new CategoryTransfer());
        $localizedAttributesTransfers = $this->mapCategoryAttributeEntitiesToCategoryLocalizedAttributesTransfers(
            $categoryEntity->getAttributes(),
            []
        );
        $localizedAttributesTransfers = $this->mapUrlEntitiesToCategoryLocalizedAttributesTransfers(
            $categoryNodeEntity->getSpyUrls(),
            $localizedAttributesTransfers
        );
        $categoryTransfer->setLocalizedAttributes(new ArrayObject($localizedAttributesTransfers));

        return $nodeTransfer
            ->fromArray($categoryNodeEntity->toArray(), true)
            ->setCategory($categoryTransfer);
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function mapCategoryEntityToCategoryTransfer(SpyCategory $categoryEntity, CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        $categoryTemplateTransfer = $this->mapCategoryTemplateEntityToCategoryTemplateTransfer(
            $categoryEntity->getCategoryTemplate(),
            new CategoryTemplateTransfer()
        );

        $storeRelationTransfer = $this->mapCategoryStoreEntitiesToStoreRelationTransfer(
            $categoryEntity->getSpyCategoryStores(),
            (new StoreRelationTransfer())->setIdEntity($categoryEntity->getIdCategory())
        );

        return $categoryTransfer
            ->fromArray($categoryEntity->toArray(), true)
            ->setCategoryTemplate($categoryTemplateTransfer)
            ->setStoreRelation($storeRelationTransfer);
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
     * @param \Orm\Zed\Category\Persistence\SpyCategoryTemplate $categoryTemplateEntity
     * @param \Generated\Shared\Transfer\CategoryTemplateTransfer $categoryTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTemplateTransfer
     */
    protected function mapCategoryTemplateEntityToCategoryTemplateTransfer(
        SpyCategoryTemplate $categoryTemplateEntity,
        CategoryTemplateTransfer $categoryTemplateTransfer
    ): CategoryTemplateTransfer {
        return $categoryTemplateTransfer->fromArray($categoryTemplateEntity->toArray());
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryAttribute[]|\Propel\Runtime\Collection\ObjectCollection $categoryAttributeEntities
     * @param \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer[] $categoryLocalizedAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer[]
     */
    protected function mapCategoryAttributeEntitiesToCategoryLocalizedAttributesTransfers(
        ObjectCollection $categoryAttributeEntities,
        array $categoryLocalizedAttributesTransfers
    ): array {
        foreach ($categoryAttributeEntities as $categoryAttributeEntity) {
            $categoryLocalizedAttributesTransfers[] = $this->mapCategoryAttributeEntityToCategoryCategoryLocalizedAttributesTransfer(
                $categoryAttributeEntity,
                new CategoryLocalizedAttributesTransfer()
            );
        }

        return $categoryLocalizedAttributesTransfers;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryAttribute $categoryAttributeEntity
     * @param \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer
     */
    protected function mapCategoryAttributeEntityToCategoryCategoryLocalizedAttributesTransfer(
        SpyCategoryAttribute $categoryAttributeEntity,
        CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer
    ): CategoryLocalizedAttributesTransfer {
        $localeTransfer = (new LocaleTransfer())->fromArray(
            $categoryAttributeEntity->getLocale()->toArray(),
            true
        );

        return $categoryLocalizedAttributesTransfer
            ->fromArray($categoryAttributeEntity->toArray(), true)
            ->setLocale($localeTransfer);
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl[]|\Propel\Runtime\Collection\ObjectCollection $urlEntities
     * @param \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer[] $categoryLocalizedAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer[]
     */
    protected function mapUrlEntitiesToCategoryLocalizedAttributesTransfers(ObjectCollection $urlEntities, array $categoryLocalizedAttributesTransfers): array
    {
        foreach ($categoryLocalizedAttributesTransfers as &$categoryLocalizedAttributesTransfer) {
            $urlEntity = $this->findUrlForLocale($urlEntities, $categoryLocalizedAttributesTransfer->getLocale());
            if (!$urlEntity) {
                continue;
            }

            $categoryLocalizedAttributesTransfer->setUrl($urlEntity->getUrl());
        }
        unset($categoryLocalizedAttributesTransfer);

        return $categoryLocalizedAttributesTransfers;
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

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     * @param \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer
     */
    protected function mapUrlEntityToCategoryLocalizedAttributesTransfer(
        SpyUrl $urlEntity,
        CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer
    ): CategoryLocalizedAttributesTransfer {
        return $categoryLocalizedAttributesTransfer->setUrl($urlEntity->getUrl());
    }
}
