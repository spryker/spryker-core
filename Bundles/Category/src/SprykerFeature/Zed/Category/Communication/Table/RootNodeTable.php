<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Communication\Table;

use Propel\Runtime\Map\TableMap;
use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryAttributeTableMap;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryAttributeQuery;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryNodeQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;


class RootNodeTable extends AbstractTable
{
    const FK_CATEGORY = 'FkCategory';
    const NAME = 'Name';
    const FK_LOCALE = 'FkLocale';
    const TABLE_IDENTIFIER = 'root_node_table';
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

    /**
     * @param SpyCategoryAttributeQuery $categoryAttributeQuery
     */
    public function __construct(SpyCategoryAttributeQuery $categoryAttributeQuery)
    {
        $this->categoryAttributeQuery = $categoryAttributeQuery;
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
        $config->setHeaders([
            self::FK_CATEGORY => 'Category Id',
            self::NAME => 'Name',
            self::FK_LOCALE => 'Locale Id',
            self::CREATED_AT => 'Created At',
            self::UPDATED_AT => 'Updated At',
        ]);
        $config->setSortable([
            self::CREATED_AT,
            self::NAME,
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
        $query = $this->categoryAttributeQuery;
        $queryResults = $this->runQuery($query, $config);
        $results = [];
        foreach ($queryResults as $attribute) {
            $results[] = [
                self::FK_CATEGORY => $attribute[self::FK_CATEGORY],
                self::NAME => $attribute[self::NAME],
                self::FK_LOCALE => $attribute['spy_localelocale_name'], //@todo: refactor when table alias is fixed (missing .)
                self::CREATED_AT => $attribute[self::CREATED_AT],
                self::UPDATED_AT => $attribute[self::UPDATED_AT],
            ];
        }
        unset($queryResults);
        return $results;
    }
}
