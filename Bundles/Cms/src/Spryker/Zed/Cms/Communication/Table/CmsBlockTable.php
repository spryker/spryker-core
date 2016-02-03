<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms\Communication\Table;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Spryker\Zed\Application\Business\Url\Url;
use Spryker\Zed\Cms\Persistence\CmsQueryContainer;
use Orm\Zed\Cms\Persistence\Base\SpyCmsBlockQuery;
use Orm\Zed\Cms\Persistence\Map\SpyCmsBlockTableMap;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CmsBlockTable extends AbstractTable
{

    const ACTIONS = 'Actions';
    const REQUEST_ID_BLOCK = 'id-block';
    const REQUEST_ID_PAGE = 'id-page';
    const PARAM_CMS_GLOSSARY = '/cms/glossary/';
    const PARAM_CMS_BLOCK_EDIT = '/cms/block/edit/';

    /**
     * @var \Orm\Zed\Cms\Persistence\Base\SpyCmsBlockQuery
     */
    protected $cmsBlockQuery;

    /**
     * @param \Orm\Zed\Cms\Persistence\Base\SpyCmsBlockQuery $cmsBlockQuery
     */
    public function __construct(SpyCmsBlockQuery $cmsBlockQuery)
    {
        $this->cmsBlockQuery = $cmsBlockQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
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
            SpyCategoryAttributeTableMap::COL_NAME,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
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
                self::ACTIONS => implode(' ', $this->buildLinks($item)),
            ];
        }
        unset($queryResults);

        return $results;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    private function buildLinks(array $item)
    {
        $buttons = [];
        $buttons[] = $this->generateEditButton(
            Url::generate(self::PARAM_CMS_GLOSSARY, [
                self::REQUEST_ID_PAGE => $item[SpyCmsBlockTableMap::COL_FK_PAGE],
            ]),
            'Edit Placeholder'
        );

        $buttons[] = $this->generateEditButton(
            Url::generate(self::PARAM_CMS_BLOCK_EDIT, [
                self::REQUEST_ID_BLOCK => $item[SpyCmsBlockTableMap::COL_ID_CMS_BLOCK],
            ]),
            'Edit Block'
        );

        return $buttons;
    }

    private function buildValueItem(array $item)
    {
        $result = $item[CmsQueryContainer::CATEGORY_NAME] . '<br><div style="font-size:.8em">' . $item[CmsQueryContainer::URL] . '<div>';

        return $result;
    }

}
