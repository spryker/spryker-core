<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business\Storage;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\CategoryTreeStorageTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Orm\Zed\CategoryStorage\Persistence\SpyCategoryTreeStorage;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\CategoryStorage\Dependency\Service\CategoryStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class CategoryTreeStorage implements CategoryTreeStorageInterface
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
     * @return void
     */
    public function publish()
    {
        $categoryTrees = $this->getCategoryTrees();
        $spyCategoryStorageEntities = $this->findCategoryStorageEntities();

        $this->storeData($categoryTrees, $spyCategoryStorageEntities);
    }

    /**
     * @return void
     */
    public function unpublish()
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

                continue;
            }

            $this->storeDataSet($categoryTreeByLocale, $localeName);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer[] $categoryNodeStorageTransfers
     * @param string $localeName
     * @param \Orm\Zed\CategoryStorage\Persistence\SpyCategoryTreeStorage|null $spyCategoryTreeStorage
     *
     * @return void
     */
    protected function storeDataSet(array $categoryNodeStorageTransfers, $localeName, ?SpyCategoryTreeStorage $spyCategoryTreeStorage = null)
    {
        if ($spyCategoryTreeStorage === null) {
            $spyCategoryTreeStorage = new SpyCategoryTreeStorage();
        }

        $categoryTreeStorageTransfer = new CategoryTreeStorageTransfer();
        foreach ($categoryNodeStorageTransfers as $categoryNodeStorageTransfer) {
            $categoryTreeStorageTransfer->addCategoryNodeStorage($categoryNodeStorageTransfer);
        }

        $data = $this->utilSanitize->arrayFilterRecursive($categoryTreeStorageTransfer->toArray());
        $spyCategoryTreeStorage->setLocale($localeName);
        $spyCategoryTreeStorage->setData($data);
        $spyCategoryTreeStorage->setIsSendingToQueue($this->isSendingToQueue);
        $spyCategoryTreeStorage->save();
    }

    /**
     * @return array
     */
    protected function findCategoryStorageEntities()
    {
        $spyCategoryStorageEntities = $this->queryContainer->queryCategoryStorage()->find();
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
        $localeNames = $this->store->getLocales();
        $locales = $this->queryContainer->queryLocalesWithLocaleNames($localeNames)->find();

        $rootCategory = $this->queryContainer->queryCategoryRoot()->findOne();
        $categoryNodeTree = [];
        $this->disableInstancePooling();
        foreach ($locales as $locale) {
            $categoryNodes = $this->queryContainer->queryCategoryNodeTree($locale->getIdLocale())->find()->getData();
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
