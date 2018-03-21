<?php

/**
 * MIT License
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
        $moduleDataImportDirectory = $this->getModuleRoot() . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'import';

        return $this->buildImporterConfiguration($moduleDataImportDirectory . DIRECTORY_SEPARATOR . 'category.csv', static::IMPORT_TYPE_CATEGORY);
    }

    /**
     * @return string
     */
    protected function getModuleRoot(): string
    {
        $moduleRoot = realpath(
            __DIR__
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
        );

        return $moduleRoot;
    }
}
