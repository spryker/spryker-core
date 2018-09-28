<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication\Table;

use Orm\Zed\Url\Persistence\Map\SpyUrlRedirectTableMap;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Orm\Zed\Url\Persistence\SpyUrl;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Spryker\Zed\Cms\Communication\Controller\RedirectController;
use Spryker\Zed\Cms\Persistence\CmsQueryContainer;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CmsRedirectTable extends AbstractTable
{
    public const ACTIONS = 'Actions';
    public const REQUEST_ID_URL = 'id-url';

    /**
     * @var \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    protected $urlQuery;

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrlQuery $urlQuery
     */
    public function __construct(SpyUrlQuery $urlQuery)
    {
        $this->urlQuery = $urlQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyUrlTableMap::COL_ID_URL => 'ID',
            SpyUrlTableMap::COL_URL => 'From Url',
            CmsQueryContainer::TO_URL => 'To Url',
            SpyUrlRedirectTableMap::COL_STATUS => 'Status',
            self::ACTIONS => self::ACTIONS,
        ]);

        $config->addRawColumn(self::ACTIONS);

        $config->setSortable([
            SpyUrlTableMap::COL_ID_URL,
            SpyUrlTableMap::COL_URL,
        ]);

        $config->setSearchable([
            SpyUrlTableMap::COL_ID_URL,
            SpyUrlTableMap::COL_URL,
            CmsQueryContainer::TO_URL => 'to_url',
            SpyUrlRedirectTableMap::COL_STATUS,
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
        $urlCollection = $this->getUrlCollection($config);
        $results = [];

        foreach ($urlCollection as $urlEntity) {
            $results[] = [
                SpyUrlTableMap::COL_ID_URL => $urlEntity->getIdUrl(),
                SpyUrlTableMap::COL_URL => $urlEntity->getUrl(),
                CmsQueryContainer::TO_URL => $urlEntity->getSpyUrlRedirect()->getToUrl(),
                SpyUrlRedirectTableMap::COL_STATUS => $urlEntity->getSpyUrlRedirect()->getStatus(),
                self::ACTIONS => $this->buildLinks($urlEntity),
            ];
        }

        return $results;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl[]
     */
    protected function getUrlCollection(TableConfiguration $config)
    {
        return $this->runQuery($this->urlQuery, $config, true);
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     *
     * @return string
     */
    protected function buildLinks(SpyUrl $urlEntity)
    {
        $buttons[] = $this->generateEditButton(sprintf('/cms/redirect/edit?%s=%s', RedirectController::REQUEST_ID_URL, $urlEntity->getIdUrl()), 'Edit');
        $buttons[] = $this->generateRemoveButton('/cms/redirect/delete', 'Delete', [
            RedirectController::REQUEST_ID_URL_REDIRECT => $urlEntity->getSpyUrlRedirect()->getIdUrlRedirect(),
        ]);

        return implode(' ', $buttons);
    }
}
