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
            SpyCmsGlossaryKeyMappingTableMap::COL_FK_GLOSSARY_KEY => 'Glossary Key',
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
                SpyCmsGlossaryKeyMappingTableMap::COL_FK_GLOSSARY_KEY => $item["".CmsQueryContainer::GLOSSARY_KEY.""],
            ];
        }
        unset($queryResults);

        return $results;
    }
}
