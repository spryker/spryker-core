<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentFileGui\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\FileManager\Persistence\SpyFile;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class ContentFileListViewTable extends AbstractTable
{
    public const TABLE_IDENTIFIER = 'file-list-view-table';
    public const TABLE_CLASS = 'item-list-view-table gui-table-data';
    public const BASE_URL = '/content-file-gui/file-list/';

    public const HEADER_NAME = 'File Name';
    public const HEADER_ID_FILE = 'ID';

    public const COL_ID_FILE = 'id_file';
    public const COL_FILE_NAME = 'file_name';

    /**
     * @deprecated Use Spryker\Zed\ContentFileGui\Communication\Table\ContentFileListViewTable::COL_ACTIONS instead.
     */
    public const COL_SELECTED = 'Actions';
    public const COL_ACTIONS = 'Actions';

    /**
     * @var \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    protected $fileQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @var string|null
     */
    protected $identifierSuffix;

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileQuery $fileQueryContainer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string|null $identifierSuffix
     */
    public function __construct(
        SpyFileQuery $fileQueryContainer,
        LocaleTransfer $localeTransfer,
        ?string $identifierSuffix
    ) {
        $this->fileQueryContainer = $fileQueryContainer;
        $this->localeTransfer = $localeTransfer;
        $this->identifierSuffix = $identifierSuffix;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $this->baseUrl = static::BASE_URL;
        $this->defaultUrl = static::TABLE_IDENTIFIER;
        $this->tableClass = static::TABLE_CLASS;

        $identifierSuffix = !$this->identifierSuffix ?
            static::TABLE_IDENTIFIER :
            sprintf('%s-%s', static::TABLE_IDENTIFIER, $this->identifierSuffix);
        $this->setTableIdentifier($identifierSuffix);

        $config->setHeader([
            static::COL_ID_FILE => static::HEADER_ID_FILE,
            static::COL_FILE_NAME => static::HEADER_NAME,
            static::COL_ACTIONS => static::COL_ACTIONS,
        ]);

        $config->setSearchable([
            static::COL_ID_FILE,
            static::COL_FILE_NAME,
        ]);

        $config->setRawColumns([
            static::COL_ACTIONS,
        ]);

        $config->setStateSave(false);

        return $config;
    }

    /**
     * @module FileManager
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $queryResults = $this->runQuery($this->fileQueryContainer, $config, true);

        $results = [];
        foreach ($queryResults as $fileEntity) {
            $results[] = $this->formatRow($fileEntity);
        }

        return $results;
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $fileEntity
     *
     * @return array
     */
    protected function formatRow(SpyFile $fileEntity): array
    {
        $idFile = $fileEntity->getIdFile();

        return [
            static::COL_ID_FILE => $idFile,
            static::COL_FILE_NAME => $fileEntity->getFileName(),
            static::COL_ACTIONS => $this->getAddButtonField($idFile),
        ];
    }

    /**
     * @param int $idFile
     *
     * @return string
     */
    public function getAddButtonField(int $idFile): string
    {
        return $actionButtons[] = $this->generateButton(
            '#',
            'Add to list',
            [
                'class' => 'btn-create js-add-item',
                'data-id' => $idFile,
                'icon' => 'fa-plus',
                'onclick' => 'return false;',
            ]
        );
    }
}
