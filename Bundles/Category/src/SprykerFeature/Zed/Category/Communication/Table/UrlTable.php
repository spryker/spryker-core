<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Communication\Table;


use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\Url\Persistence\Propel\SpyUrlQuery;

class UrlTable extends AbstractTable
{
    const FK_RESOURCE_CATEGORYNODE = 'FkResourceCategorynode';
    const FK_LOCALE = 'FkLocale';
    const URL = 'Url';
    const FK_RESOURCE_REDIRECT = 'FkResourceRedirect';
    const TABLE_IDENTIFIER = 'url_table';

    /**
     * @param SpyUrlQuery $urlQuery
     */
    public function __construct(SpyUrlQuery $urlQuery)
    {
        $this->urlQuery = $urlQuery;
        $this->defaultUrl = 'urlTable';
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
            self::FK_RESOURCE_CATEGORYNODE => 'Category node Id',
            self::FK_LOCALE => 'Fk Locale',
            self::URL => 'Url',
            self::FK_RESOURCE_REDIRECT => 'Fk Resource Redirect'
        ]);
        $config->setSortable([
            self::URL,
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
        $query = $this->urlQuery;
        $queryResults = $this->runQuery($query, $config);
        $results = [];
        foreach ($queryResults as $attribute) {
            $results[] = [
                self::FK_RESOURCE_CATEGORYNODE => $attribute[self::FK_RESOURCE_CATEGORYNODE],
                self::FK_LOCALE => $attribute[self::FK_LOCALE],
                self::URL => $attribute[self::URL],
                self::FK_RESOURCE_REDIRECT => $attribute[self::FK_RESOURCE_REDIRECT],
            ];
        }
        unset($queryResults);
        return $results;
    }
}
