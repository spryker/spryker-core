<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Table;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\Category\CategoryConstants;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class RootNodeTable extends AbstractTable
{
    public const TABLE_IDENTIFIER = 'root-node-table';

    public const ID_CATEGORY_NODE = 'id_category_node';
    public const LOCALE_NAME = 'locale_name';
    public const COL_ACTIONS = 'actions';
    public const URL_CATEGORY_RE_SORT = '/category/re-sort';
    public const URL_PRODUCT_CATEGORY_ADD = '/category/create';

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @var int
     */
    protected $idLocale;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $productCategoryQueryContainer
     * @param int $idLocale
     */
    public function __construct(CategoryQueryContainerInterface $productCategoryQueryContainer, $idLocale)
    {
        $this->categoryQueryContainer = $productCategoryQueryContainer;
        $this->idLocale = $idLocale;
        $this->defaultUrl = 'rootNodeTable';
        $this->setTableIdentifier(self::TABLE_IDENTIFIER);
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
            self::COL_ACTIONS => 'Actions',
        ]);

        $config->addRawColumn(self::COL_ACTIONS);

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
        $query = $this->categoryQueryContainer->queryRootNodes()
            ->orderBy(SpyCategoryAttributeTableMap::COL_NAME)
            ->setModelAlias('spy_locale')
            ->filterByFkLocale($this->idLocale);

        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $rootNode) {
            $results[] = [
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY => $rootNode[SpyCategoryAttributeTableMap::COL_FK_CATEGORY],
                SpyCategoryAttributeTableMap::COL_NAME => $rootNode[SpyCategoryAttributeTableMap::COL_NAME],
                self::COL_ACTIONS => implode(' ', $this->createActionButtons($rootNode)),
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
    protected function createActionButtons(array $rootNode)
    {
        $urls = [];

        $urls[] = $this->generateViewButton(
            Url::generate(self::URL_CATEGORY_RE_SORT, [
                CategoryConstants::PARAM_ID_NODE => $rootNode[self::ID_CATEGORY_NODE],
            ]),
            '<i class="fa fa-fw fa-arrows-v"></i>',
            [
                self::BUTTON_ICON => null,
                'id' => sprintf('node-%d', $rootNode[self::ID_CATEGORY_NODE]),
                'title' => 'Re-sort Child-Categories',
            ]
        );

        $urls[] = $this->generateCreateButton(
            Url::generate(self::URL_PRODUCT_CATEGORY_ADD, [
                CategoryConstants::PARAM_ID_PARENT_NODE => $rootNode[self::ID_CATEGORY_NODE],
            ]),
            '<i class="fa fa-fw fa-plus"></i>',
            [
                self::BUTTON_ICON => null,
                'title' => 'Add Category to this Node',
            ]
        );

        return $urls;
    }
}
