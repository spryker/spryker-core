<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Table;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface;
use Spryker\Zed\CategoryGui\Dependency\QueryContainer\CategoryGuiToCategoryQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class RootNodeTable extends AbstractTable
{
    public const TABLE_IDENTIFIER = 'root-node-table';

    public const ID_CATEGORY_NODE = 'id_category_node';
    public const LOCALE_NAME = 'locale_name';
    public const COL_ACTIONS = 'actions';
    public const URL_CATEGORY_RE_SORT = '/category-gui/re-sort';
    public const URL_PRODUCT_CATEGORY_ADD = '/category-gui/create';

    protected const REQUEST_PARAM_ID_NODE = 'id-node';
    protected const REQUEST_PARAM_ID_PARENT_NODE = 'id-parent-node';

    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\QueryContainer\CategoryGuiToCategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\CategoryGui\Dependency\QueryContainer\CategoryGuiToCategoryQueryContainerInterface $categoryQueryContainer
     * @param \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        CategoryGuiToCategoryQueryContainerInterface $categoryQueryContainer,
        CategoryGuiToLocaleFacadeInterface $localeFacade
    ) {
        $this->categoryQueryContainer = $categoryQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->defaultUrl = 'root-node-table';
        $this->setTableIdentifier(static::TABLE_IDENTIFIER);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $this->tableClass = 'gui-table-data-category';

        $config->setHeader([
            SpyCategoryAttributeTableMap::COL_FK_CATEGORY => 'Category Id',
            SpyCategoryAttributeTableMap::COL_NAME => 'Name',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->addRawColumn(static::COL_ACTIONS);

        $config->setSortable([
            SpyCategoryAttributeTableMap::COL_NAME,
        ]);

        $config->setSearchable([
            SpyCategoryAttributeTableMap::COL_FK_CATEGORY,
            SpyCategoryAttributeTableMap::COL_NAME,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $idLocale = $this->localeFacade->getCurrentLocale()->getIdLocale();

        $query = $this->categoryQueryContainer->queryRootNodes()
            ->orderBy(SpyCategoryAttributeTableMap::COL_NAME)
            ->setModelAlias('spy_locale')
            ->filterByFkLocale($idLocale);

        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $rootNode) {
            $results[] = [
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY => $rootNode[SpyCategoryAttributeTableMap::COL_FK_CATEGORY],
                SpyCategoryAttributeTableMap::COL_NAME => $rootNode[SpyCategoryAttributeTableMap::COL_NAME],
                static::COL_ACTIONS => implode(' ', $this->createActionButtons($rootNode)),
            ];
        }
        unset($queryResults);

        return $results;
    }

    /**
     * @param array $rootNode
     *
     * @return array
     */
    protected function createActionButtons(array $rootNode): array
    {
        $urls = [];

        $urls[] = $this->generateViewButton(
            Url::generate(static::URL_CATEGORY_RE_SORT, [
                static::REQUEST_PARAM_ID_NODE => $rootNode[static::ID_CATEGORY_NODE],
            ]),
            '<i class="fa fa-fw fa-arrows-v"></i>',
            [
                static::BUTTON_ICON => null,
                'id' => sprintf('node-%d', $rootNode[static::ID_CATEGORY_NODE]),
                'title' => 'Re-sort Child-Categories',
            ]
        );

        $urls[] = $this->generateCreateButton(
            Url::generate(static::URL_PRODUCT_CATEGORY_ADD, [
                static::REQUEST_PARAM_ID_PARENT_NODE => $rootNode[static::ID_CATEGORY_NODE],
            ]),
            '<i class="fa fa-fw fa-plus"></i>',
            [
                static::BUTTON_ICON => null,
                'title' => 'Add Category to this Node',
            ]
        );

        return $urls;
    }
}
