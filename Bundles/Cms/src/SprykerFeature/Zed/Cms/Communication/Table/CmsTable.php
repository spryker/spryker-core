<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cms\Communication\Table;

use SprykerFeature\Zed\Cms\Persistence\Propel\Map\SpyCmsPageTableMap;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsPageQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class CmsTable extends AbstractTable{

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
            SpyCmsPageTableMap::COL_FK_TEMPLATE => 'Template',
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
//        $results = [];
//        foreach ($queryResults as $item) {
//            echo '<pre>';
//            print_r($item);
//            die;
//            $results[] = [
//                SpyCmsPageTableMap::COL_ID_CMS_PAGE => $item[SpyCmsPageTableMap::COL_ID_CMS_PAGE],
//                SpyCmsPageTableMap::COL_FK_TEMPLATE  => $item[SpyCmsPageTableMap::COL_FK_TEMPLATE],
//            ];
//        }
//        unset($queryResults);

        return $queryResults;
    }
}
