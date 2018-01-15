<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Orm\Zed\CategoryPageSearch\Persistence\SpyCategoryNodePageSearch;
use Propel\Runtime\Map\TableMap;
use Spryker\Shared\CategoryPageSearch\CategoryPageSearchConstants;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CategoryPageSearch\Communication\CategoryPageSearchCommunicationFactory getFactory()
 */
abstract class AbstractCategoryNodeSearchListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @param array $categoryNodeIds
     *
     * @return void
     */
    protected function publish(array $categoryNodeIds)
    {
        $categoryTrees = $this->getCategoryTrees($categoryNodeIds);
        $spyCategoryNodePageSearchEntities = $this->findCategoryNodePageSearchEntitiesByCategoryNodeIds($categoryNodeIds);

        $this->storeData($categoryTrees, $spyCategoryNodePageSearchEntities);
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    protected function unpublish(array $productAbstractIds)
    {
        $spyCategoryNodePageSearchEntities = $this->findCategoryNodePageSearchEntitiesByCategoryNodeIds($productAbstractIds);
        foreach ($spyCategoryNodePageSearchEntities as $spyCategoryNodePageSearchEntity) {
            $spyCategoryNodePageSearchEntity->delete();
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
                } else {
                    $this->storeDataSet($categoryTreeWithLocale, $localeName);
                }
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
    protected function storeDataSet(SpyCategoryNode $spyCategoryNodeEntity, $localeName, SpyCategoryNodePageSearch $spyCategoryNodePageSearchEntity = null)
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

        $categoryTreeNodeData = $this->getFactory()->getUtilSanitizeService()->arrayFilterRecursive($spyCategoryNodeEntity->toArray(TableMap::TYPE_FIELDNAME, true, [], true));
        $data = $this->mapToSearchData($categoryTreeNodeData, $localeName);
        $spyCategoryNodePageSearchEntity->setFkCategoryNode($spyCategoryNodeEntity->getIdCategoryNode());
        $spyCategoryNodePageSearchEntity->setStructuredData($this->getFactory()->getUtilEncoding()->encodeJson($categoryTreeNodeData));
        $spyCategoryNodePageSearchEntity->setData($data);
        $spyCategoryNodePageSearchEntity->setStore($this->getStore()->getStoreName());
        $spyCategoryNodePageSearchEntity->setLocale($localeName);
        $spyCategoryNodePageSearchEntity->save();
    }

    /**
     * @param array $categoryNodeData
     * @param string $localeName
     *
     * @return mixed
     */
    public function mapToSearchData(array $categoryNodeData, $localeName)
    {
        return $this->getFactory()->getSearchFacade()
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
        $categoryNodeSearchEntities = $this->getQueryContainer()->queryCategoryNodePageSearchByIds($categoryNodeIds)->find();
        $categoryNodeSearchEntitiesByIdAndLocale = [];
        foreach ($categoryNodeSearchEntities as $categoryNodeSearchEntity) {
            $categoryNodeSearchEntitiesByIdAndLocale[$categoryNodeSearchEntity->getFkCategoryNode()][$categoryNodeSearchEntity->getLocale()] = $categoryNodeSearchEntity;
        }

        return $categoryNodeSearchEntitiesByIdAndLocale;
    }

    /**
     * @param array $categoryNodeIds
     *
     * @return array
     */
    protected function getCategoryTrees(array $categoryNodeIds)
    {
        $localeNames = $this->getStore()->getLocales();
        $locales = $this->getQueryContainer()->queryLocalesWithLocaleNames($localeNames)->find();

        $categoryNodeTree = [];
        $this->disableInstancePooling();
        foreach ($locales as $locale) {
            $categoryNodes = $this->getQueryContainer()->queryCategoryNodeTree($categoryNodeIds, $locale->getIdLocale())->find()->toKeyIndex();
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
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getFactory()->getStore();
    }
}
