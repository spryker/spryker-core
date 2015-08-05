<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cms\Communication\Table;

use SprykerFeature\Zed\Cms\Persistence\CmsQueryContainer;
use SprykerFeature\Zed\Cms\Persistence\Propel\Map\SpyCmsGlossaryKeyMappingTableMap;
use SprykerFeature\Zed\Cms\Persistence\Propel\Map\SpyCmsPageTableMap;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsGlossaryKeyMappingQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class CmsGlossaryTable extends AbstractTable
{

    const ACTIONS = 'Actions';
    const REQUEST_ID_MAPPING = 'id-mapping';

    /**
     * @var SpyCmsGlossaryKeyMappingQuery
     */
    protected $glossaryQuery;

    /**
     * @var array
     */
    protected $placeholders;

    /**
     * @var int
     */
    protected $idPage;

    /**
     * @param SpyCmsGlossaryKeyMappingQuery $glossaryQuery
     * @param int $idPage
     * @param array $placeholders
     *
     * @param SpyCmsGlossaryKeyMappingQuery $glossaryQuery
     */
    public function __construct(SpyCmsGlossaryKeyMappingQuery $glossaryQuery, $idPage, $placeholders = null)
    {
        $this->glossaryQuery = $glossaryQuery;
        $this->idPage = $idPage;
        $this->placeholders = $placeholders;
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
            self::ACTIONS => self::ACTIONS,
        ]);
        $config->setSortable([
            SpyCmsPageTableMap::COL_ID_CMS_PAGE,
        ]);

        $config->setSearchable([
            SpyCmsGlossaryKeyMappingTableMap::COL_ID_CMS_GLOSSARY_KEY_MAPPING,
            SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER,
            CmsQueryContainer::KEY => '`key`',
            CmsQueryContainer::TRANS => 'value',
        ]);

        $config->setUrl(sprintf('table?'.CmsPageTable::REQUEST_ID_PAGE.'=%d', $this->idPage));

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

        $mappedPlaceholders = [];
        $results = [];

        foreach ($queryResults as $item) {
            $results[] = [
                SpyCmsGlossaryKeyMappingTableMap::COL_ID_CMS_GLOSSARY_KEY_MAPPING => $item[SpyCmsGlossaryKeyMappingTableMap::COL_ID_CMS_GLOSSARY_KEY_MAPPING],
                SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER => $item[SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER],
                CmsQueryContainer::KEY => $item[CmsQueryContainer::KEY],
                CmsQueryContainer::TRANS => $item[CmsQueryContainer::TRANS],
                self::ACTIONS => $this->buildLinks($item),
            ];
            $mappedPlaceholders[] = $item[SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER];
        }

        unset($queryResults);

        foreach ($this->placeholders as $place) {

            if (!in_array($place, $mappedPlaceholders)) {
                $results[] = [
                    SpyCmsGlossaryKeyMappingTableMap::COL_ID_CMS_GLOSSARY_KEY_MAPPING => null,
                    SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER => $place,
                    CmsQueryContainer::KEY => null,
                    CmsQueryContainer::TRANS => null,
                    self::ACTIONS => $this->buildPlaceholderLinks($place),
                ];
            }
        }

        return $results;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    private function buildLinks($item)
    {
        $mappingParam = self::REQUEST_ID_MAPPING . '=' . $item[SpyCmsGlossaryKeyMappingTableMap::COL_ID_CMS_GLOSSARY_KEY_MAPPING];
        $pageParam = CmsPageTable::REQUEST_ID_PAGE . '=' . $this->idPage;

        $result = '<a href="/cms/glossary/edit/?' . $mappingParam . '&' . $pageParam . '" class="btn btn-xs btn-white">Edit</a>&nbsp;
                   <a href="/cms/glossary/delete/?' . $mappingParam . '&' . $pageParam . '" class="btn btn-xs btn-white">Delete</a>';

        return $result;
    }

    /**
     * @param string $placeholder
     *
     * @return string
     */
    private function buildPlaceholderLinks($placeholder)
    {
        return '<a href="/cms/glossary/add/?' . CmsPageTable::REQUEST_ID_PAGE . '=' . $this->idPage . '&placeholder=' . $placeholder . '" class="btn btn-xs btn-white">Add Glossary</a>';
    }
}
