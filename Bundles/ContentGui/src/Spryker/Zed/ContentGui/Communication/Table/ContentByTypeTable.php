<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Table;

use Orm\Zed\Content\Persistence\Map\SpyContentTableMap;
use Orm\Zed\Content\Persistence\SpyContentQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\ContentGui\Communication\Controller\ListContentByTypeController;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class ContentByTypeTable extends AbstractTable
{
    protected const FIELD_ACTION_CONTENT_ITEM = '<input type="radio" %s name="content-item" value="%s"/>';

    /**
     * @var string
     */
    protected $contentType;

    /**
     * @var \Orm\Zed\Content\Persistence\SpyContentQuery
     */
    protected $contentQuery;

    /**
     * @var string|null
     */
    protected $contentItemKey;

    /**
     * @param string $contentType
     * @param \Orm\Zed\Content\Persistence\SpyContentQuery $contentQuery
     * @param string|null $contentItemKey
     */
    public function __construct(
        string $contentType,
        SpyContentQuery $contentQuery,
        ?string $contentItemKey = null
    ) {
        $this->contentType = $contentType;
        $this->contentQuery = $contentQuery;
        $this->contentItemKey = $contentItemKey;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config = $this->setHeader($config);

        $config->setUrl($this->getTableUrl());
        $config->setSortable([
            ContentTableConstants::COL_KEY,
            ContentTableConstants::COL_NAME,
        ]);

        $config->addRawColumn(ContentTableConstants::COL_ACTIONS);
        $config->setDefaultSortField(ContentTableConstants::COL_KEY, TableConfiguration::SORT_DESC);
        $config->setSearchable([
            ContentTableConstants::COL_KEY,
            ContentTableConstants::COL_NAME,
        ]);
        $config->setStateSave(false);

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
            ContentTableConstants::COL_ACTIONS => '',
            ContentTableConstants::COL_KEY => 'KEY',
            ContentTableConstants::COL_NAME => 'Name',
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
            $checked = $this->isCheckedItem($key, $contentItem[SpyContentTableMap::COL_KEY]);

            $results[] = [
                ContentTableConstants::COL_ACTIONS => $this->buildRadioButton($contentItem, $checked),
                ContentTableConstants::COL_KEY => $contentItem[SpyContentTableMap::COL_KEY],
                ContentTableConstants::COL_NAME => $contentItem[SpyContentTableMap::COL_NAME],
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
    protected function buildRadioButton(array $contentItem, bool $checked = false): string
    {
        $selectedAttr = $checked ? 'checked="checked"' : '';

        return sprintf(
            static::FIELD_ACTION_CONTENT_ITEM,
            $selectedAttr,
            $contentItem[ContentTableConstants::COL_KEY]
        );
    }

    /**
     * @param int $key
     * @param string $contentItemKey
     *
     * @return bool
     */
    protected function isCheckedItem(int $key, string $contentItemKey): bool
    {
        if ($this->contentItemKey) {
            return $this->contentItemKey === $contentItemKey;
        }

        return $key === 0;
    }

    /**
     * @return string
     */
    protected function getTableUrl(): string
    {
        return Url::generate($this->defaultUrl, [ListContentByTypeController::PARAM_CONTENT_TYPE => $this->contentType]);
    }
}
