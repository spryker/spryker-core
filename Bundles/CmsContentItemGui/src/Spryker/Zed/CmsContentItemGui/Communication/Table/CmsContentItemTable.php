<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentItemGui\Communication\Table;

use Orm\Zed\CmsContentPlan\Persistence\Base\SpyCmsContentPlanQuery;
use Orm\Zed\CmsContentPlan\Persistence\Map\SpyCmsContentPlanTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\CmsContentItemGui\Dependency\Service\CmsContentItemGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CmsContentItemTable extends AbstractTable
{
    /**
     * @var \Orm\Zed\CmsContentPlan\Persistence\Base\SpyCmsContentPlanQuery
     */
    private $cmsContentPlanQuery;

    /**
     * @var \Spryker\Zed\CmsContentItemGui\Dependency\Service\CmsContentItemGuiToUtilDateTimeServiceInterface
     */
    private $utilDateTimeService;

    /**
     * @param \Orm\Zed\CmsContentPlan\Persistence\Base\SpyCmsContentPlanQuery $cmsContentPlanQuery
     * @param \Spryker\Zed\CmsContentItemGui\Dependency\Service\CmsContentItemGuiToUtilDateTimeServiceInterface $utilDateTimeService
     */
    public function __construct(
        SpyCmsContentPlanQuery $cmsContentPlanQuery,
        CmsContentItemGuiToUtilDateTimeServiceInterface $utilDateTimeService
    ) {
        $this->cmsContentPlanQuery = $cmsContentPlanQuery;
        $this->utilDateTimeService = $utilDateTimeService;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config = $this->setHeader($config);

        $config->setSortable([
            CmsContentItemTableConstants::COL_ID_CMS_CONTENT_ITEM,
            CmsContentItemTableConstants::COL_NAME,
            CmsContentItemTableConstants::COL_TYPE,
        ]);

        $config->addRawColumn(CmsContentItemTableConstants::COL_ACTIONS);
        $config->addRawColumn(CmsContentItemTableConstants::COL_TYPE);
        $config->setDefaultSortField(CmsContentItemTableConstants::COL_ID_CMS_CONTENT_ITEM, TableConfiguration::SORT_DESC);

        $config->setSearchable([
            CmsContentItemTableConstants::COL_ID_CMS_CONTENT_ITEM,
            CmsContentItemTableConstants::COL_NAME,
            CmsContentItemTableConstants::COL_TYPE,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setHeader(TableConfiguration $config): TableConfiguration
    {
        $baseData = [
            CmsContentItemTableConstants::COL_ID_CMS_CONTENT_ITEM => 'Content Item ID',
            CmsContentItemTableConstants::COL_NAME => 'Name',
            CmsContentItemTableConstants::COL_DESCRIPTION => 'Description',
            CmsContentItemTableConstants::COL_TYPE => 'Content Type',
            CmsContentItemTableConstants::COL_UPDATED_AT => 'Updated',
        ];

        $actions = [CmsContentItemTableConstants::COL_ACTIONS => 'Actions'];

        $config->setHeader($baseData + $actions);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $cmsContentItems = $this->runQuery($this->cmsContentPlanQuery, $config);
        $results = [];

        foreach ($cmsContentItems as $cmsContentItem) {
            $results[] = [
                CmsContentItemTableConstants::COL_ID_CMS_CONTENT_ITEM => $cmsContentItem[SpyCmsContentPlanTableMap::COL_ID_CMS_CONTENT_PLAN],
                CmsContentItemTableConstants::COL_NAME => $cmsContentItem[SpyCmsContentPlanTableMap::COL_NAME],
                CmsContentItemTableConstants::COL_DESCRIPTION => $cmsContentItem[SpyCmsContentPlanTableMap::COL_DESCRIPTION],
                CmsContentItemTableConstants::COL_TYPE => $this->buildContentTypeLabel($cmsContentItem[SpyCmsContentPlanTableMap::COL_TYPE]),
                CmsContentItemTableConstants::COL_UPDATED_AT => $this->utilDateTimeService->formatDateTime($cmsContentItem[SpyCmsContentPlanTableMap::COL_UPDATED_AT]),
                CmsContentItemTableConstants::COL_ACTIONS => $this->buildLinks($cmsContentItem),
            ];
        }

        unset($cmsContentItems);

        return $results;
    }

    /**
     * @param array $cmsContentItem
     *
     * @return string
     */
    protected function buildLinks(array $cmsContentItem): string
    {
        $buttons = [];

        $urlParams = [CmsContentItemTableConstants::REQUEST_ID_CMS_CONTENT_ITEM => $cmsContentItem[CmsContentItemTableConstants::COL_ID_CMS_CONTENT_ITEM]];

        $buttons[] = $this->generateEditButton(
            Url::generate(CmsContentItemTableConstants::URL_CMS_CONTENT_ITEM_EDIT, $urlParams),
            'Edit'
        );

        return implode(' ', $buttons);
    }

    /**
     * @param string $label
     *
     * @return string
     */
    protected function buildContentTypeLabel(string $label): string
    {
        return sprintf('<span class="label label-info">%s</span>', ucfirst($label));
    }
}
