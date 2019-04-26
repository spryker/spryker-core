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
    private $contentType;

    /**
     * @var \Orm\Zed\Content\Persistence\SpyContentQuery
     */
    private $contentQuery;

    /**
     * @var int
     */
    private $idContent;

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
            ContentTableConstants::COL_ID_CONTENT => 'ID',
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
            $checked = $this->isCheckedItem($key, $contentItem[SpyContentTableMap::COL_ID_CONTENT]);

            $results[] = [
                ContentTableConstants::COL_ID_CONTENT => $contentItem[SpyContentTableMap::COL_ID_CONTENT],
                ContentTableConstants::COL_NAME => $contentItem[SpyContentTableMap::COL_NAME],
                ContentTableConstants::COL_ACTIONS => $this->buildLinks($contentItem, $checked),
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

        return sprintf(
            static::FIELD_ACTION_CONTENT_ITEM,
            $selectedAttr,
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
        return Url::generate($this->defaultUrl, [ListContentByTypeController::PARAM_CONTENT_TYPE => $this->contentType]);
    }
}
