<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\CategoryTreeStorageTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Orm\Zed\CategoryStorage\Persistence\SpyCategoryTreeStorage;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CategoryStorage\Communication\CategoryStorageCommunicationFactory getFactory()
 */
abstract class AbstractCategoryTreeStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @return void
     */
    protected function publish()
    {
        $categoryTrees = $this->getCategoryTrees();
        $spyCategoryStorageEntities = $this->findCategoryStorageEntities();

        $this->storeData($categoryTrees, $spyCategoryStorageEntities);
    }

    /**
     * @return void
     */
    protected function unpublish()
    {
        $spyCategoryMenuTranslationStorageEntities = $this->findCategoryStorageEntities();
        foreach ($spyCategoryMenuTranslationStorageEntities as $spyCategoryMenuTranslationStorageEntity) {
            $spyCategoryMenuTranslationStorageEntity->delete();
        }
    }

    /**
     * @param array $categoryTrees
     * @param array $spyCategoryStorageEntities
     *
     * @return void
     */
    protected function storeData(array $categoryTrees, array $spyCategoryStorageEntities)
    {
        foreach ($categoryTrees as $localeName => $categoryTreeByLocale) {
            if (isset($spyCategoryStorageEntities[$localeName])) {
                $this->storeDataSet($categoryTreeByLocale, $localeName, $spyCategoryStorageEntities[$localeName]);
            } else {
                $this->storeDataSet($categoryTreeByLocale, $localeName);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer[] $categoryNodeStorageTransfers
     * @param string $localeName
     * @param \Orm\Zed\CategoryStorage\Persistence\SpyCategoryTreeStorage|null $spyCategoryTreeStorage
     *
     * @return void
     */
    protected function storeDataSet(array $categoryNodeStorageTransfers, $localeName, SpyCategoryTreeStorage $spyCategoryTreeStorage = null)
    {
        if ($spyCategoryTreeStorage === null) {
            $spyCategoryTreeStorage = new SpyCategoryTreeStorage();
        }

        $categoryTreeStorageTransfer = new CategoryTreeStorageTransfer();
        foreach ($categoryNodeStorageTransfers as $categoryNodeStorageTransfer) {
            $categoryTreeStorageTransfer->addCategoryNodeStorage($categoryNodeStorageTransfer);
        }

        $data = $this->getFactory()->getUtilSanitizeService()->arrayFilterRecursive($categoryTreeStorageTransfer->toArray());
        $spyCategoryTreeStorage->setLocale($localeName);
        $spyCategoryTreeStorage->setStore($this->getStore()->getStoreName());
        $spyCategoryTreeStorage->setData($data);
        $spyCategoryTreeStorage->save();
    }

    /**
     * @return array
     */
    protected function findCategoryStorageEntities()
    {
        $spyCategoryStorageEntities = $this->getQueryContainer()->queryCategoryStorage()->find();
        $categoryStorageEntitiesByLocale = [];
        foreach ($spyCategoryStorageEntities as $spyCategoryStorageEntity) {
            $categoryStorageEntitiesByLocale[$spyCategoryStorageEntity->getLocale()] = $spyCategoryStorageEntity;
        }

        return $categoryStorageEntitiesByLocale;
    }

    /**
     * @return array
     */
    protected function getCategoryTrees()
    {
        $localeNames = $this->getStore()->getLocales();
        $locales = $this->getQueryContainer()->queryLocalesWithLocaleNames($localeNames)->find();

        $rootCategory = $this->getQueryContainer()->queryCategoryRoot()->findOne();
        $categoryNodeTree = [];
        $this->disableInstancePooling();
        foreach ($locales as $locale) {
            $categoryNodes = $this->getQueryContainer()->queryCategoryNodeTree($locale->getIdLocale())->find()->getData();
            $categoryNodeTree[$locale->getLocaleName()] = $this->getChildren($rootCategory->getIdCategoryNode(), $categoryNodes);
        }
        $this->enableInstancePooling();

        return $categoryNodeTree;
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
                $categoryTreeStorageTransfer = $this->mapToCategoryNodeStorageTransfer($categoryNodes, $categoryNode);

                $children[] = $categoryTreeStorageTransfer;
            }
        }

        return $children;
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getFactory()->getStore();
    }

    /**
     * @param array $categoryNodes
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $categoryNode
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    protected function mapToCategoryNodeStorageTransfer(array $categoryNodes, SpyCategoryNode $categoryNode)
    {
        $categoryNodeStorageTransfer = new CategoryNodeStorageTransfer();
        /** @var \Orm\Zed\Category\Persistence\SpyCategoryAttribute $attribute */
        $attribute = $categoryNode->getCategory()->getAttributes()->getFirst();
        $categoryNodeStorageTransfer->setNodeId($categoryNode->getIdCategoryNode());
        $categoryNodeStorageTransfer->setUrl($categoryNode->getSpyUrls()->getFirst()->getUrl());
        $categoryNodeStorageTransfer->setName($attribute->getName());
        $categoryNodeStorageTransfer->setMetaTitle($attribute->getMetaTitle());
        $categoryNodeStorageTransfer->setMetaDescription($attribute->getMetaDescription());
        $categoryNodeStorageTransfer->setMetaKeywords($attribute->getMetaDescription());
        $categoryNodeStorageTransfer->setImage($attribute->getCategoryImageName());
        $categoryNodeStorageTransfer->setOrder($categoryNode->getNodeOrder());
        $children = $this->getChildren($categoryNode->getIdCategoryNode(), $categoryNodes);
        foreach ($children as $child) {
            $categoryNodeStorageTransfer->addChildren($child);
        }

        return $categoryNodeStorageTransfer;
    }
}
