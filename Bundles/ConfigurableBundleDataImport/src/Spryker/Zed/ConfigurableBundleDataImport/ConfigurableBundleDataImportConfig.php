<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ConfigurableBundleDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\DataImportConfig;

class ConfigurableBundleDataImportConfig extends DataImportConfig
{
    public const IMPORT_TYPE_CONFIGURABLE_BUNDLE_TEMPLATE = 'configurable-bundle-template';
    public const IMPORT_TYPE_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT = 'configurable-bundle-template-slot';
    public const IMPORT_TYPE_CONFIGURABLE_BUNDLE_TEMPLATE_IMAGE = 'configurable-bundle-template-image';

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getConfigurableBundleTemplateDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;

        return $this->buildImporterConfiguration(
            $moduleDataImportDirectory . 'configurable_bundle_template.csv',
            static::IMPORT_TYPE_CONFIGURABLE_BUNDLE_TEMPLATE
        );
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getConfigurableBundleTemplateSlotDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;

        return $this->buildImporterConfiguration(
            $moduleDataImportDirectory . 'configurable_bundle_template_slot.csv',
            static::IMPORT_TYPE_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT
        );
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getConfigurableBundleTemplateImageDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;

        return $this->buildImporterConfiguration(
            $moduleDataImportDirectory . 'configurable_bundle_template_image.csv',
            static::IMPORT_TYPE_CONFIGURABLE_BUNDLE_TEMPLATE_IMAGE
        );
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
