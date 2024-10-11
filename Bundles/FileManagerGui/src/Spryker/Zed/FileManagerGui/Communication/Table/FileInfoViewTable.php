<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Table;

use Orm\Zed\FileManager\Persistence\SpyFileInfoQuery;
use Spryker\Service\UtilText\Model\Url\Url;

class FileInfoViewTable extends FileInfoTable
{
    /**
     * @var array<\Spryker\Zed\FileManagerGuiExtension\Dependency\Plugin\FileInfoViewTableActionsExpanderPluginInterface>
     */
    protected array $tableActionsExpanderPlugins;

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileInfoQuery $fileInfoQuery
     * @param int $idFile
     * @param array<\Spryker\Zed\FileManagerGuiExtension\Dependency\Plugin\FileInfoViewTableActionsExpanderPluginInterface> $tableActionsExpanderPlugins
     */
    public function __construct(
        SpyFileInfoQuery $fileInfoQuery,
        int $idFile,
        array $tableActionsExpanderPlugins
    ) {
        parent::__construct($fileInfoQuery, $idFile);

        $this->tableActionsExpanderPlugins = $tableActionsExpanderPlugins;
    }

    /**
     * @param array<mixed> $item
     *
     * @return array<string>
     */
    protected function buildLinks($item)
    {
        $buttons = [];

        $buttons[] = $this->generateViewButton(
            Url::generate('/file-manager-gui/download-file', [
                static::REQUEST_ID_FILE_INFO => $item[static::COL_ID_FILE_INFO],
            ]),
            'Download',
        );

        return $this->expandLinks($item, $buttons);
    }

    /**
     * @param array<string, mixed> $item
     * @param array<string> $buttons
     *
     * @return array<string>
     */
    protected function expandLinks(array $item, array $buttons): array
    {
        $buttonCollection = [];

        foreach ($this->tableActionsExpanderPlugins as $tableActionsExpanderPlugin) {
            $buttonCollection = $tableActionsExpanderPlugin->execute($item, $buttonCollection);
        }

        return array_merge($buttons, $this->generateButtons($buttonCollection));
    }

    /**
     * @param array<\Generated\Shared\Transfer\ButtonTransfer> $buttonTransferCollection
     *
     * @return array<string>
     */
    protected function generateButtons(array $buttonTransferCollection): array
    {
        $generatedButtons = [];

        foreach ($buttonTransferCollection as $buttonTransfer) {
            $generatedButtons[] = $this->generateButton(
                $buttonTransfer->getUrlOrFail(),
                $buttonTransfer->getTitleOrFail(),
                $buttonTransfer->getDefaultOptions(),
                $buttonTransfer->getCustomOptions(),
            );
        }

        return $generatedButtons;
    }
}
