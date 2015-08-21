<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms\Communication\Table;

use SprykerFeature\Zed\Cms\Persistence\CmsQueryContainer;
use SprykerFeature\Zed\Cms\Persistence\Propel\Map\SpyCmsPageTableMap;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsPageQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class CmsPageTable extends AbstractTable
{
    const ACTIONS         = 'Actions';
    const REQUEST_ID_PAGE = 'id-page';

    /**
     * @var SpyCmsPageQuery
     */
    protected $pageQuery;

    /**
     * @param SpyCmsPageQuery $pageQuery
     */
    public function __construct(SpyCmsPageQuery $pageQuery)
    {
        $this->pageQuery = $pageQuery;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyCmsPageTableMap::COL_ID_CMS_PAGE => 'Page Id',
            CmsQueryContainer::TEMPLATE_NAME    => 'Template',
            CmsQueryContainer::URL              => 'url',
            self::ACTIONS                       => self::ACTIONS,
        ]);
        $config->setSortable([
            SpyCmsPageTableMap::COL_ID_CMS_PAGE,
        ]);

        $config->setSearchable([
            SpyCmsPageTableMap::COL_ID_CMS_PAGE,
            CmsQueryContainer::TEMPLATE_NAME,
            CmsQueryContainer::URL,
        ]);

        $config->setUrl('pageTable');
        $config->setPageLength(5);

        return $config;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query        = $this->pageQuery;
        $queryResults = $this->runQuery($query, $config);
        $results      = [];

        foreach ($queryResults as $item) {
            $results[] = [
                SpyCmsPageTableMap::COL_ID_CMS_PAGE => $item[SpyCmsPageTableMap::COL_ID_CMS_PAGE],
                CmsQueryContainer::TEMPLATE_NAME    => $item[CmsQueryContainer::TEMPLATE_NAME],
                CmsQueryContainer::URL              => $item[CmsQueryContainer::URL],
                self::ACTIONS                       => $this->buildLinks($item),
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
        $result = '<a href="/cms/glossary/?'.self::REQUEST_ID_PAGE.'='.$item[SpyCmsPageTableMap::COL_ID_CMS_PAGE].'" class="btn btn-xs btn-white">Edit glossaries</a>&nbsp;
        <a href="/cms/page/edit/?'.self::REQUEST_ID_PAGE.'='.$item[SpyCmsPageTableMap::COL_ID_CMS_PAGE].'" class="btn btn-xs btn-white">Edit page</a>';

        return $result;
    }
}
