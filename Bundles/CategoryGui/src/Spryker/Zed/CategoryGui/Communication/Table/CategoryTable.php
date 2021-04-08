<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Table;

use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface;
use Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class CategoryTable extends AbstractTable
{
    public const COL_CATEGORY_KEY = 'category_key';
    public const COL_NAME = 'name';
    public const COL_PARENT = 'parent_name';
    public const COL_ACTIVE = 'is_active';
    public const COL_VISIBLE = 'is_in_menu';
    public const COL_SEARCHABLE = 'is_searchable';
    public const COL_TEMPLATE = 'template';
    public const COL_STORE_RELATION = 'store_relation';
    public const COL_ACTIONS = 'actions';
    public const IDENTIFIER = 'category_data_table';
    public const COL_ID_CATEGORY_NODE = 'id_category_node';

    protected const REQUEST_PARAM_ID_CATEGORY = 'id-category';
    protected const REQUEST_PARAM_ID_NODE = 'id-node';
    protected const REQUEST_PARAM_ID_PARENT_NODE = 'id-parent-node';

    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface
     */
    protected $categoryGuiRepository;

    /**
     * @param \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface $categoryGuiRepository
     */
    public function __construct(
        CategoryGuiToLocaleFacadeInterface $localeFacade,
        CategoryGuiRepositoryInterface $categoryGuiRepository
    ) {
        $this->localeFacade = $localeFacade;
        $this->categoryGuiRepository = $categoryGuiRepository;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            static::COL_CATEGORY_KEY => 'Category Key',
            static::COL_NAME => 'Name',
            static::COL_PARENT => 'Parent',
            static::COL_ACTIVE => 'Active',
            static::COL_VISIBLE => 'Visible',
            static::COL_SEARCHABLE => 'Searchable',
            static::COL_TEMPLATE => 'Template',
            static::COL_STORE_RELATION => 'Stores',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->setDefaultSortField(static::COL_CATEGORY_KEY);

        $config->setSortable([
            static::COL_CATEGORY_KEY,
            static::COL_NAME,
            static::COL_PARENT,
            static::COL_ACTIVE,
            static::COL_VISIBLE,
            static::COL_SEARCHABLE,
            static::COL_TEMPLATE,
        ]);

        $config->setSearchable([
            SpyCategoryTableMap::COL_CATEGORY_KEY,
            'attr.name',
        ]);

        $config->setRawColumns([
            static::COL_STORE_RELATION,
            static::COL_ACTIONS,
        ]);

        $this->setTableIdentifier(static::IDENTIFIER);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $fkLocale = $this->localeFacade->getCurrentLocale()->getIdLocale();
        $query = $this->prepareQuery($fkLocale);

        $queryResults = $this->runQuery($query, $config, true);

        $categoryIds = $this->extractCategoryIds($queryResults);
        $categoryStoreNamesGroupedByIdCategory = $this->categoryGuiRepository->getCategoryStoreNamesGroupedByIdCategory($categoryIds);

        $categoryCollection = [];
        foreach ($queryResults as $categoryEntity) {
            $categoryCollection[] = $this->generateItem($categoryEntity, $categoryStoreNamesGroupedByIdCategory);
        }

        return $categoryCollection;
    }

    /**
     * @param int $fkLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    protected function prepareQuery(int $fkLocale): SpyCategoryQuery
    {
        $query = SpyCategoryQuery::create('sc')
            ->useAttributeQuery('attr', Criteria::LEFT_JOIN)
                ->filterByFkLocale($fkLocale)
            ->endUse()
            ->leftJoinCategoryTemplate('tpl')
            ->leftJoinNode('node')
            ->useNodeQuery('node', Criteria::LEFT_JOIN)
                ->groupByFkCategory()
                ->groupByIsMain()
                ->useParentCategoryNodeQuery('parent_node')
                    ->useCategoryQuery('sc_parent', Criteria::LEFT_JOIN)
                        ->useAttributeQuery('parent_attr', Criteria::LEFT_JOIN)
                            ->filterByFkLocale($fkLocale)
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->addOr('parent_node.id_category_node');
        /** @var \Orm\Zed\Category\Persistence\SpyCategoryQuery $query */
        $query = $query->having('node.is_main = ?', true)
            ->withColumn('count(node.fk_category)', 'count')
            ->withColumn('attr.name', static::COL_NAME)
            ->withColumn('tpl.name', static::COL_TEMPLATE)
            ->withColumn('parent_attr.name', static::COL_PARENT)
            ->withColumn('node.id_category_node', static::COL_ID_CATEGORY_NODE);

        return $query;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     * @param string[][] $categoryStoreNamesGroupedByIdCategory
     *
     * @return array
     */
    protected function generateItem(SpyCategory $categoryEntity, array $categoryStoreNamesGroupedByIdCategory): array
    {
        return [
            static::COL_CATEGORY_KEY => $categoryEntity->getCategoryKey(),
            static::COL_NAME => $categoryEntity->getVirtualColumn(static::COL_NAME),
            static::COL_PARENT => $categoryEntity->getVirtualColumn(static::COL_PARENT),
            static::COL_ACTIVE => $this->yesNoOutput($categoryEntity->getIsActive()),
            static::COL_VISIBLE => $this->yesNoOutput($categoryEntity->getIsInMenu()),
            static::COL_SEARCHABLE => $this->yesNoOutput($categoryEntity->getIsSearchable()),
            static::COL_TEMPLATE => $categoryEntity->getVirtualColumn(static::COL_TEMPLATE),
            static::COL_STORE_RELATION => $this->getStoreNames($categoryEntity->getIdCategory(), $categoryStoreNamesGroupedByIdCategory),
            static::COL_ACTIONS => $this->generateActionsButton($categoryEntity),
        ];
    }

    /**
     * @param bool $condition
     *
     * @return string
     */
    protected function yesNoOutput(bool $condition): string
    {
        if ($condition === true) {
            return 'Yes';
        }

        return 'No';
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $item
     *
     * @return string
     */
    protected function generateActionsButton(SpyCategory $item): string
    {
        $buttonGroupItems = [];

        $buttonGroupItems[] = $this->generateEditCategoryButtonGroupItem($item);
        $buttonGroupItems[] = $this->generateCategoryRemoveButtonGroupItem($item);
        $buttonGroupItems[] = $this->generateAddCategoryToNodeButtonGroupItem($item);
        $buttonGroupItems[] = $this->generateCategoryResortButtonGroupItem($item);
        $buttonGroupItems[] = $this->generateAssignProductsButtonGroupItem($item);

        return $this->generateButtonGroup(
            $buttonGroupItems,
            'Actions',
            [
                'icon' => '',
            ]
        );
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $item
     *
     * @return array
     */
    protected function generateAssignProductsButtonGroupItem(SpyCategory $item): array
    {
        return $this->createButtonGroupItem(
            'Assign products',
            Url::generate('/product-category/assign', [
                static::REQUEST_PARAM_ID_CATEGORY => $item->getIdCategory(),
            ]),
            true
        );
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $item
     *
     * @return array
     */
    protected function generateEditCategoryButtonGroupItem(SpyCategory $item): array
    {
        return $this->createButtonGroupItem(
            'Edit',
            Url::generate('/category-gui/edit', [
                static::REQUEST_PARAM_ID_CATEGORY => $item->getIdCategory(),
            ])
        );
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $item
     *
     * @return array
     */
    protected function generateCategoryRemoveButtonGroupItem(SpyCategory $item): array
    {
        return $this->createButtonGroupItem(
            'Delete',
            Url::generate('/category-gui/delete', [
                static::REQUEST_PARAM_ID_CATEGORY => $item->getIdCategory(),
            ])
        );
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $item
     *
     * @return array
     */
    protected function generateCategoryResortButtonGroupItem(SpyCategory $item): array
    {
        return $this->createButtonGroupItem(
            'Re-sort child categories',
            Url::generate('/category-gui/re-sort', [
                static::REQUEST_PARAM_ID_NODE => $item->getVirtualColumn(static::COL_ID_CATEGORY_NODE),
            ])
        );
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $item
     *
     * @return array
     */
    protected function generateAddCategoryToNodeButtonGroupItem(SpyCategory $item): array
    {
        return $this->createButtonGroupItem(
            'Add category to this node',
            Url::generate('/category-gui/create', [
                static::REQUEST_PARAM_ID_PARENT_NODE => $item->getVirtualColumn(static::COL_ID_CATEGORY_NODE),
            ]),
            true
        );
    }

    /**
     * @param int $idCategory
     * @param string[][] $categoryStoreNamesGroupedByIdCategory
     *
     * @return string
     */
    protected function getStoreNames(int $idCategory, array $categoryStoreNamesGroupedByIdCategory): string
    {
        if (!array_key_exists($idCategory, $categoryStoreNamesGroupedByIdCategory)) {
            return '';
        }

        $storeNames = [];
        foreach ($categoryStoreNamesGroupedByIdCategory[$idCategory] as $storeName) {
            $storeNames[] = sprintf(
                '<span class="label label-info">%s</span>',
                $storeName
            );
        }

        return implode(' ', $storeNames);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $queryResults
     *
     * @return int[]
     */
    private function extractCategoryIds(ObjectCollection $queryResults): array
    {
        $categoryIds = [];
        /** @var \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity */
        foreach ($queryResults as $categoryEntity) {
            $categoryIds[] = $categoryEntity->getIdCategory();
        }

        return $categoryIds;
    }
}
