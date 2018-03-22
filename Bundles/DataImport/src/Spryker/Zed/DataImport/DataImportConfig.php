<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport;

use Spryker\Shared\DataImport\DataImportConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class DataImportConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getDataImportRootPath()
    {
        $defaultPath = $this->getDefaultPath();
        $dataImportRootPath = $this->get(DataImportConstants::IMPORT_FILE_ROOT_PATH, $defaultPath);

        return rtrim($dataImportRootPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    /**
     * @return string
     */
    private function getDefaultPath()
    {
        $pathParts = [
            APPLICATION_ROOT_DIR,
            'data',
            'import',
        ];

        return implode(DIRECTORY_SEPARATOR, $pathParts) . DIRECTORY_SEPARATOR;
    }
}
