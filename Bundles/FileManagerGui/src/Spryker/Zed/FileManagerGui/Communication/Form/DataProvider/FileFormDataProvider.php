<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Form\DataProvider;

use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainer;
use Spryker\Zed\FileManagerGui\Communication\Form\FileForm;

class FileFormDataProvider
{
    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainer
     */
    private $queryContainer;

    /**
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainer $queryContainer
     */
    public function __construct(
        FileManagerQueryContainer $queryContainer
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
