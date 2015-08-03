<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cms\Communication\Table;

use SprykerFeature\Zed\Cms\Persistence\CmsQueryContainer;
use SprykerFeature\Zed\Cms\Persistence\Propel\Map\SpyCmsPageTableMap;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsPageQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class CmsTable extends AbstractTable{

    const ACTIONS = 'Actions';

    /**
     * @param SpyCmsPage $pageQuery
     */
    public function __construct(SpyCmsPageQuery $pageQuery){
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
            CmsQueryContainer::TEMPLATE_NAME => 'Template',
            CmsQueryContainer::URL => 'url',
            self::ACTIONS => self::ACTIONS
        ]);
        $config->setSortable([
            SpyCmsPageTableMap::COL_ID_CMS_PAGE,
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
        $query = $this->pageQuery;
        $queryResults = $this->runQuery($query, $config);
        $results = [];

        foreach ($queryResults as $item) {
            $results[] = [
                SpyCmsPageTableMap::COL_ID_CMS_PAGE => $item[SpyCmsPageTableMap::COL_ID_CMS_PAGE],
                CmsQueryContainer::TEMPLATE_NAME => $item[CmsQueryContainer::TEMPLATE_NAME],
                CmsQueryContainer::URL => $item[CmsQueryContainer::URL],
                self::ACTIONS => $this->buildLinks($item),
            ];
        }
        unset($queryResults);

        return $results;
    }

    private function buildLinks($item)
    {
        $result = '<a href="/cms/glossary/?id_page='.$item[SpyCmsPageTableMap::COL_ID_CMS_PAGE].'" class="btn btn-xs btn-white">Manage glossaries</a>';

        return $result;
    }
}
