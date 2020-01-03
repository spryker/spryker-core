<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentFileGui\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\FileManager\Persistence\SpyFile;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\ContentFileGui\Communication\Controller\FileListController;
use Spryker\Zed\ContentFileGui\ContentFileGuiConfig;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class ContentFileListSelectedTable extends AbstractTable
{
    public const TABLE_IDENTIFIER = 'file-list-selected-table';
    public const TABLE_CLASS = 'item-list-selected-table gui-table-data';
    public const BASE_URL = '/content-file-gui/file-list/';

    public const COL_ID_FILE = 'ID';
    public const COL_FILE_NAME = 'File Name';
    public const COL_ACTIONS = 'Actions';

    public const BUTTON_DELETE = 'Delete';
    public const BUTTON_MOVE_UP = 'Move Up';
    public const BUTTON_MOVE_DOWN = 'Move Down';

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
     * @var array
     */
    protected $fileIds;

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileQuery $fileQueryContainer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int[] $fileIds
     * @param string|null $identifierSuffix
     */
    public function __construct(
        SpyFileQuery $fileQueryContainer,
        LocaleTransfer $localeTransfer,
        array $fileIds,
        ?string $identifierSuffix
    ) {
        $this->fileQueryContainer = $fileQueryContainer;
        $this->localeTransfer = $localeTransfer;
        $this->fileIds = $fileIds;
        $this->identifierSuffix = $identifierSuffix;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $parameters = [];

        if ($this->fileIds) {
            $parameters = [FileListController::PARAM_FILE_IDS => $this->fileIds];
        }

        $this->baseUrl = static::BASE_URL;
        $this->defaultUrl = Url::generate(static::TABLE_IDENTIFIER, $parameters)->build();
        $this->tableClass = static::TABLE_CLASS;
        $identifierSuffix = !$this->identifierSuffix ?
            static::TABLE_IDENTIFIER :
            sprintf('%s-%s', static::TABLE_IDENTIFIER, $this->identifierSuffix);
        $this->setTableIdentifier($identifierSuffix);

        $this->disableSearch();

        $config->setHeader([
            static::COL_ID_FILE => static::COL_ID_FILE,
            static::COL_FILE_NAME => static::COL_FILE_NAME,
            static::COL_ACTIONS => static::COL_ACTIONS,
        ]);

        $config->setRawColumns([
            static::COL_ACTIONS,
        ]);

        $config->setStateSave(false);

        return $config;
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function newTableConfiguration(): TableConfiguration
    {
        $tableConfiguration = parent::newTableConfiguration();
        $tableConfiguration->setServerSide(false);
        $tableConfiguration->setPaging(false);
        $tableConfiguration->setOrdering(false);

        return $tableConfiguration;
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
        $results = [];
        if (!$this->fileIds) {
            return $results;
        }

        $fileIds = array_values($this->fileIds);
        $query = $this->fileQueryContainer
            ->filterByIdFile_In($fileIds);

        $this->setLimit(ContentFileGuiConfig::MAX_NUMBER_SELECTABLE_FILES_IN_FILE_LIST);
        $queryResults = $this->runQuery($query, $config, true);

        /** @var \Orm\Zed\FileManager\Persistence\SpyFile $fileEntity */
        foreach ($queryResults as $fileEntity) {
            $index = array_search($fileEntity->getIdFile(), $fileIds);
            $results[$index] = $this->formatRow($fileEntity);
        }
        ksort($results);

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
            static::COL_ACTIONS => $this->getActionButtons($idFile),
        ];
    }

    /**
     * @param int $idFile
     *
     * @return string
     */
    protected function getActionButtons(int $idFile): string
    {
        $actionButtons = [];

        $actionButtons[] = $this->generateButton(
            '#',
            static::BUTTON_DELETE,
            [
                'class' => 'js-delete-item btn-danger',
                'data-id' => $idFile,
                'icon' => 'fa-trash',
                'onclick' => 'return false;',
            ]
        );
        $actionButtons[] = $this->generateButton(
            '#',
            static::BUTTON_MOVE_UP,
            [
                'class' => 'js-reorder-item btn-create',
                'data-id' => $idFile,
                'data-direction' => 'up',
                'icon' => 'fa-arrow-up',
                'onclick' => 'return false;',
            ]
        );
        $actionButtons[] = $this->generateButton(
            '#',
            static::BUTTON_MOVE_DOWN,
            [
                'class' => 'js-reorder-item btn-create',
                'data-id' => $idFile,
                'data-direction' => 'down',
                'icon' => 'fa-arrow-down',
                'onclick' => 'return false;',
            ]
        );

        return implode(' ', $actionButtons);
    }
}
