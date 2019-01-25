<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Table;

use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\Category\CategoryConstants;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface;
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
    public const COL_ACTIONS = 'actions';
    public const IDENTIFIER = 'category_data_table';

    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(CategoryGuiToLocaleFacadeInterface $localeFacade)
    {
        $this->localeFacade = $localeFacade;
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
            'attr.name',
        ]);

        $config->setRawColumns([
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

        $categoryCollection = [];

        foreach ($queryResults as $categoryEntity) {
            $categoryCollection[] = $this->generateItem($categoryEntity);
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
        $query->having('node.is_main = ?', true)
            ->withColumn('count(node.fk_category)', 'count')
            ->withColumn('attr.name', static::COL_NAME)
            ->withColumn('tpl.name', static::COL_TEMPLATE)
            ->withColumn('parent_attr.name', static::COL_PARENT);

        return $query;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     *
     * @return array
     */
    protected function generateItem(SpyCategory $categoryEntity): array
    {
        return [
            static::COL_CATEGORY_KEY => $categoryEntity->getCategoryKey(),
            static::COL_NAME => $categoryEntity->getVirtualColumn(static::COL_NAME),
            static::COL_PARENT => $categoryEntity->getVirtualColumn(static::COL_PARENT),
            static::COL_ACTIVE => $this->yesNoOutput($categoryEntity->getIsActive()),
            static::COL_VISIBLE => $this->yesNoOutput($categoryEntity->getIsInMenu()),
            static::COL_SEARCHABLE => $this->yesNoOutput($categoryEntity->getIsSearchable()),
            static::COL_TEMPLATE => $categoryEntity->getVirtualColumn(static::COL_TEMPLATE),
            static::COL_ACTIONS => implode(' ', $this->createActionColumn($categoryEntity)),
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
     * @return string[]
     */
    protected function createActionColumn(SpyCategory $item): array
    {
        $urls = [];

        $urls[] = $this->generateViewButton(
            Url::generate('/product-category/assign', [
                CategoryConstants::PARAM_ID_CATEGORY => $item->getIdCategory(),
            ]),
            'Assign products'
        );

        $urls[] = $this->generateEditButton(
            Url::generate('/category/edit', [
                CategoryConstants::PARAM_ID_CATEGORY => $item->getIdCategory(),
            ]),
            'Edit'
        );

        $urls[] = $this->generateViewButton(
            Url::generate('/category/view', [
                CategoryConstants::PARAM_ID_CATEGORY => $item->getIdCategory(),
            ]),
            'View'
        );

        $urls[] = $this->generateRemoveButton(
            Url::generate('/category/delete', [
                CategoryConstants::PARAM_ID_CATEGORY => $item->getIdCategory(),
            ]),
            'Delete'
        );

        return $urls;
    }
}
