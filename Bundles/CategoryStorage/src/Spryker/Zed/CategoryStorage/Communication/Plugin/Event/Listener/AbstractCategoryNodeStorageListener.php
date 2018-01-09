<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorage;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CategoryStorage\Communication\CategoryStorageCommunicationFactory getFactory()
 */
abstract class AbstractCategoryNodeStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @param array $categoryNodeIds
     *
     * @return void
     */
    protected function publish(array $categoryNodeIds)
    {
        $categoryNodes = $this->getCategoryNodes($categoryNodeIds);
        $spyCategoryNodeStorageEntities = $this->findCategoryNodeStorageEntitiesByCategoryNodeIds($categoryNodeIds);

        $this->storeData($categoryNodes, $spyCategoryNodeStorageEntities);
    }

    /**
     * @param array $categoryNodeIds
     *
     * @return void
     */
    protected function unpublish(array $categoryNodeIds)
    {
        $spyCategoryNodeStorageEntities = $this->findCategoryNodeStorageEntitiesByCategoryNodeIds($categoryNodeIds);
        foreach ($spyCategoryNodeStorageEntities as $spyCategoryNodeStorageEntity) {
            $spyCategoryNodeStorageEntity->delete();
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
    protected function storeDataSet(CategoryNodeStorageTransfer $categoryNodeStorageTransfer, $localeName, SpyCategoryNodeStorage $spyCategoryNodeStorageEntity = null)
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

        $categoryNodeNodeData = $this->getFactory()->getUtilSanitizeService()->arrayFilterRecursive($categoryNodeStorageTransfer->toArray());
        $spyCategoryNodeStorageEntity->setFkCategoryNode($categoryNodeStorageTransfer->getNodeId());
        $spyCategoryNodeStorageEntity->setData($categoryNodeNodeData);
        $spyCategoryNodeStorageEntity->setStore($this->getStore()->getStoreName());
        $spyCategoryNodeStorageEntity->setLocale($localeName);
        $spyCategoryNodeStorageEntity->save();
    }

    /**
     * @param array $categoryNodeIds
     *
     * @return array
     */
    protected function findCategoryNodeStorageEntitiesByCategoryNodeIds(array $categoryNodeIds)
    {
        $categoryNodeStorageEntities = $this->getQueryContainer()->queryCategoryNodeStorageByIds($categoryNodeIds)->find();
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
        $localeNames = $this->getStore()->getLocales();
        $locales = $this->getQueryContainer()->queryLocalesWithLocaleNames($localeNames)->find();

        $categoryNodeTree = [];
        $this->disableInstancePooling();
        foreach ($locales as $locale) {
            $categoryNodes = $this->getQueryContainer()->queryCategoryNode($locale->getIdLocale())->find()->toKeyIndex();
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

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getFactory()->getStore();
    }
}
