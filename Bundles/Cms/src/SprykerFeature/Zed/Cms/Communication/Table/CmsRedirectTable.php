<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms\Communication\Table;

use SprykerFeature\Zed\Cms\Communication\Form\CmsRedirectForm;
use SprykerFeature\Zed\Cms\Persistence\CmsQueryContainer;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;
use Orm\Zed\Url\Persistence\Map\SpyRedirectTableMap;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Orm\Zed\Url\Persistence\SpyUrlQuery;

class CmsRedirectTable extends AbstractTable
{

    const ACTIONS = 'Actions';
    const REQUEST_ID_URL = 'id-url';

    /**
     * @var SpyUrlQuery
     */
    protected $urlQuery;

    /**
     * @param SpyUrlQuery $urlQuery
     */
    public function __construct($urlQuery)
    {
        $this->urlQuery = $urlQuery;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyUrlTableMap::COL_ID_URL => 'ID',
            SpyUrlTableMap::COL_URL => 'From Url',
            CmsQueryContainer::TO_URL => 'To Url',
            SpyRedirectTableMap::COL_STATUS => 'Status',
            self::ACTIONS => self::ACTIONS,
        ]);
        $config->setSortable([
            SpyUrlTableMap::COL_ID_URL,
            SpyUrlTableMap::COL_URL,
        ]);

        $config->setSearchable([
            SpyUrlTableMap::COL_ID_URL,
            SpyUrlTableMap::COL_URL,
            CmsQueryContainer::TO_URL => 'to_url',
            SpyRedirectTableMap::COL_STATUS,
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

        foreach ($queryResults as $item) {
            $results[] = [
                SpyUrlTableMap::COL_ID_URL => $item[SpyUrlTableMap::COL_ID_URL],
                SpyUrlTableMap::COL_URL => $item[SpyUrlTableMap::COL_URL],
                CmsQueryContainer::TO_URL => $item[CmsQueryContainer::TO_URL],
                SpyRedirectTableMap::COL_STATUS => $item[CmsRedirectForm::STATUS],
                self::ACTIONS => $this->buildLinks($item),
            ];
        }
        unset($queryResults);

        return $results;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    private function buildLinks($item)
    {
        $result = '<a href="/cms/redirect/edit/?' . self::REQUEST_ID_URL . '=' . $item[SpyUrlTableMap::COL_ID_URL] . '" class="btn btn-xs btn-white">Edit</a>&nbsp;
                   <a class="btn btn-xs btn-white">Delete</a>';

        return $result;
    }

}
