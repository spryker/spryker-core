<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Table;

use Orm\Zed\Cms\Persistence\Map\SpyCmsPageLocalizedAttributesTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Spryker\Shared\Url\Url;
use Spryker\Zed\Cms\Persistence\CmsQueryContainer;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\CmsGui\Communication\Controller\CreateGlossaryController;
use Spryker\Zed\CmsGui\Communication\Controller\EditPageController;
use Spryker\Zed\CmsGui\Communication\Controller\ListPageController;
use Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface;

class CmsPageTable extends AbstractTable
{

    const ACTIONS = 'Actions';
    const URL_CMS_PAGE_ACTIVATE = '/cms-gui/edit-page/activate';
    const URL_CMS_PAGE_DEACTIVATE = '/cms-gui/edit-age/deactivate';

    /**
     * @var CmsGuiToCmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @param CmsGuiToCmsQueryContainerInterface $cmsQueryContainer
     */
    public function __construct(CmsGuiToCmsQueryContainerInterface $cmsQueryContainer)
    {
        $this->cmsQueryContainer = $cmsQueryContainer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyCmsPageTableMap::COL_ID_CMS_PAGE => '#',
            SpyCmsPageLocalizedAttributesTableMap::COL_NAME => 'Name',
            'Url' => 'Url',
            'Template' => 'Template',
            SpyCmsPageTableMap::COL_IS_ACTIVE => 'Active',
            self::ACTIONS => self::ACTIONS,
        ]);

        $config->addRawColumn(self::ACTIONS);

        $config->setSortable([
            SpyCmsPageTableMap::COL_ID_CMS_PAGE,
            SpyCmsPageTableMap::COL_IS_ACTIVE,
        ]);

        $config->setDefaultSortDirection(TableConfiguration::SORT_DESC);

        $config->setSearchable([
            SpyCmsPageTableMap::COL_ID_CMS_PAGE
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
        $query = $this->cmsQueryContainer->queryPagesWithTemplatesForSelectedLocale(66);
        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $item) {
            $results[] = [
                SpyCmsPageTableMap::COL_ID_CMS_PAGE => $item[SpyCmsPageTableMap::COL_ID_CMS_PAGE],
                'name' => $this->getPageName($item),
                'Url' => $this->buildUrlList($item),
                'Template' => $item['template_name'],
                SpyCmsPageTableMap::COL_IS_ACTIVE => $item[SpyCmsPageTableMap::COL_IS_ACTIVE],
                self::ACTIONS => implode(' ', $this->buildLinks($item)),
            ];
        }

        return $results;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function buildUrlList(array $item)
    {
        return $item['name'];
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function getPageName(array $item)
    {
        return $item['name'];
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function buildLinks(array $item)
    {
        $buttons = [];

        $buttons[] = $this->createViewButton($item);
        $buttons[] = $this->createViewInShopButton($item);
        $buttons[] = $this->createEditGlossaryButton($item);
        $buttons[] = $this->createEditPageButton($item);
        $buttons[] = $this->createCmsStateChangeButton($item);

        return $buttons;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function createViewInShopButton(array $item)
    {
        return 'n/a';
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function createViewButton(array $item)
    {
        return $this->generateEditButton(
            Url::generate('/cms-gui/view-page/index', [
                ListPageController::URL_PARAM_ID_CMS_PAGE => $item[SpyCmsPageTableMap::COL_ID_CMS_PAGE],
            ]),
            'view'
        );
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function createEditGlossaryButton(array $item)
    {
        return $this->generateEditButton(
            Url::generate('/cms-gui/creat-glossary/index', [
                CreateGlossaryController::URL_PARAM_ID_CMS_PAGE => $item[SpyCmsPageTableMap::COL_ID_CMS_PAGE],
            ]),
            'Edit Placeholders'
        );
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function createEditPageButton(array $item)
    {
        return $this->generateEditButton(
            Url::generate('/cms-gui/create-page/index', [
                EditPageController::URL_PARAM_ID_CMS_PAGE => $item[SpyCmsPageTableMap::COL_ID_CMS_PAGE],
            ]),
            'Edit Page'
        );
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function createCmsStateChangeButton(array $item)
    {
        if ($item[SpyCmsPageTableMap::COL_IS_ACTIVE]) {
            $label = 'Deactivate';
            $url = static::URL_CMS_PAGE_DEACTIVATE;
        } else {
            $label = 'Activate';
            $url = static::URL_CMS_PAGE_ACTIVATE;
        }

        return $this->generateViewButton(
            Url::generate($url, [
                EditPageController::URL_PARAM_ID_CMS_PAGE => $item[SpyCmsPageTableMap::COL_ID_CMS_PAGE],
            ]),
            $label
        );
    }

}
