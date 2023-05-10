<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ServicePointDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\DataImportConfig;

class ServicePointDataImportConfig extends DataImportConfig
{
    /**
     * @api
     *
     * @var string
     */
    public const IMPORT_TYPE_SERVICE_POINT = 'service-point';

    /**
     * @api
     *
     * @var string
     */
    public const IMPORT_TYPE_SERVICE_POINT_ADDRESS = 'service-point-address';

    /**
     * @api
     *
     * @var string
     */
    public const IMPORT_TYPE_SERVICE_POINT_STORE = 'service-point-store';

    /**
     * @api
     *
     * @var string
     */
    public const IMPORT_TYPE_SERVICE_TYPE = 'service-type';

    /**
     * @api
     *
     * @var string
     */
    public const IMPORT_TYPE_SERVICE_POINT_SERVICE = 'service-point-service';

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getServicePointDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;

        return $this->buildImporterConfiguration(
            $moduleDataImportDirectory . 'service_point.csv',
            static::IMPORT_TYPE_SERVICE_POINT,
        );
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getServicePointAddressDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;

        return $this->buildImporterConfiguration(
            $moduleDataImportDirectory . 'service_point_address.csv',
            static::IMPORT_TYPE_SERVICE_POINT_ADDRESS,
        );
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getServicePointStoreDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;

        return $this->buildImporterConfiguration(
            $moduleDataImportDirectory . 'service_point_store.csv',
            static::IMPORT_TYPE_SERVICE_POINT_STORE,
        );
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getServiceTypeDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;

        return $this->buildImporterConfiguration(
            $moduleDataImportDirectory . 'service_type.csv',
            static::IMPORT_TYPE_SERVICE_TYPE,
        );
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getServicePointServiceDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;

        return $this->buildImporterConfiguration(
            $moduleDataImportDirectory . 'service_point_service.csv',
            static::IMPORT_TYPE_SERVICE_POINT_SERVICE,
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
            . DIRECTORY_SEPARATOR . '..',
        );

        return $moduleRoot . DIRECTORY_SEPARATOR;
    }
}
