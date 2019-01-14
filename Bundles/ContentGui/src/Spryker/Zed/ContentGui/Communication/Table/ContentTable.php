<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Table;

use Orm\Zed\Content\Persistence\Map\SpyContentTableMap;
use Orm\Zed\Content\Persistence\SpyContentQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class ContentTable extends AbstractTable
{
    /**
     * @var \Orm\Zed\Content\Persistence\SpyContentQuery
     */
    private $contentQuery;

    /**
     * @var \Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToUtilDateTimeServiceInterface
     */
    private $utilDateTimeService;

    /**
     * @param \Orm\Zed\Content\Persistence\SpyContentQuery $contentQuery
     * @param \Spryker\Zed\ContentGui\Dependency\Service\ContentGuiToUtilDateTimeServiceInterface $utilDateTimeService
     */
    public function __construct(
        SpyContentQuery $contentQuery,
        ContentGuiToUtilDateTimeServiceInterface $utilDateTimeService
    ) {
        $this->contentQuery = $contentQuery;
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
            ContentTableConstants::COL_ID_CONTENT,
            ContentTableConstants::COL_NAME,
            ContentTableConstants::COL_CONTENT_TYPE_CANDIDATE_KEY,
        ]);

        $config->addRawColumn(ContentTableConstants::COL_ACTIONS);
        $config->addRawColumn(ContentTableConstants::COL_CONTENT_TYPE_CANDIDATE_KEY);
        $config->setDefaultSortField(ContentTableConstants::COL_ID_CONTENT, TableConfiguration::SORT_DESC);

        $config->setSearchable([
            ContentTableConstants::COL_ID_CONTENT,
            ContentTableConstants::COL_NAME,
            ContentTableConstants::COL_CONTENT_TYPE_CANDIDATE_KEY,
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
        $header = [
            ContentTableConstants::COL_ID_CONTENT => 'Content Item ID',
            ContentTableConstants::COL_NAME => 'Name',
            ContentTableConstants::COL_DESCRIPTION => 'Description',
            ContentTableConstants::COL_CONTENT_TYPE_CANDIDATE_KEY => 'Content Type',
            ContentTableConstants::COL_UPDATED_AT => 'Updated',
            ContentTableConstants::COL_ACTIONS => 'Actions',
        ];

        $config->setHeader($header);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $contentItems = $this->runQuery($this->contentQuery, $config);
        $results = [];

        foreach ($contentItems as $key => $contentItem) {
            $results[] = [
                ContentTableConstants::COL_ID_CONTENT => $contentItem[SpyContentTableMap::COL_ID_CONTENT],
                ContentTableConstants::COL_NAME => $contentItem[SpyContentTableMap::COL_NAME],
                ContentTableConstants::COL_DESCRIPTION => $contentItem[SpyContentTableMap::COL_DESCRIPTION],
                ContentTableConstants::COL_CONTENT_TYPE_CANDIDATE_KEY => $this->buildContentTypeLabel($contentItem[SpyContentTableMap::COL_CONTENT_TYPE_CANDIDATE_KEY]),
                ContentTableConstants::COL_UPDATED_AT => $this->utilDateTimeService->formatDateTime($contentItem[SpyContentTableMap::COL_UPDATED_AT]),
                ContentTableConstants::COL_ACTIONS => $this->buildLinks($contentItem),
            ];
        }

        return $results;
    }

    /**
     * @param array $contentItem
     *
     * @return string
     */
    protected function buildLinks(array $contentItem): string
    {
        $buttons = [];

        $urlParams = [
            ContentTableConstants::REQUEST_TERM_KEY => $contentItem[ContentTableConstants::COL_ID_CONTENT],
            ContentTableConstants::REQUEST_ID_CONTENT => $contentItem[ContentTableConstants::COL_ID_CONTENT],
        ];

        $buttons[] = $this->generateEditButton(
            Url::generate(ContentTableConstants::URL_CONTENT_EDIT, $urlParams),
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
