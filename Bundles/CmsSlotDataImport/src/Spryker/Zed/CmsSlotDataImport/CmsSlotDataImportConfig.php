<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsSlotDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\DataImportConfig;

class CmsSlotDataImportConfig extends DataImportConfig
{
    /**
     * @var string
     */
    public const IMPORT_CMS_SLOT_FILE_NAME = 'cms_slot.csv';
    /**
     * @var string
     */
    public const IMPORT_TYPE_CMS_SLOT = 'cms-slot';
    /**
     * @var string
     */
    public const IMPORT_CMS_SLOT_TEMPLATE_FILE_NAME = 'cms_slot_template.csv';
    /**
     * @var string
     */
    public const IMPORT_TYPE_CMS_SLOT_TEMPLATE = 'cms-slot-template';

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getCmsSlotDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;

        return $this->buildImporterConfiguration($moduleDataImportDirectory . static::IMPORT_CMS_SLOT_FILE_NAME, static::IMPORT_TYPE_CMS_SLOT);
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getCmsSlotTemplateDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;

        return $this->buildImporterConfiguration($moduleDataImportDirectory . static::IMPORT_CMS_SLOT_TEMPLATE_FILE_NAME, static::IMPORT_TYPE_CMS_SLOT_TEMPLATE);
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
}
