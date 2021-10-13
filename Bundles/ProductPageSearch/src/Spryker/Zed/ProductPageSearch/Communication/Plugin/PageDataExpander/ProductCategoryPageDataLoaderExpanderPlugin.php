<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\PageDataExpander;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryQueryContainer;
use Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\ProductCategorySearch\Communication\Plugin\ProductPageSearch\ProductCategoryPageDataExpanderPlugin} instead.
 *
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 */
class ProductCategoryPageDataLoaderExpanderPlugin extends AbstractPlugin implements ProductPageDataExpanderInterface
{
    /**
     * @var string
     */
    public const RESULT_FIELD_PRODUCT_ORDER = 'product_order';

    /**
     * @var string
     */
    protected const KEY_FK_LOCALE = 'fk_locale';
    /**
     * @var string
     */
    protected const KEY_FK_CATEGORY_NODE_DESCENDANT = 'fk_category_node_descendant';
    /**
     * @var string
     */
    protected const KEY_CATEGORY_NAME = 'category_name';

    /**
     * @var array
     */
    protected static $categoryTree;

    /**
     * @var array
     */
    protected static $categoryNames = [];

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $productData
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     *
     * @return void
     */
    public function expandProductPageData(array $productData, ProductPageSearchTransfer $productAbstractPageSearchTransfer)
    {
        $productCategoryEntities = $productData[ProductPageSearchConfig::PRODUCT_ABSTRACT_PAGE_LOAD_DATA]->getCategories();
        $allParentCategoryNodesIds = [];
        $localeTransfer = (new LocaleTransfer())
            ->setIdLocale($productData['Locale']['id_locale']);
        foreach ($productAbstractPageSearchTransfer->getCategoryNodeIds() as $idCategoryNode) {
            $allParentCategoryNodesIds = array_merge(
                $allParentCategoryNodesIds,
                $this->getAllParents($idCategoryNode, $localeTransfer)
            );
        }

        $allParentCategoryNodesIds = array_values(array_unique($allParentCategoryNodesIds));
        $productAbstractPageSearchTransfer->setAllParentCategoryIds($allParentCategoryNodesIds);

        $this->setCategoryNames(
            $allParentCategoryNodesIds,
            $productAbstractPageSearchTransfer->getCategoryNodeIds(),
            $localeTransfer,
            $productAbstractPageSearchTransfer
        );

        $this->setSorting(
            $allParentCategoryNodesIds,
            $localeTransfer,
            $productAbstractPageSearchTransfer,
            $productCategoryEntities
        );
    }

    /**
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array<int>
     */
    protected function getAllParents(int $idCategoryNode, LocaleTransfer $localeTransfer): array
    {
        if (static::$categoryTree === null) {
            $this->loadTree($localeTransfer);
        }

        return static::$categoryTree[$idCategoryNode];
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function loadTree(LocaleTransfer $localeTransfer): void
    {
        static::$categoryTree = [];

        $categoryNodeEntityCollection = $this->getFactory()->getCategoryQueryContainer()
            ->queryCategoryNode($localeTransfer->getIdLocale())
            ->find();

        $categoryNodesData = $this->getQueryContainer()->queryAllCategoriesWithAttributesAndOrderByDescendant()->find();
        $mappedCategoryNodesDataByLocaleIdAndNodeId = $this->mapCategoryNodesWithLocaleIdAndNodeId($categoryNodesData);

        foreach ($categoryNodeEntityCollection as $categoryNodeEntity) {
            $pathData = $mappedCategoryNodesDataByLocaleIdAndNodeId[$localeTransfer->getIdLocale()][$categoryNodeEntity->getIdCategoryNode()] ?? [];
            static::$categoryTree[$categoryNodeEntity->getIdCategoryNode()] = [];

            foreach ($pathData as $path) {
                $idCategoryNode = (int)$path['id_category_node'];
                if (!in_array($idCategoryNode, static::$categoryTree[$categoryNodeEntity->getIdCategoryNode()])) {
                    static::$categoryTree[$categoryNodeEntity->getIdCategoryNode()][] = $idCategoryNode;
                }
            }
        }
    }

    /**
     * @param array<int> $allParentCategoryNodeIds
     * @param array<int> $allCategoryNodeIds
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     *
     * @return void
     */
    protected function setCategoryNames(
        array $allParentCategoryNodeIds,
        array $allCategoryNodeIds,
        LocaleTransfer $localeTransfer,
        ProductPageSearchTransfer $productAbstractPageSearchTransfer
    ) {
        $boostedCategoryNames = [];

        foreach ($allCategoryNodeIds as $idCategoryNode) {
            $boostedName = $this->getName($idCategoryNode, $localeTransfer);

            if ($boostedName !== null) {
                $boostedCategoryNames[$idCategoryNode] = $boostedName;
            }
        }
        $productAbstractPageSearchTransfer->setBoostedCategoryNames($boostedCategoryNames);

        $categoryNames = [];
        foreach ($allParentCategoryNodeIds as $idCategoryNode) {
            if (in_array($idCategoryNode, $allCategoryNodeIds)) {
                continue;
            }

            $categoryName = $this->getName($idCategoryNode, $localeTransfer);
            if ($categoryName !== null) {
                $categoryNames[$idCategoryNode] = $categoryName;
            }
        }
        $productAbstractPageSearchTransfer->setCategoryNames($categoryNames);
    }

    /**
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    protected function getName($idCategoryNode, LocaleTransfer $localeTransfer)
    {
        $idLocale = $localeTransfer->getIdLocale();
        if (!isset(static::$categoryNames[$idLocale])) {
            $this->loadNames($localeTransfer);
        }

        return static::$categoryNames[$idLocale][$idCategoryNode] ?? null;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function loadNames(LocaleTransfer $localeTransfer)
    {
        $categoryNodeEntityCollection = $this->getFactory()
            ->getCategoryQueryContainer()
            ->queryCategoryNode($localeTransfer->getIdLocale())
            ->useCategoryQuery()
                ->filterByIsSearchable(true)
            ->endUse()
            ->find();

        static::$categoryNames[$localeTransfer->getIdLocale()] = $this->mapCategoryNamesIndexedByCategoryNodeIds($categoryNodeEntityCollection);
    }

    /**
     * @param array<int> $directParentCategories
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     * @param array $productCategoryEntities
     *
     * @return void
     */
    protected function setSorting(
        array $directParentCategories,
        LocaleTransfer $localeTransfer,
        ProductPageSearchTransfer $productAbstractPageSearchTransfer,
        array $productCategoryEntities
    ) {
        $maxProductOrder = (pow(2, 31) - 1);

        $filteredProductCategoriesByDirectParents = [];
        if ($productCategoryEntities) {
            foreach ($productCategoryEntities as $productCategory) {
                if (in_array($productCategory->getVirtualColumn('id_category_node'), $directParentCategories)) {
                    $filteredProductCategoriesByDirectParents[] = $productCategory;
                }
            }
        }

        $sortedCategories = [];
        foreach ($filteredProductCategoriesByDirectParents as $productCategoryEntity) {
            $idCategoryNode = $productCategoryEntity->getVirtualColumn(
                ProductCategoryQueryContainer::VIRTUAL_COLUMN_ID_CATEGORY_NODE
            );

            $productOrder = (int)$productCategoryEntity->getProductOrder() ?: $maxProductOrder;
            $sortedCategories[$idCategoryNode]['product_order'] = $productOrder;
            $allNodeParents = $this->getAllParents($idCategoryNode, $localeTransfer);
            $sortedCategories[$idCategoryNode]['all_node_parents'] = $allNodeParents;
        }
        $productAbstractPageSearchTransfer->setSortedCategories($sortedCategories);
    }

    /**
     * @param array $categoryNodeData
     *
     * @return array
     */
    protected function mapCategoryNodesWithLocaleIdAndNodeId(array $categoryNodeData): array
    {
        $categoryNodesMap = [];

        foreach ($categoryNodeData as $categoryNodeDatum) {
            $categoryNodesMap[$categoryNodeDatum[static::KEY_FK_LOCALE]][$categoryNodeDatum[static::KEY_FK_CATEGORY_NODE_DESCENDANT]][] = $categoryNodeDatum;
        }

        return $categoryNodesMap;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $categoryNodeEntityCollection
     *
     * @return array<string>
     */
    protected function mapCategoryNamesIndexedByCategoryNodeIds(ObjectCollection $categoryNodeEntityCollection): array
    {
        $categoryNames = [];
        foreach ($categoryNodeEntityCollection as $categoryNodeEntity) {
            $categoryNames[$categoryNodeEntity->getIdCategoryNode()] = $categoryNodeEntity->getVirtualColumn(static::KEY_CATEGORY_NAME);
        }

        return $categoryNames;
    }
}
