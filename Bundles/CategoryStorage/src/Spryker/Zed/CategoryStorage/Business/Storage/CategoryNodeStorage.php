<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business\Storage;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorage;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\CategoryStorage\Dependency\Service\CategoryStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class CategoryNodeStorage implements CategoryNodeStorageInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\CategoryStorage\Dependency\Service\CategoryStorageToUtilSanitizeServiceInterface
     */
    protected $utilSanitize;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\CategoryStorage\Dependency\Service\CategoryStorageToUtilSanitizeServiceInterface $utilSanitize
     * @param \Spryker\Shared\Kernel\Store $store
     * @param bool $isSendingToQueue
     */
    public function __construct(CategoryStorageQueryContainerInterface $queryContainer, CategoryStorageToUtilSanitizeServiceInterface $utilSanitize, Store $store, $isSendingToQueue)
    {
        $this->queryContainer = $queryContainer;
        $this->utilSanitize = $utilSanitize;
        $this->store = $store;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param array $categoryNodeIds
     *
     * @return void
     */
    public function publish(array $categoryNodeIds)
    {
        $categoryNodes = $this->getCategoryNodes($categoryNodeIds);
        $spyCategoryNodeStorageEntities = $this->findCategoryNodeStorageEntitiesByCategoryNodeIds($categoryNodeIds);

        if (!$categoryNodes) {
            $this->deleteStorageData($spyCategoryNodeStorageEntities);
        }

        $this->storeData($categoryNodes, $spyCategoryNodeStorageEntities);
    }

    /**
     * @param array $categoryNodeIds
     *
     * @return void
     */
    public function unpublish(array $categoryNodeIds)
    {
        $spyCategoryNodeStorageEntities = $this->findCategoryNodeStorageEntitiesByCategoryNodeIds($categoryNodeIds);

        $this->deleteStorageData($spyCategoryNodeStorageEntities);
    }

    /**
     * @param array $spyCategoryNodeStorageEntities
     *
     * @return void
     */
    protected function deleteStorageData(array $spyCategoryNodeStorageEntities)
    {
        foreach ($spyCategoryNodeStorageEntities as $spyCategoryNodeStorageLocaleEntities) {
            foreach ($spyCategoryNodeStorageLocaleEntities as $spyCategoryNodeStorageLocaleEntity) {
                $spyCategoryNodeStorageLocaleEntity->delete();
            }
        }
    }

    /**
     * @param array $categoryNodes
     * @param array $spyCategoryNodeStorageEntities
     *
     * @return void
     */
    protected function storeData(array $categoryNodes, array $spyCategoryNodeStorageEntities)
    {
        foreach ($categoryNodes as $categoryNodeId => $categoryNodeWithLocales) {
            foreach ($categoryNodeWithLocales as $localeName => $categoryNodeWithLocale) {
                if (isset($spyCategoryNodeStorageEntities[$categoryNodeId][$localeName])) {
                    $this->storeDataSet($categoryNodeWithLocale, $localeName, $spyCategoryNodeStorageEntities[$categoryNodeId][$localeName]);
                } else {
                    $this->storeDataSet($categoryNodeWithLocale, $localeName);
                }
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $categoryNodeStorageTransfer
     * @param string $localeName
     * @param \Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorage|null $spyCategoryNodeStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(CategoryNodeStorageTransfer $categoryNodeStorageTransfer, $localeName, ?SpyCategoryNodeStorage $spyCategoryNodeStorageEntity = null)
    {
        if ($spyCategoryNodeStorageEntity === null) {
            $spyCategoryNodeStorageEntity = new SpyCategoryNodeStorage();
        }

        if (!$categoryNodeStorageTransfer->getIsActive()) {
            if (!$spyCategoryNodeStorageEntity->isNew()) {
                $spyCategoryNodeStorageEntity->delete();
            }

            return;
        }

        $categoryNodeNodeData = $this->utilSanitize->arrayFilterRecursive($categoryNodeStorageTransfer->toArray());
        $spyCategoryNodeStorageEntity->setFkCategoryNode($categoryNodeStorageTransfer->getNodeId());
        $spyCategoryNodeStorageEntity->setData($categoryNodeNodeData);
        $spyCategoryNodeStorageEntity->setStore($this->store->getStoreName());
        $spyCategoryNodeStorageEntity->setLocale($localeName);
        $spyCategoryNodeStorageEntity->setIsSendingToQueue($this->isSendingToQueue);
        $spyCategoryNodeStorageEntity->save();
    }

    /**
     * @param array $categoryNodeIds
     *
     * @return array
     */
    protected function findCategoryNodeStorageEntitiesByCategoryNodeIds(array $categoryNodeIds)
    {
        $categoryNodeStorageEntities = $this->queryContainer->queryCategoryNodeStorageByIds($categoryNodeIds)->find();
        $categoryNodeStorageEntitiesByIdAndLocale = [];
        foreach ($categoryNodeStorageEntities as $categoryNodeStorageEntity) {
            $categoryNodeStorageEntitiesByIdAndLocale[$categoryNodeStorageEntity->getFkCategoryNode()][$categoryNodeStorageEntity->getLocale()] = $categoryNodeStorageEntity;
        }

        return $categoryNodeStorageEntitiesByIdAndLocale;
    }

    /**
     * @param array $categoryNodeIds
     *
     * @return array
     */
    protected function getCategoryNodes(array $categoryNodeIds)
    {
        $localeNames = $this->store->getLocales();
        $locales = $this->queryContainer->queryLocalesWithLocaleNames($localeNames)->find();

        $categoryNodeTree = [];
        $this->disableInstancePooling();
        foreach ($locales as $locale) {
            $categoryNodes = $this->queryContainer->queryCategoryNode($locale->getIdLocale())->find()->toKeyIndex();
            foreach ($categoryNodeIds as $categoryNodeId) {
                if (isset($categoryNodes[$categoryNodeId])) {
                    $categoryNodeTree[$categoryNodeId][$locale->getLocaleName()] = $this->mapToCategoryNodeStorageTransfer($categoryNodes, $categoryNodes[$categoryNodeId]);
                }
            }
        }
        $this->enableInstancePooling();

        return $categoryNodeTree;
    }

    /**
     * @param array $categoryNodes
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $categoryNode
     * @param bool $includeChildren
     * @param bool $includeParents
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    protected function mapToCategoryNodeStorageTransfer(array $categoryNodes, SpyCategoryNode $categoryNode, $includeChildren = true, $includeParents = true)
    {
        $categoryNodeStorageTransfer = new CategoryNodeStorageTransfer();
        /** @var \Orm\Zed\Category\Persistence\SpyCategoryAttribute $attribute */
        $attribute = $categoryNode->getCategory()->getAttributes()->getFirst();
        $categoryNodeStorageTransfer->setIdCategory($categoryNode->getFkCategory());
        $categoryNodeStorageTransfer->setNodeId($categoryNode->getIdCategoryNode());
        $categoryNodeStorageTransfer->setUrl($categoryNode->getSpyUrls()->getFirst()->getUrl());
        $categoryNodeStorageTransfer->setName($attribute->getName());
        $categoryNodeStorageTransfer->setIsActive($categoryNode->getCategory()->getIsActive());
        $categoryNodeStorageTransfer->setMetaTitle($attribute->getMetaTitle());
        $categoryNodeStorageTransfer->setMetaDescription($attribute->getMetaDescription());
        $categoryNodeStorageTransfer->setMetaKeywords($attribute->getMetaDescription());
        $categoryNodeStorageTransfer->setImage($attribute->getCategoryImageName());
        $categoryNodeStorageTransfer->setTemplatePath($categoryNode->getCategory()->getCategoryTemplate()->getTemplatePath());
        $categoryNodeStorageTransfer->setOrder($categoryNode->getNodeOrder());

        if ($includeChildren) {
            $children = $this->getChildren($categoryNode->getIdCategoryNode(), $categoryNodes);
            foreach ($children as $child) {
                $categoryNodeStorageTransfer->addChildren($child);
            }
        }

        if ($includeParents) {
            $parents = $this->getParents($categoryNode->getFkParentCategoryNode(), $categoryNodes);
            foreach ($parents as $parent) {
                $categoryNodeStorageTransfer->addParents($parent);
            }
        }

        return $categoryNodeStorageTransfer;
    }

    /**
     * @param int $idCategoryNode
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode[] $categoryNodes
     *
     * @return array
     */
    protected function getChildren($idCategoryNode, array $categoryNodes)
    {
        $children = [];
        foreach ($categoryNodes as $categoryNode) {
            if ($categoryNode->getFkParentCategoryNode() === $idCategoryNode) {
                $categoryNodeStorageTransfer = $this->mapToCategoryNodeStorageTransfer($categoryNodes, $categoryNode, true, false);

                $children[] = $categoryNodeStorageTransfer;
            }
        }

        return $children;
    }

    /**
     * @param int $fkCategoryNodeParent
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode[] $categoryNodes
     *
     * @return array
     */
    protected function getParents($fkCategoryNodeParent, array $categoryNodes)
    {
        if ($fkCategoryNodeParent === null) {
            return [];
        }

        $parents = [];
        foreach ($categoryNodes as $categoryNode) {
            if ($categoryNode->getIdCategoryNode() === $fkCategoryNodeParent) {
                $categoryNodeStorageTransfer = $this->mapToCategoryNodeStorageTransfer($categoryNodes, $categoryNode, false, true);

                $parents[] = $categoryNodeStorageTransfer;
            }
        }

        return $parents;
    }
}
