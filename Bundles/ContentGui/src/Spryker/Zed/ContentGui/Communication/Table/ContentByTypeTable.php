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
    protected const FIELD_ACTION_CONTENT_ITEM = '<input type="radio" %s  data-content-item-type="%s" data-content-item-name="%s" name="content-item" value="%s"/>';

    /**
     * @var string
     */
    protected $contentType;

    /**
     * @var \Orm\Zed\Content\Persistence\SpyContentQuery
     */
    protected $contentQuery;

    /**
     * @var int|null
     */
    protected $idContent;

    /**
     * @param string $contentType
     * @param \Orm\Zed\Content\Persistence\SpyContentQuery $contentQuery
     * @param int|null $idContent
     */
    public function __construct(
        string $contentType,
        SpyContentQuery $contentQuery,
        ?int $idContent = null
    ) {
        $this->contentType = $contentType;
        $this->contentQuery = $contentQuery;
        $this->idContent = $idContent;
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
            ContentTableConstants::COL_ID_CONTENT,
            ContentTableConstants::COL_NAME,
        ]);

        $config->addRawColumn(ContentTableConstants::COL_ACTIONS);
        $config->setDefaultSortField(ContentTableConstants::COL_ID_CONTENT, TableConfiguration::SORT_DESC);
        $config->setSearchable([
            ContentTableConstants::COL_ID_CONTENT,
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
            ContentTableConstants::COL_ID_CONTENT => 'ID',
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

        if ($this->idContent) {
            $orderCondition = sprintf(
                '(CASE WHEN %s = %d THEN 1 END)',
                SpyContentTableMap::COL_ID_CONTENT,
                $this->idContent
            );
            $this->contentQuery->addAscendingOrderByColumn($orderCondition);
        }

        $contentItems = $this->runQuery($this->contentQuery, $config);
        $results = [];

        foreach ($contentItems as $key => $contentItem) {
            $checked = $this->isCheckedItem($key, $contentItem[SpyContentTableMap::COL_ID_CONTENT]);

            $results[] = [
                ContentTableConstants::COL_ACTIONS => $this->buildRadioButton($contentItem, $checked),
                ContentTableConstants::COL_ID_CONTENT => $contentItem[SpyContentTableMap::COL_ID_CONTENT],
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
            $contentItem[ContentTableConstants::COL_CONTENT_TYPE_KEY],
            $contentItem[ContentTableConstants::COL_NAME],
            $contentItem[ContentTableConstants::COL_ID_CONTENT]
        );
    }

    /**
     * @param int $key
     * @param int $idContent
     *
     * @return bool
     */
    protected function isCheckedItem(int $key, int $idContent): bool
    {
        if ($this->idContent) {
            return $this->idContent === $idContent;
        }

        return $key === 0;
    }

    /**
     * @return string
     */
    protected function getTableUrl(): string
    {
        $params = [ListContentByTypeController::PARAM_CONTENT_TYPE => $this->contentType];

        if ($this->idContent) {
            $params[ListContentByTypeController::PARAM_CONTENT_ID] = $this->idContent;
        }

        return Url::generate($this->defaultUrl, $params);
    }
}
