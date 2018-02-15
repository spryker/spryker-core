<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDataImport;

use Spryker\Zed\DataImport\DataImportConfig;

class CategoryDataImportConfig extends DataImportConfig
{
    const IMPORT_TYPE_CATEGORY = 'category';

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getCategoryDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('icecat_biz_data' . DIRECTORY_SEPARATOR . 'category.csv', static::IMPORT_TYPE_CATEGORY);
    }
}
