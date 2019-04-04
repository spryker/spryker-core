<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Table;

use Orm\Zed\Content\Persistence\Map\SpyContentTableMap;
use Orm\Zed\Content\Persistence\SpyContentQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class ContentTable extends AbstractTable
{
    /**
     * @var \Orm\Zed\Content\Persistence\SpyContentQuery
     */
    private $contentQuery;

    /**
     * @var string[]
     */
    private $contentTypeKeyCache = [];

    /**
     * @param \Orm\Zed\Content\Persistence\SpyContentQuery $contentQuery
     * @param \Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentPluginInterface[] $contentPlugins
     */
    public function __construct(
        SpyContentQuery $contentQuery,
        array $contentPlugins
    ) {
        $this->contentQuery = $contentQuery;
        $this->contentTypeKeyCache = $this->populateContentTypeKeyCache($contentPlugins);
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
            ContentTableConstants::COL_CONTENT_TYPE_KEY,
            ContentTableConstants::COL_CREATED_AT,
            ContentTableConstants::COL_UPDATED_AT,
        ]);

        $config->addRawColumn(ContentTableConstants::COL_ACTIONS);
        $config->addRawColumn(ContentTableConstants::COL_CONTENT_TYPE_KEY);
        $config->setDefaultSortField(ContentTableConstants::COL_ID_CONTENT, TableConfiguration::SORT_DESC);

        $config->setSearchable([
            ContentTableConstants::COL_ID_CONTENT,
            ContentTableConstants::COL_NAME,
            ContentTableConstants::COL_DESCRIPTION,
            ContentTableConstants::COL_CONTENT_TYPE_KEY,
            ContentTableConstants::COL_CREATED_AT,
            ContentTableConstants::COL_UPDATED_AT,
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
            ContentTableConstants::COL_CONTENT_TYPE_KEY => 'Content Type',
            ContentTableConstants::COL_CREATED_AT => 'Created',
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
        $contents = $this->runQuery($this->contentQuery, $config);
        $results = [];

        foreach ($contents as $key => $content) {
            $results[] = [
                ContentTableConstants::COL_ID_CONTENT => $content[SpyContentTableMap::COL_ID_CONTENT],
                ContentTableConstants::COL_NAME => $content[SpyContentTableMap::COL_NAME],
                ContentTableConstants::COL_DESCRIPTION => $content[SpyContentTableMap::COL_DESCRIPTION],
                ContentTableConstants::COL_CONTENT_TYPE_KEY => $this->buildContentTypeLabel($content[SpyContentTableMap::COL_CONTENT_TYPE_KEY]),
                ContentTableConstants::COL_CREATED_AT => date('Y-m-d H:i', strtotime($content[SpyContentTableMap::COL_CREATED_AT])),
                ContentTableConstants::COL_UPDATED_AT => date('Y-m-d H:i', strtotime($content[SpyContentTableMap::COL_UPDATED_AT])),
                ContentTableConstants::COL_ACTIONS => $this->buildLinks($content),
            ];
        }

        return $results;
    }

    /**
     * @param array $content
     *
     * @return string
     */
    protected function buildLinks(array $content): string
    {
        if (!$this->isContentTypeEnabled($content[ContentTableConstants::COL_CONTENT_TYPE_KEY])) {
            return '';
        }

        $buttons = [];

        $urlParams = [
            ContentTableConstants::REQUEST_TERM_KEY => $content[ContentTableConstants::COL_TERM_KEY],
            ContentTableConstants::REQUEST_ID_CONTENT => $content[ContentTableConstants::COL_ID_CONTENT],
        ];

        $buttons[] = $this->generateEditButton(
            Url::generate(ContentTableConstants::URL_CONTENT_EDIT, $urlParams),
            'Edit'
        );

        return implode(' ', $buttons);
    }

    /**
     * @param \Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentPluginInterface[] $contentPlugins
     *
     * @return array
     */
    protected function populateContentTypeKeyCache(array $contentPlugins): array
    {
        $contentTypeKeyCache = [];
        foreach ($contentPlugins as $contentPlugin) {
            $contentTypeKeyCache = $contentPlugin->getTypeKey();
        }

        return array_unique($contentTypeKeyCache);
    }

    /**
     * @param string $contentTypeKey
     *
     * @return bool
     */
    protected function isContentTypeEnabled(string $contentTypeKey): bool
    {
        return in_array($contentTypeKey, $this->contentTypeKeyCache, true);
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
