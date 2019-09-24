<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Business\Search;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Orm\Zed\CategoryPageSearch\Persistence\SpyCategoryNodePageSearch;
use Propel\Runtime\Map\TableMap;
use Spryker\Shared\CategoryPageSearch\CategoryPageSearchConstants;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToSearchInterface;
use Spryker\Zed\CategoryPageSearch\Dependency\Service\CategoryPageSearchToUtilEncodingInterface;
use Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class CategoryNodePageSearch implements CategoryNodePageSearchInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\CategoryPageSearch\Dependency\Service\CategoryPageSearchToUtilEncodingInterface
     */
    protected $utilEncoding;

    /**
     * @var \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToSearchInterface
     */
    protected $searchFacade;

    /**
     * @var \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @deprecated Use `\Spryker\Zed\SynchronizationBehavior\SynchronizationBehaviorConfig::isSynchronizationEnabled()` instead.
     *
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param \Spryker\Zed\CategoryPageSearch\Dependency\Service\CategoryPageSearchToUtilEncodingInterface $utilEncoding
     * @param \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToSearchInterface $searchFacade
     * @param \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchQueryContainerInterface $queryContainer
     * @param \Spryker\Shared\Kernel\Store $store
     * @param bool $isSendingToQueue
     */
    public function __construct(
        CategoryPageSearchToUtilEncodingInterface $utilEncoding,
        CategoryPageSearchToSearchInterface $searchFacade,
        CategoryPageSearchQueryContainerInterface $queryContainer,
        Store $store,
        $isSendingToQueue
    ) {
        $this->utilEncoding = $utilEncoding;
        $this->searchFacade = $searchFacade;
        $this->queryContainer = $queryContainer;
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
        $categoryTrees = $this->getCategoryTrees($categoryNodeIds);
        $spyCategoryNodePageSearchEntities = $this->findCategoryNodePageSearchEntitiesByCategoryNodeIds($categoryNodeIds);

        if (!$categoryTrees) {
            $this->deleteSearchData($spyCategoryNodePageSearchEntities);
        }

        $this->storeData($categoryTrees, $spyCategoryNodePageSearchEntities);
    }

    /**
     * @param array $categoryNodeIds
     *
     * @return void
     */
    public function unpublish(array $categoryNodeIds)
    {
        $spyCategoryNodePageSearchEntities = $this->findCategoryNodePageSearchEntitiesByCategoryNodeIds($categoryNodeIds);

        $this->deleteSearchData($spyCategoryNodePageSearchEntities);
    }

    /**
     * @param array $spyCategoryNodePageSearchEntities
     *
     * @return void
     */
    protected function deleteSearchData(array $spyCategoryNodePageSearchEntities)
    {
        foreach ($spyCategoryNodePageSearchEntities as $spyCategoryNodePageSearchLocaleEntities) {
            foreach ($spyCategoryNodePageSearchLocaleEntities as $spyCategoryNodePageSearchLocaleEntity) {
                $spyCategoryNodePageSearchLocaleEntity->delete();
            }
        }
    }

    /**
     * @param array $categoryTrees
     * @param array $spyCategoryNodePageSearchEntities
     *
     * @return void
     */
    protected function storeData(array $categoryTrees, array $spyCategoryNodePageSearchEntities)
    {
        foreach ($categoryTrees as $categoryNodeId => $categoryTreeWithLocales) {
            foreach ($categoryTreeWithLocales as $localeName => $categoryTreeWithLocale) {
                if (isset($spyCategoryNodePageSearchEntities[$categoryNodeId][$localeName])) {
                    $this->storeDataSet($categoryTreeWithLocale, $localeName, $spyCategoryNodePageSearchEntities[$categoryNodeId][$localeName]);

                    continue;
                }

                $this->storeDataSet($categoryTreeWithLocale, $localeName);
            }
        }
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $spyCategoryNodeEntity
     * @param string $localeName
     * @param \Orm\Zed\CategoryPageSearch\Persistence\SpyCategoryNodePageSearch|null $spyCategoryNodePageSearchEntity
     *
     * @return void
     */
    protected function storeDataSet(SpyCategoryNode $spyCategoryNodeEntity, $localeName, ?SpyCategoryNodePageSearch $spyCategoryNodePageSearchEntity = null)
    {
        if ($spyCategoryNodePageSearchEntity === null) {
            $spyCategoryNodePageSearchEntity = new SpyCategoryNodePageSearch();
        }

        if (!$spyCategoryNodeEntity->getCategory()->getIsActive()) {
            if (!$spyCategoryNodePageSearchEntity->isNew()) {
                $spyCategoryNodePageSearchEntity->delete();
            }

            return;
        }

        $categoryTreeNodeData = $spyCategoryNodeEntity->toArray(TableMap::TYPE_FIELDNAME, true, [], true);
        $data = $this->mapToSearchData($categoryTreeNodeData, $localeName);
        $spyCategoryNodePageSearchEntity->setFkCategoryNode($spyCategoryNodeEntity->getIdCategoryNode());
        $spyCategoryNodePageSearchEntity->setStructuredData($this->utilEncoding->encodeJson($categoryTreeNodeData));
        $spyCategoryNodePageSearchEntity->setData($data);
        $spyCategoryNodePageSearchEntity->setLocale($localeName);
        $spyCategoryNodePageSearchEntity->setIsSendingToQueue($this->isSendingToQueue);
        $spyCategoryNodePageSearchEntity->save();
    }

    /**
     * @param array $categoryNodeData
     * @param string $localeName
     *
     * @return array
     */
    public function mapToSearchData(array $categoryNodeData, $localeName)
    {
        return $this->searchFacade
            ->transformPageMapToDocumentByMapperName(
                $categoryNodeData,
                (new LocaleTransfer())->setLocaleName($localeName),
                CategoryPageSearchConstants::CATEGORY_NODE_RESOURCE_NAME
            );
    }

    /**
     * @param array $categoryNodeIds
     *
     * @return array
     */
    protected function findCategoryNodePageSearchEntitiesByCategoryNodeIds(array $categoryNodeIds)
    {
        $categoryNodeSearchEntities = $this->queryContainer->queryCategoryNodePageSearchByIds($categoryNodeIds)->find();
        $categoryNodeSearchEntitiesByIdAndLocale = [];
        foreach ($categoryNodeSearchEntities as $categoryNodeSearchEntity) {
            $categoryNodeSearchEntitiesByIdAndLocale[$categoryNodeSearchEntity->getFkCategoryNode()][$categoryNodeSearchEntity->getLocale()] = $categoryNodeSearchEntity;
        }

        return $categoryNodeSearchEntitiesByIdAndLocale;
    }

    /**
     * @param int[] $categoryNodeIds
     *
     * @return array
     */
    protected function getCategoryTrees(array $categoryNodeIds): array
    {
        $localeNames = $this->getSharedPersistenceLocaleNames();
        $locales = $this->queryContainer->queryLocalesWithLocaleNames($localeNames)->find();

        $categoryNodeTree = [];
        $this->disableInstancePooling();
        foreach ($locales as $locale) {
            $categoryNodes = $this->queryContainer->queryWholeCategoryNodeTree($categoryNodeIds, $locale->getIdLocale())->find()->toKeyIndex();

            foreach ($categoryNodeIds as $categoryNodeId) {
                if (isset($categoryNodes[$categoryNodeId])) {
                    $categoryNodeTree[$categoryNodeId][$locale->getLocaleName()] = $categoryNodes[$categoryNodeId];
                }
            }
        }
        $this->enableInstancePooling();

        return $categoryNodeTree;
    }

    /**
     * @return string[]
     */
    protected function getSharedPersistenceLocaleNames(): array
    {
        $localeNames = $this->store->getLocales();
        foreach ($this->store->getStoresWithSharedPersistence() as $storeName) {
            foreach ($this->store->getLocalesPerStore($storeName) as $localeName) {
                $localeNames[] = $localeName;
            }
        }

        return array_unique($localeNames);
    }
}
