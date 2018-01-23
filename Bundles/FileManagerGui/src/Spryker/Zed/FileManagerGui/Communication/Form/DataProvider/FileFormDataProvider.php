<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Form\DataProvider;

use Spryker\Zed\FileManagerGui\Communication\Form\FileForm;
use Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerBridgeInterface;

class FileFormDataProvider
{
    /**
     * @var \Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerBridgeInterface
     */
    private $queryContainer;

    /**
     * @param \Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerBridgeInterface $queryContainer
     */
    public function __construct(
        FileManagerGuiToFileManagerQueryContainerBridgeInterface $queryContainer
    ) {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int|null $idFile
     *
     * @return array
     */
    public function getData($idFile = null)
    {
        if ($idFile === null) {
            return [];
        }

        $file = $this
            ->queryContainer
            ->queryFileById($idFile)
            ->findOne();

        return [
            FileForm::FIELD_ID_FILE => $idFile,
            FileForm::FIELD_FILE_NAME => $file->getFileName(),
        ];
    }
}
