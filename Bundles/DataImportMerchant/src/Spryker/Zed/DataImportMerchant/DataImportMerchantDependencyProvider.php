<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchant;

use Spryker\Zed\DataImportMerchant\Dependency\Facade\DataImportMerchantToDataImportFacadeBridge;
use Spryker\Zed\DataImportMerchant\Dependency\Facade\DataImportMerchantToMerchantFacadeBridge;
use Spryker\Zed\DataImportMerchant\Dependency\Facade\DataImportMerchantToUserFacadeBridge;
use Spryker\Zed\DataImportMerchant\Dependency\Service\DataImportMerchantToFileSystemServiceBridge;
use Spryker\Zed\DataImportMerchant\Dependency\Service\DataImportMerchantToUtilEncodingServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\DataImportMerchant\DataImportMerchantConfig getConfig()
 */
class DataImportMerchantDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const SERVICE_FILE_SYSTEM = 'SERVICE_FILE_SYSTEM';

    /**
     * @var string
     */
    public const FACADE_USER = 'FACADE_USER';

    /**
     * @var string
     */
    public const FACADE_MERCHANT = 'FACADE_MERCHANT';

    /**
     * @var string
     */
    public const FACADE_DATA_IMPORT = 'FACADE_DATA_IMPORT';

    /**
     * @var string
     */
    public const PLUGINS_DATA_IMPORT_MERCHANT_FILE_VALIDATOR = 'PLUGINS_DATA_IMPORT_MERCHANT_FILE_VALIDATOR';

    /**
     * @var string
     */
    public const PLUGINS_DATA_IMPORT_MERCHANT_FILE_EXPANDER = 'PLUGINS_DATA_IMPORT_MERCHANT_FILE_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_DATA_IMPORT_MERCHANT_FILE_REQUEST_EXPANDER = 'PLUGINS_DATA_IMPORT_MERCHANT_FILE_REQUEST_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_POSSIBLE_CSV_HEADER_EXPANDER = 'PLUGINS_POSSIBLE_CSV_HEADER_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addUserFacade($container);
        $container = $this->addMerchantFacade($container);
        $container = $this->addDataImportFacade($container);
        $container = $this->addFileSystemService($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addDataImportMerchantFileValidatorPlugins($container);
        $container = $this->addDataImportMerchantFileExpanderPlugins($container);
        $container = $this->addDataImportMerchantFileRequestExpanderPlugins($container);
        $container = $this->addPossibleCsvHeaderExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new DataImportMerchantToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFileSystemService(Container $container): Container
    {
        $container->set(static::SERVICE_FILE_SYSTEM, function (Container $container) {
            return new DataImportMerchantToFileSystemServiceBridge(
                $container->getLocator()->fileSystem()->service(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_USER, function (Container $container) {
            return new DataImportMerchantToUserFacadeBridge(
                $container->getLocator()->user()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT, function (Container $container) {
            return new DataImportMerchantToMerchantFacadeBridge(
                $container->getLocator()->merchant()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDataImportFacade(Container $container): Container
    {
        $container->set(static::FACADE_DATA_IMPORT, function (Container $container) {
            return new DataImportMerchantToDataImportFacadeBridge(
                $container->getLocator()->dataImport()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDataImportMerchantFileValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_DATA_IMPORT_MERCHANT_FILE_VALIDATOR, function () {
            return $this->getDataImportMerchantFileValidatorPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDataImportMerchantFileExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_DATA_IMPORT_MERCHANT_FILE_EXPANDER, function () {
            return $this->getDataImportMerchantFileExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDataImportMerchantFileRequestExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_DATA_IMPORT_MERCHANT_FILE_REQUEST_EXPANDER, function () {
            return $this->getDataImportMerchantFileRequestExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPossibleCsvHeaderExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_POSSIBLE_CSV_HEADER_EXPANDER, function () {
            return $this->getPossibleCsvHeaderExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\DataImportMerchantFileValidatorPluginInterface>
     */
    protected function getDataImportMerchantFileValidatorPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\DataImportMerchantFileExpanderPluginInterface>
     */
    protected function getDataImportMerchantFileExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\DataImportMerchantFileRequestExpanderPluginInterface>
     */
    protected function getDataImportMerchantFileRequestExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\PossibleCsvHeaderExpanderPluginInterface>
     */
    protected function getPossibleCsvHeaderExpanderPlugins(): array
    {
        return [];
    }
}
