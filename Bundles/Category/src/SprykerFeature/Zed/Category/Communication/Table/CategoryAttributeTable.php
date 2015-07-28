<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Communication\Table;

use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryAttributeTableMap;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryAttributeQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class CategoryAttributeTable extends AbstractTable
{
    const TABLE_IDENTIFIER = 'category_attribute_table';

    /**
     * @param SpyCategoryAttributeQuery $categoryAttributeQuery
     */
    public function __construct(SpyCategoryAttributeQuery $categoryAttributeQuery)
    {
        $this->categoryAttributeQuery = $categoryAttributeQuery;
        $this->defaultUrl = 'categoryAttributeTable';
        $this->setTableIdentifier(self::TABLE_IDENTIFIER);
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyCategoryAttributeTableMap::COL_FK_CATEGORY => 'Category Id',
            SpyCategoryAttributeTableMap::COL_NAME => 'Name',
            SpyCategoryAttributeTableMap::COL_FK_LOCALE => 'Locale Id',
            SpyCategoryAttributeTableMap::COL_META_TITLE => 'Meta Title',
            SpyCategoryAttributeTableMap::COL_META_DESCRIPTION => 'Meta Description',
            SpyCategoryAttributeTableMap::COL_META_KEYWORDS => 'Meta Keywords',
            SpyCategoryAttributeTableMap::COL_CATEGORY_IMAGE_NAME => 'Category Image Name',
            SpyCategoryAttributeTableMap::COL_CREATED_AT => 'Created At',
            SpyCategoryAttributeTableMap::COL_UPDATED_AT => 'Updated At',
        ]);
        $config->setSortable([
            SpyCategoryAttributeTableMap::COL_CREATED_AT,
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
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY => $attribute[SpyCategoryAttributeTableMap::COL_FK_CATEGORY],
                SpyCategoryAttributeTableMap::COL_NAME => $attribute[SpyCategoryAttributeTableMap::COL_NAME],
                SpyCategoryAttributeTableMap::COL_FK_LOCALE => $attribute['spy_localelocale_name'], //@todo: refactor when table alias is fixed (missing .)
                SpyCategoryAttributeTableMap::COL_META_TITLE => $attribute[SpyCategoryAttributeTableMap::COL_META_TITLE],
                SpyCategoryAttributeTableMap::COL_META_DESCRIPTION => $attribute[SpyCategoryAttributeTableMap::COL_META_DESCRIPTION],
                SpyCategoryAttributeTableMap::COL_META_KEYWORDS => $attribute[SpyCategoryAttributeTableMap::COL_META_KEYWORDS],
                SpyCategoryAttributeTableMap::COL_CATEGORY_IMAGE_NAME => $attribute[SpyCategoryAttributeTableMap::COL_CATEGORY_IMAGE_NAME],
                SpyCategoryAttributeTableMap::COL_CREATED_AT => $attribute[SpyCategoryAttributeTableMap::COL_CREATED_AT],
                SpyCategoryAttributeTableMap::COL_UPDATED_AT => $attribute[SpyCategoryAttributeTableMap::COL_UPDATED_AT],
            ];
        }
        unset($queryResults);
        return $results;
    }
}
