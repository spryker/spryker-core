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
        return rtrim($this->get(DataImportConstants::IMPORT_FILE_ROOT_PATH), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

}
