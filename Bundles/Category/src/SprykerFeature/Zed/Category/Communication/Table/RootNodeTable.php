<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Communication\Table;

use SprykerEngine\Zed\Locale\Persistence\Propel\Map\SpyLocaleTableMap;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainerInterface;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryAttributeTableMap;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;


class RootNodeTable extends AbstractTable
{
    const TABLE_IDENTIFIER = 'root-node-table';

    const ID_CATEGORY_NODE = 'id_category_node';
    const LOCALE_NAME = 'locale_name';
    const COL_REORDER = 'Reorder';


    /**
     * @var CategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @var int
     */
    protected $idLocale;

    /**
     * @param int $idLocale
     * @param CategoryQueryContainerInterface $productCategoryQueryContainer
     */
    public function __construct(CategoryQueryContainerInterface $productCategoryQueryContainer, $idLocale)
    {
        $this->categoryQueryContainer = $productCategoryQueryContainer;
        $this->idLocale = $idLocale;
        $this->defaultUrl = 'rootNodeTable';
        $this->setTableIdentifier(self::TABLE_IDENTIFIER);
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $this->tableClass = 'gui-table-data-category';
        $config->setHeader([
            SpyCategoryAttributeTableMap::COL_FK_CATEGORY => 'Category Id',
            SpyCategoryAttributeTableMap::COL_NAME => 'Name',
            SpyLocaleTableMap::COL_LOCALE_NAME => 'Locale',
            self::COL_REORDER => ''
        ]);
        $config->setSortable([
            SpyLocaleTableMap::COL_LOCALE_NAME,
            SpyCategoryAttributeTableMap::COL_NAME,
        ]);

        return $config;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->categoryQueryContainer->queryRootNodes($this->idLocale)
            ->orderBy(SpyCategoryAttributeTableMap::COL_NAME)
            ->setModelAlias('spy_locale')
        ;

        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $rootNode) {
            $results[] = [
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY => $rootNode[SpyCategoryAttributeTableMap::COL_FK_CATEGORY],
                SpyCategoryAttributeTableMap::COL_NAME => $rootNode[SpyCategoryAttributeTableMap::COL_NAME],
                SpyLocaleTableMap::COL_LOCALE_NAME => $rootNode[self::LOCALE_NAME],
                self::COL_REORDER => $this->getReorderButtonHtml($rootNode).' '.$this->getAddButtonHtml($rootNode)
            ];
        }
        unset($queryResults);
        return $results;
    }

    /**
     * @param array $rootNode
     *
     * @return string
     */
    protected function getAddButtonHtml(array $rootNode)
    {
        return sprintf(
            '<a href="/category/node/view?id-node=%d" id="node-%d" class="btn btn-xs btn-success"><i class="fa fa-sitemap"></i></a>',
            $rootNode[self::ID_CATEGORY_NODE],
            $rootNode[self::ID_CATEGORY_NODE]
        );
    }

    /**
     * @param array $rootNode
     *
     * @return string
     */
    protected function getReorderButtonHtml(array $rootNode)
    {
        return sprintf(
            '<a href="/productCategory/add?id-parent-node=%d" class="btn btn-xs btn-success"><i class="fa fa-plus"></i></a>',
            $rootNode[self::ID_CATEGORY_NODE]
        );
    }
}
