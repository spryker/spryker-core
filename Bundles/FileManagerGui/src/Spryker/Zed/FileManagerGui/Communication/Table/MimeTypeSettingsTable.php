<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Table;

use Orm\Zed\FileManager\Persistence\Map\SpyMimeTypeTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class MimeTypeSettingsTable extends AbstractTable
{
    const COL_ID_MIME_TYPE = SpyMimeTypeTableMap::COL_ID_MIME_TYPE;
    const COL_NAME = SpyMimeTypeTableMap::COL_NAME;
    const COL_COMMENT = SpyMimeTypeTableMap::COL_COMMENT;
    const COL_IS_ALLOWED = SpyMimeTypeTableMap::COL_IS_ALLOWED;
    const COL_ACTIONS = 'Actions';

    const TITLE_MIME_TYPE = 'MIME Type';
    const TITLE_COMMENT = 'Comment';
    const TITLE_IS_ALLOWED = 'Is Allowed';
    const TITLE_ACTIONS = 'Actions';

    const REQUEST_ID_MIME_TYPE = 'id-mime-type';
    const ROUTE_EDIT = 'mime-type/edit';
    const ROUTE_DELETE = 'mime-type/delete';

    /**
     * @var \Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerInterface $queryContainer
     */
    public function __construct(FileManagerGuiToFileManagerQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            static::COL_NAME => static::TITLE_MIME_TYPE,
            static::COL_COMMENT => static::TITLE_COMMENT,
            static::COL_IS_ALLOWED => static::TITLE_IS_ALLOWED,
            static::COL_ACTIONS => static::TITLE_ACTIONS,
        ]);

        $config->setSortable([
            static::COL_NAME,
            static::COL_COMMENT,
        ]);

        $config->setSearchable([
            static::COL_NAME,
            static::COL_COMMENT,
        ]);

        $config->setRawColumns([
            static::COL_IS_ALLOWED,
            static::COL_ACTIONS,
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
        $data = [];
        $query = $this->queryContainer->queryMimeType();
        $queryResults = $this->runQuery($query, $config);

        foreach ($queryResults as $mimeType) {
            $data[] = $this->mapResults($mimeType);
        }

        return $data;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function mapResults(array $item)
    {
        $actions = implode(' ', $this->buildLinks($item));

        return [
            static::COL_NAME => $item[static::COL_NAME],
            static::COL_COMMENT => $item[static::COL_COMMENT],
            static::COL_IS_ALLOWED => $this->addCheckBox($item),
            static::COL_ACTIONS => $actions,
        ];
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function addCheckbox(array $item)
    {
        return sprintf(
            "<input id='mime_type_is_allowed_%s' class='mime_type_is_allowed' type='checkbox' data-id='%s' %s/>",
            $item[static::COL_ID_MIME_TYPE],
            $item[static::COL_ID_MIME_TYPE],
            $item[static::COL_IS_ALLOWED] ? "checked='checked'" : ''
        );
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function buildLinks(array $item)
    {
        $buttons = [];

        $buttons[] = $this->generateEditButton(
            Url::generate(static::ROUTE_EDIT, [
                static::REQUEST_ID_MIME_TYPE => $item[static::COL_ID_MIME_TYPE],
            ]),
            'Edit'
        );
        $buttons[] = $this->generateRemoveButton(
            Url::generate(static::ROUTE_DELETE, [
                static::REQUEST_ID_MIME_TYPE => $item[static::COL_ID_MIME_TYPE],
            ]),
            'Delete'
        );

        return $buttons;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setDefaultSortField(TableConfiguration $config)
    {
        $config->setDefaultSortField(static::COL_ID_MIME_TYPE, TableConfiguration::SORT_ASC);
    }
}
