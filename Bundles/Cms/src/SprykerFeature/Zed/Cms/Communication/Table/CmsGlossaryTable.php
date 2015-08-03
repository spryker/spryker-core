<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cms\Communication\Table;

use SprykerFeature\Zed\Cms\Persistence\CmsQueryContainer;
use SprykerFeature\Zed\Cms\Persistence\Propel\Map\SpyCmsGlossaryKeyMappingTableMap;
use SprykerFeature\Zed\Cms\Persistence\Propel\Map\SpyCmsPageTableMap;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsGlossaryKeyMappingQuery;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsPageQuery;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Map\SpyGlossaryKeyTableMap;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class CmsGlossaryTable extends AbstractTable{

    const ACTIONS = 'Actions';

    protected $glossaryQuery;

    protected $idPage;
    /**
     * @param int $idPage
     * @param SpyCmsGlossaryKeyMappingQuery $glossaryQuery
     */
    public function __construct(SpyCmsGlossaryKeyMappingQuery $glossaryQuery,$idPage){
        $this->glossaryQuery = $glossaryQuery;
        $this->idPage = $idPage;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyCmsGlossaryKeyMappingTableMap::COL_ID_CMS_GLOSSARY_KEY_MAPPING => 'Id',
            SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER => 'Placeholder',
            CmsQueryContainer::KEY => 'Glossary Key',
            CmsQueryContainer::TRANS => 'Glossary Value',
            self::ACTIONS => self::ACTIONS
        ]);
        $config->setSortable([
            SpyCmsPageTableMap::COL_ID_CMS_PAGE,
        ]);

        $config->setUrl(sprintf('table?id_page=%d', $this->idPage));
        return $config;
    }
    /**
     * @param TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->glossaryQuery;
        $queryResults = $this->runQuery($query, $config);
        $results = [];

        foreach ($queryResults as $item) {
            $results[] = [
                SpyCmsGlossaryKeyMappingTableMap::COL_ID_CMS_GLOSSARY_KEY_MAPPING => $item[SpyCmsGlossaryKeyMappingTableMap::COL_ID_CMS_GLOSSARY_KEY_MAPPING],
                SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER => $item[SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER],
                CmsQueryContainer::KEY => $item[CmsQueryContainer::KEY],
                CmsQueryContainer::TRANS => $item[CmsQueryContainer::TRANS],
                self::ACTIONS => $this->buildLinks($item)
            ];
        }
        unset($queryResults);

        return $results;
    }

    private function buildLinks($item)
    {
        $mappingParam = 'id_mapping='.$item[SpyCmsGlossaryKeyMappingTableMap::COL_ID_CMS_GLOSSARY_KEY_MAPPING];
        $pageParam = 'id_page='.$this->idPage;

        $result = '<a href="/cms/glossary/edit/?'.$mappingParam.'&'.$pageParam.'" class="btn btn-xs btn-white">Edit</a>&nbsp;
                   <a href="/cms/glossary/delete/?'.$mappingParam.'&'.$pageParam.'" class="btn btn-xs btn-white">Delete</a>';

        return $result;
    }
}
