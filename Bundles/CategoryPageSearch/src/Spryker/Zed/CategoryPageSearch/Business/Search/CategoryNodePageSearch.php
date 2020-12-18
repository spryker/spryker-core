<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Business\Search;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\CategoryPageSearch\Persistence\SpyCategoryNodePageSearch;
use Spryker\Zed\CategoryPageSearch\Business\Search\DataMapper\CategoryNodePageSearchDataMapperInterface;
use Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToCategoryFacadeInterface;
use Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToStoreFacadeInterface;
use Spryker\Zed\CategoryPageSearch\Dependency\Service\CategoryPageSearchToUtilEncodingInterface;
use Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchQueryContainerInterface;

class CategoryNodePageSearch implements CategoryNodePageSearchInterface
{
    /**
     * @var \Spryker\Zed\CategoryPageSearch\Dependency\Service\CategoryPageSearchToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\CategoryPageSearch\Business\Search\DataMapper\CategoryNodePageSearchDataMapperInterface
     */
    protected $categoryNodePageSearchDataMapper;

    /**
     * @var \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @param \Spryker\Zed\CategoryPageSearch\Dependency\Service\CategoryPageSearchToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Zed\CategoryPageSearch\Business\Search\DataMapper\CategoryNodePageSearchDataMapperInterface $categoryNodePageSearchDataMapper
     * @param \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToCategoryFacadeInterface $categoryFacade
     */
    public function __construct(
        CategoryPageSearchToUtilEncodingInterface $utilEncodingService,
        CategoryNodePageSearchDataMapperInterface $categoryNodePageSearchDataMapper,
        CategoryPageSearchQueryContainerInterface $queryContainer,
        CategoryPageSearchToStoreFacadeInterface $storeFacade,
        CategoryPageSearchToCategoryFacadeInterface $categoryFacade
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->categoryNodePageSearchDataMapper = $categoryNodePageSearchDataMapper;
        $this->queryContainer = $queryContainer;
        $this->storeFacade = $storeFacade;
        $this->categoryFacade = $categoryFacade;
    }

    /**
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function publish(array $categoryNodeIds)
    {
        $nodeTransfers = $this->categoryFacade->getAllCategoryNodeTreeElementsByCategoryNodeIds($categoryNodeIds);
        $categoryNodePageSearchEntities = $this->findCategoryNodePageSearchEntitiesByCategoryNodeIds($categoryNodeIds);

        if (!$nodeTransfers) {
            $this->deleteSearchData($categoryNodePageSearchEntities);
        }

        $indexedCategoryNodePageSearchEntities = $this->indexCategoryNodePageSearchEntitiesByStoreAndLocaleAndIdCategoryNode(
            $categoryNodePageSearchEntities
        );
        $this->storeData($nodeTransfers, $indexedCategoryNodePageSearchEntities);
    }

    /**
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function unpublish(array $categoryNodeIds)
    {
        $spyCategoryNodePageSearchEntities = $this->findCategoryNodePageSearchEntitiesByCategoryNodeIds($categoryNodeIds);

        $this->deleteSearchData($spyCategoryNodePageSearchEntities);
    }

    /**
     * @param \Orm\Zed\CategoryPageSearch\Persistence\SpyCategoryNodePageSearch[] $spyCategoryNodePageSearchEntities
     *
     * @return void
     */
    protected function deleteSearchData(array $spyCategoryNodePageSearchEntities): void
    {
        foreach ($spyCategoryNodePageSearchEntities as $spyCategoryNodePageSearchEntity) {
            $spyCategoryNodePageSearchEntity->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer[] $nodeTransfers
     * @param \Orm\Zed\CategoryPageSearch\Persistence\SpyCategoryNodePageSearch[][][] $categoryNodePageSearchEntities
     *
     * @return void
     */
    protected function storeData(array $nodeTransfers, array $categoryNodePageSearchEntities): void
    {
        $localeNameMapByStoreName = $this->getLocaleNameMapByStoreName();
        foreach ($localeNameMapByStoreName as $storeName => $localeNames) {
            foreach ($localeNames as $localeName) {
                $this->storeDataForStoreAndLocale($nodeTransfers, $categoryNodePageSearchEntities, $storeName, $localeName);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer[] $nodeTransfers
     * @param \Orm\Zed\CategoryPageSearch\Persistence\SpyCategoryNodePageSearch[][][] $categoryNodePageSearchEntities
     * @param string $storeName
     * @param string $localeName
     *
     * @return void
     */
    protected function storeDataForStoreAndLocale(
        array $nodeTransfers,
        array $categoryNodePageSearchEntities,
        string $storeName,
        string $localeName
    ): void {
        foreach ($nodeTransfers as $nodeTransfer) {
            if (!$this->isCategoryHasStoreRelation($nodeTransfer->getCategoryOrFail(), $storeName)) {
                continue;
            }

            if (isset($categoryNodePageSearchEntities[$storeName][$localeName][$nodeTransfer->getIdCategoryNode()])) {
                $this->storeDataSet(
                    $nodeTransfer,
                    $storeName,
                    $localeName,
                    $categoryNodePageSearchEntities[$storeName][$localeName][$nodeTransfer->getIdCategoryNode()]
                );

                continue;
            }

            $this->storeDataSet($nodeTransfer, $storeName, $localeName);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param string $storeName
     * @param string $localeName
     * @param \Orm\Zed\CategoryPageSearch\Persistence\SpyCategoryNodePageSearch|null $spyCategoryNodePageSearchEntity
     *
     * @return void
     */
    protected function storeDataSet(
        NodeTransfer $nodeTransfer,
        string $storeName,
        string $localeName,
        ?SpyCategoryNodePageSearch $spyCategoryNodePageSearchEntity = null
    ): void {
        if ($spyCategoryNodePageSearchEntity === null) {
            $spyCategoryNodePageSearchEntity = new SpyCategoryNodePageSearch();
        }

        if (!$nodeTransfer->getCategoryOrFail()->getIsActive()) {
            if (!$spyCategoryNodePageSearchEntity->isNew()) {
                $spyCategoryNodePageSearchEntity->delete();
            }

            return;
        }

        $data = $this->categoryNodePageSearchDataMapper
            ->mapNodeTransferToCategoryNodePageSearchDataForStoreAndLocale($nodeTransfer, $storeName, $localeName);
        $spyCategoryNodePageSearchEntity->setFkCategoryNode($nodeTransfer->getIdCategoryNode());
        $spyCategoryNodePageSearchEntity->setStructuredData($this->utilEncodingService->encodeJson($nodeTransfer->toArray()));
        $spyCategoryNodePageSearchEntity->setData($data);
        $spyCategoryNodePageSearchEntity->setLocale($localeName);
        $spyCategoryNodePageSearchEntity->save();
    }

    /**
     * @param int[] $categoryNodeIds
     *
     * @return \Orm\Zed\CategoryPageSearch\Persistence\SpyCategoryNodePageSearch[]
     */
    protected function findCategoryNodePageSearchEntitiesByCategoryNodeIds(array $categoryNodeIds): array
    {
        return $this->queryContainer->queryCategoryNodePageSearchByIds($categoryNodeIds)->find()->getArrayCopy();
    }

    /**
     * @param \Orm\Zed\CategoryPageSearch\Persistence\SpyCategoryNodePageSearch[] $categoryNodeSearchEntities
     *
     * @return \Orm\Zed\CategoryPageSearch\Persistence\SpyCategoryNodePageSearch[][][]
     */
    protected function indexCategoryNodePageSearchEntitiesByStoreAndLocaleAndIdCategoryNode(array $categoryNodeSearchEntities): array
    {
        $indexedCategoryNodeSearchEntities = [];
        foreach ($categoryNodeSearchEntities as $categoryNodeSearchEntity) {
            $indexedCategoryNodeSearchEntities[$categoryNodeSearchEntity->getStore()][$categoryNodeSearchEntity->getLocale()][$categoryNodeSearchEntity->getFkCategoryNode()] = $categoryNodeSearchEntity;
        }

        return $indexedCategoryNodeSearchEntities;
    }

    /**
     * @return string[][]
     */
    protected function getLocaleNameMapByStoreName(): array
    {
        $localeNameMapByStoreName = [];
        foreach ($this->storeFacade->getAllStores() as $storeTransfer) {
            $localeNameMapByStoreName[$storeTransfer->getName()] = $storeTransfer->getAvailableLocaleIsoCodes();
        }

        return $localeNameMapByStoreName;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param string $storeName
     *
     * @return bool
     */
    protected function isCategoryHasStoreRelation(CategoryTransfer $categoryTransfer, string $storeName): bool
    {
        foreach ($categoryTransfer->getStoreRelationOrFail()->getStores() as $storeTransfer) {
            if ($storeTransfer->getName() === $storeName) {
                return true;
            }
        }

        return false;
    }
}
