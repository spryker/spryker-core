<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Table;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Spryker\Shared\Url\Url;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class RootNodeTable extends AbstractTable
{

    const TABLE_IDENTIFIER = 'root-node-table';

    const ID_CATEGORY_NODE = 'id_category_node';
    const LOCALE_NAME = 'locale_name';
    const COL_REORDER = 'Reorder';
    const URL_CATEGORY_RE_SORT= '/category/re-sort';
    const PARAM_ID_NODE = 'id-node';
    const URL_PRODUCT_CATEGORY_ADD = '/category/create';
    const PARAM_ID_PARENT_NODE = 'id-parent-node';

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
            SpyLocaleTableMap::COL_LOCALE_NAME => 'Locale',
            self::COL_REORDER => '',
        ]);

        $config->addRawColumn(self::COL_REORDER);

        $config->setSortable([
            SpyLocaleTableMap::COL_LOCALE_NAME,
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
                SpyLocaleTableMap::COL_LOCALE_NAME => $rootNode[self::LOCALE_NAME],
                self::COL_REORDER => implode(' ', $this->createActionButtons($rootNode)),
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

        $urls[] = $this->generateCreateButton(
            Url::generate(self::URL_PRODUCT_CATEGORY_ADD, [
                self::PARAM_ID_PARENT_NODE => $rootNode[self::ID_CATEGORY_NODE],
            ]),
            '<i class="fa fa-plus"></i>',
            [
                self::BUTTON_ICON => null,
                'title' => 'Add Category to this node'
            ]
        );

        $urls[] = $this->generateViewButton(
            Url::generate(self::URL_CATEGORY_RE_SORT, [
                self::PARAM_ID_NODE => $rootNode[self::ID_CATEGORY_NODE],
            ]),
            '<i class="fa fa-sitemap"></i>',
            [
                self::BUTTON_ICON => null,
                'id' => sprintf('node-%d', $rootNode[self::ID_CATEGORY_NODE]),
                'title' => 'Reorder assigned Categories'
            ]
        );

        return $urls;
    }

}
