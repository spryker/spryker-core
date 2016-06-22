<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication\Table;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsBlockTableMap;
use Orm\Zed\Cms\Persistence\SpyCmsBlockQuery;
use Spryker\Shared\Url\Url;
use Spryker\Zed\Cms\Persistence\CmsQueryContainer;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CmsBlockTable extends AbstractTable
{

    const ACTIONS = 'Actions';
    const REQUEST_ID_BLOCK = 'id-block';
    const REQUEST_ID_PAGE = 'id-page';
    const PARAM_CMS_GLOSSARY = '/cms/glossary';
    const PARAM_CMS_BLOCK_EDIT = '/cms/block/edit';
    const URL_CMS_BLOCK_DEACTIVATE = '/cms/block/deactivate';
    const URL_CMS_BLOCK_ACTIVATE = '/cms/block/activate';

    /**
     * @var \Orm\Zed\Cms\Persistence\SpyCmsBlockQuery
     */
    protected $cmsBlockQuery;

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsBlockQuery $cmsBlockQuery
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
            CmsQueryContainer::IS_ACTIVE => 'Active',
            self::ACTIONS => self::ACTIONS,
        ]);

        $config->addRawColumn(self::ACTIONS);

        $config->setSortable([
            SpyCmsBlockTableMap::COL_ID_CMS_BLOCK,
            SpyCmsBlockTableMap::COL_NAME,
            CmsQueryContainer::TEMPLATE_NAME,
            SpyCmsBlockTableMap::COL_TYPE,
            SpyCmsBlockTableMap::COL_VALUE,
            CmsQueryContainer::IS_ACTIVE,
        ]);

        $config->setDefaultSortDirection(TableConfiguration::SORT_DESC);

        $config->setSearchable([
            SpyCmsBlockTableMap::COL_ID_CMS_BLOCK,
            CmsQueryContainer::TEMPLATE_NAME,
            SpyCmsBlockTableMap::COL_TYPE,
            SpyCmsBlockTableMap::COL_VALUE,
            SpyCmsBlockTableMap::COL_NAME,
            SpyCategoryAttributeTableMap::COL_NAME,
        ]);

        $config->addRawColumn(SpyCmsBlockTableMap::COL_VALUE);

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
                CmsQueryContainer::IS_ACTIVE => $item[CmsQueryContainer::IS_ACTIVE],
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
    protected function buildLinks(array $item)
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

        $buttons[] = $this->generateStateChangeButton($item);

        return $buttons;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function buildValueItem(array $item)
    {
        $result = $item[CmsQueryContainer::CATEGORY_NAME] . '<br><div style="font-size:.8em">' . $item[CmsQueryContainer::URL] . '<div>';

        return $result;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function generateStateChangeButton(array $item)
    {
        if ($item[CmsQueryContainer::IS_ACTIVE]) {
            $name = 'Deactivate';
            $url = self::URL_CMS_BLOCK_DEACTIVATE;
        } else {
            $name = 'Activate';
            $url = self::URL_CMS_BLOCK_ACTIVATE;
        }

        return $this->generateViewButton(
            Url::generate($url, [
                self::REQUEST_ID_BLOCK => $item[SpyCmsBlockTableMap::COL_ID_CMS_BLOCK],
            ]),
            $name
        );
    }

}
