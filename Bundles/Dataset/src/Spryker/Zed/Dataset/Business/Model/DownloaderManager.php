<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use Generated\Shared\Transfer\DatasetFilenameTransfer;

class DownloaderManager implements DownloaderManagerInterface
{
    const DEFAULT_NAME = 'dataset';

    /**
     * @param string|null $datasetName
     *
     * @return \Generated\Shared\Transfer\DatasetFilenameTransfer
     */
    public function getFilenameByDatasetName($datasetName)
    {
        $filename = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $datasetName);
        $filename = mb_ereg_replace("([\.]{2,})", '', $filename);
        $filename = preg_replace("/\s+/", ' ', $filename);
        $filename = trim($filename);

        if (!strlen($filename) || $filename === '.') {
            $filename = static::DEFAULT_NAME;
        }

        $datasetFilenameTransfer = new DatasetFilenameTransfer();
        $datasetFilenameTransfer->setFilename($filename);

        return $datasetFilenameTransfer;
    }
}
