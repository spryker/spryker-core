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
    protected const FIELD_ACTION_CONTENT_ITEM = '<input type="radio" %s data-content-item-name="%s" data-id="%d" name="content-item" value="%s"/>';

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
    protected $contentKey;

    /**
     * @param string $contentType
     * @param \Orm\Zed\Content\Persistence\SpyContentQuery $contentQuery
     * @param string|null $contentKey
     */
    public function __construct(
        string $contentType,
        SpyContentQuery $contentQuery,
        ?string $contentKey = null
    ) {
        $this->contentType = $contentType;
        $this->contentQuery = $contentQuery;
        $this->contentKey = $contentKey;
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
        $config->setDefaultSortField(ContentTableConstants::COL_NAME, TableConfiguration::SORT_ASC);
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
            ContentTableConstants::COL_KEY => 'Content Item Key',
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

        if ($this->contentKey) {
            $this->addOrderBySelectedKey();
        }

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
     * @return void
     */
    protected function addOrderBySelectedKey(): void
    {
        $keyColumn = SpyContentTableMap::COL_KEY;
        $selectedKeyColumn = sprintf("(CASE WHEN $keyColumn = '%s' THEN 0 ELSE 1 END)", $this->contentKey);

        $this->contentQuery
            ->withColumn($selectedKeyColumn, 'selectedKey')
            ->orderBy('selectedKey')
            ->orderBy(SpyContentTableMap::COL_ID_CONTENT);
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
            $contentItem[ContentTableConstants::COL_NAME],
            $contentItem[ContentTableConstants::COL_ID_CONTENT],
            $contentItem[ContentTableConstants::COL_KEY]
        );
    }

    /**
     * @param int $key
     * @param string $contentKey
     *
     * @return bool
     */
    protected function isCheckedItem(int $key, string $contentKey): bool
    {
        if ($this->contentKey) {
            return $this->contentKey === $contentKey;
        }

        return $key === 0;
    }

    /**
     * @return string
     */
    protected function getTableUrl(): string
    {
        $params = [ListContentByTypeController::PARAM_CONTENT_TYPE => $this->contentType];

        if ($this->contentKey) {
            $params[ListContentByTypeController::PARAM_CONTENT_KEY] = $this->contentKey;
        }

        return Url::generate($this->defaultUrl, $params);
    }
}
