<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication\Table;

use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Orm\Zed\Cms\Persistence\SpyCmsPageQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Cms\Persistence\CmsQueryContainer;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CmsPageTable extends AbstractTable
{
    public const ACTIONS = 'Actions';
    public const REQUEST_ID_PAGE = 'id-page';
    public const URL_CMS_PAGE_ACTIVATE = '/cms/page/activate';
    public const URL_CMS_PAGE_DEACTIVATE = '/cms/page/deactivate';

    /**
     * @var \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    protected $pageQuery;

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPageQuery $pageQuery
     */
    public function __construct(SpyCmsPageQuery $pageQuery)
    {
        $this->pageQuery = $pageQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyCmsPageTableMap::COL_ID_CMS_PAGE => 'Page Id',
            CmsQueryContainer::URL => 'URL',
            CmsQueryContainer::TEMPLATE_NAME => 'Template',
            CmsQueryContainer::IS_ACTIVE => 'Active',
            self::ACTIONS => self::ACTIONS,
        ]);

        $config->addRawColumn(self::ACTIONS);

        $config->setSortable([
            SpyCmsPageTableMap::COL_ID_CMS_PAGE,
            CmsQueryContainer::URL,
            CmsQueryContainer::TEMPLATE_NAME,
            CmsQueryContainer::IS_ACTIVE,
        ]);

        $config->setDefaultSortDirection(TableConfiguration::SORT_DESC);

        $config->setSearchable([
            SpyCmsPageTableMap::COL_ID_CMS_PAGE,
            CmsQueryContainer::TEMPLATE_NAME,
            CmsQueryContainer::URL,
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
        $query = $this->pageQuery;
        $queryResults = $this->runQuery($query, $config);
        $results = [];

        foreach ($queryResults as $item) {
            $results[] = [
                SpyCmsPageTableMap::COL_ID_CMS_PAGE => $item[SpyCmsPageTableMap::COL_ID_CMS_PAGE],
                CmsQueryContainer::TEMPLATE_NAME => $item[CmsQueryContainer::TEMPLATE_NAME],
                CmsQueryContainer::URL => $item[CmsQueryContainer::URL],
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
    protected function buildLinks($item)
    {
        $buttons = [];

        $buttons[] = $this->generateEditButton(
            Url::generate('/cms/glossary', [
                self::REQUEST_ID_PAGE => $item[SpyCmsPageTableMap::COL_ID_CMS_PAGE],
            ]),
            'Edit Placeholders'
        );
        $buttons[] = $this->generateEditButton(
            Url::generate('/cms/page/edit', [
                self::REQUEST_ID_PAGE => $item[SpyCmsPageTableMap::COL_ID_CMS_PAGE],
            ]),
            'Edit Page'
        );
        $buttons[] = $this->generateStateChangeButton($item);

        return $buttons;
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
            $url = self::URL_CMS_PAGE_DEACTIVATE;
        } else {
            $name = 'Activate';
            $url = self::URL_CMS_PAGE_ACTIVATE;
        }

        return $this->generateViewButton(
            Url::generate($url, [
                self::REQUEST_ID_PAGE => $item[SpyCmsPageTableMap::COL_ID_CMS_PAGE],
            ]),
            $name
        );
    }
}
