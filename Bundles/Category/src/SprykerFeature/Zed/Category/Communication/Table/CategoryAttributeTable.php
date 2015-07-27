<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Communication\Table;

use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryAttributeQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class CategoryAttributeTable extends AbstractTable
{
    const FK_CATEGORY = 'FkCategory';
    const NAME = 'Name';
    const FK_LOCALE = 'FkLocale';
    const META_TITLE = 'MetaTitle';
    const META_DESCRIPTION = 'MetaDescription';
    const META_KEYWORDS = 'MetaKeywords';
    const CATEGORY_IMAGE_NAME = 'CategoryImageName';
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';
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
        $config->setHeaders([
            self::FK_CATEGORY => 'Category Id',
            self::NAME => 'Name',
            self::FK_LOCALE => 'Locale Id',
            self::META_TITLE => 'Meta Title',
            self::META_DESCRIPTION => 'Meta Description',
            self::META_KEYWORDS => 'Meta Keywords',
            self::CATEGORY_IMAGE_NAME => 'Category Image Name',
            self::CREATED_AT => 'Created At',
            self::UPDATED_AT => 'Updated At',
        ]);
        $config->setSortable([
            self::CREATED_AT,
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
                self::FK_LOCALE => $attribute[self::FK_LOCALE],
                self::META_TITLE => $attribute[self::META_TITLE],
                self::META_DESCRIPTION => $attribute[self::META_DESCRIPTION],
                self::META_KEYWORDS => $attribute[self::META_KEYWORDS],
                self::CATEGORY_IMAGE_NAME => $attribute[self::CATEGORY_IMAGE_NAME],
                self::CREATED_AT => $attribute[self::CREATED_AT],
                self::UPDATED_AT => $attribute[self::UPDATED_AT],
            ];
        }
        unset($queryResults);
        return $results;
    }
}
