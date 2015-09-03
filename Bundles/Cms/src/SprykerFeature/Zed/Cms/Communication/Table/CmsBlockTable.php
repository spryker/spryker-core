<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms\Communication\Table;

use SprykerFeature\Zed\Category\Persistence\Propel\Map\SpyCategoryAttributeTableMap;
use SprykerFeature\Zed\Cms\Persistence\CmsQueryContainer;
use SprykerFeature\Zed\Cms\Persistence\Propel\Base\SpyCmsBlockQuery;
use SprykerFeature\Zed\Cms\Persistence\Propel\Map\SpyCmsBlockTableMap;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class CmsBlockTable extends AbstractTable
{

    const ACTIONS = 'Actions';
    const REQUEST_ID_BLOCK = 'id-block';
    const REQUEST_ID_PAGE = 'id-page';

    /**
     * @var SpyCmsBlockQuery
     */
    protected $cmsBlockQuery;

    /**
     * @param SpyCmsBlockQuery $cmsBlockQuery
     */
    public function __construct(SpyCmsBlockQuery $cmsBlockQuery)
    {
        $this->cmsBlockQuery = $cmsBlockQuery;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyCmsBlockTableMap::COL_ID_CMS_BLOCK => 'Block Id',
            SpyCmsBlockTableMap::COL_NAME => 'Name',
            CmsQueryContainer::TEMPLATE_NAME => 'Template',
            SpyCmsBlockTableMap::COL_TYPE => 'Type',
            SpyCmsBlockTableMap::COL_VALUE => 'Value',
            self::ACTIONS => self::ACTIONS,
        ]);
        $config->setSortable([
            SpyCmsBlockTableMap::COL_ID_CMS_BLOCK,
        ]);

        $config->setSearchable([
            SpyCmsBlockTableMap::COL_ID_CMS_BLOCK,
            CmsQueryContainer::TEMPLATE_NAME,
            SpyCmsBlockTableMap::COL_TYPE,
            SpyCmsBlockTableMap::COL_VALUE,
            SpyCmsBlockTableMap::COL_NAME,
            SpyCategoryAttributeTableMap::COL_NAME
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
        $queryResults = $this->runQuery($this->cmsBlockQuery, $config);
        $results = [];

        foreach ($queryResults as $item) {
            $results[] = [
                SpyCmsBlockTableMap::COL_ID_CMS_BLOCK => $item[SpyCmsBlockTableMap::COL_ID_CMS_BLOCK],
                CmsQueryContainer::TEMPLATE_NAME => $item[CmsQueryContainer::TEMPLATE_NAME],
                SpyCmsBlockTableMap::COL_NAME => $item[SpyCmsBlockTableMap::COL_NAME],
                SpyCmsBlockTableMap::COL_TYPE => $item[SpyCmsBlockTableMap::COL_TYPE],
                SpyCmsBlockTableMap::COL_VALUE => $this->buildValueItem($item),
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
    private function buildLinks(array $item)
    {
        $result = '<a href="/cms/glossary/?' . self::REQUEST_ID_PAGE . '=' . $item[SpyCmsBlockTableMap::COL_FK_PAGE] . '" class="btn btn-xs btn-white">Edit placeholders</a>&nbsp;
        <a href="/cms/block/edit/?' . self::REQUEST_ID_BLOCK . '=' . $item[SpyCmsBlockTableMap::COL_ID_CMS_BLOCK] . '" class="btn btn-xs btn-white">Edit block</a>';

        return $result;
    }

    private function buildValueItem(array $item)
    {
        $result = $item[CmsQueryContainer::CATEGORY_NAME].'<br><div style="font-size:.8em">'.$item[CmsQueryContainer::URL].'<div>';

        return $result;
    }
}
