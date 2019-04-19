<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Table;

use Orm\Zed\Content\Persistence\Map\SpyContentTableMap;
use Orm\Zed\Content\Persistence\SpyContentQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class ContentByTypeTable extends AbstractTable
{
    /**
     * @var string
     */
    private $contentType;

    /**
     * @var \Orm\Zed\Content\Persistence\SpyContentQuery
     */
    private $contentQuery;

    /**
     * @param string $contentType
     * @param \Orm\Zed\Content\Persistence\SpyContentQuery $contentQuery
     */
    public function __construct(
        string $contentType,
        SpyContentQuery $contentQuery
    ) {
        $this->contentType = $contentType;
        $this->contentQuery = $contentQuery;
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
        ]);

        $config->addRawColumn(ContentTableConstants::COL_ACTIONS);
        $config->setDefaultSortField(ContentTableConstants::COL_ID_CONTENT, TableConfiguration::SORT_DESC);
        $config->setSearchable([
            ContentTableConstants::COL_ID_CONTENT,
            ContentTableConstants::COL_NAME,
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
            ContentTableConstants::COL_ACTIONS => '',
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
        $this->contentQuery->filterByContentTypeKey($this->contentType);
        $contentItems = $this->runQuery($this->contentQuery, $config);
        $results = [];

        foreach ($contentItems as $key => $contentItem) {
            $results[] = [
                ContentTableConstants::COL_ID_CONTENT => $contentItem[SpyContentTableMap::COL_ID_CONTENT],
                ContentTableConstants::COL_NAME => $contentItem[SpyContentTableMap::COL_NAME],
                ContentTableConstants::COL_ACTIONS => $this->buildLinks($contentItem, !$results),
            ];
        }

        return $results;
    }

    /**
     * @param array $contentItem
     * @param bool $checked
     *
     * @return string
     */
    protected function buildLinks(array $contentItem, bool $checked = false): string
    {
        $selectedAttr = $checked ? 'checked="checked"' : '';

        return sprintf('<input type="radio" %s name="content-item" value="%s"/>', $selectedAttr, $contentItem[ContentTableConstants::COL_ID_CONTENT]);
    }
}
