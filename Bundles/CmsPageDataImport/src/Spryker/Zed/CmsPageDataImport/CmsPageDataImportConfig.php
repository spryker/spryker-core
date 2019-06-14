<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsPageDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\DataImportConfig;

class CmsPageDataImportConfig extends DataImportConfig
{
    public const IMPORT_TYPE_CMS_PAGE = 'cms-page';
    public const IMPORT_TYPE_CMS_PAGE_STORE = 'cms-page-store';

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getCmsPageDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleDataImportDirectory();

        return $this->buildImporterConfiguration($moduleDataImportDirectory . 'cms_page.csv', static::IMPORT_TYPE_CMS_PAGE);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getCmsPageStoreDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleDataImportDirectory();

        return $this->buildImporterConfiguration($moduleDataImportDirectory . 'cms_page_store.csv', static::IMPORT_TYPE_CMS_PAGE_STORE);
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

        return $moduleRoot . DIRECTORY_SEPARATOR;
    }

    /**
     * @return string
     */
    protected function getModuleDataImportDirectory(): string
    {
        return $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;
    }
}
