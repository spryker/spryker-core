<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantFile;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantFile\Dependency\Facade\MerchantFileToMerchantUserBridge;
use Spryker\Zed\MerchantFile\Dependency\Facade\MerchantFileToMerchantUserInterface;
use Spryker\Zed\MerchantFile\Dependency\Service\MerchantFileToFileSystemServiceBridge;
use Spryker\Zed\MerchantFile\Dependency\Service\MerchantFileToFileSystemServiceInterface;

/**
 * @method \Spryker\Zed\MerchantFile\MerchantFileConfig getConfig()
 */
class MerchantFileDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const SERVICE_FILE_SYSTEM = 'SERVICE_FILE_SYSTEM';

    /**
     * @var string
     */
    public const FACADE_MERCHANT_USER = 'FACADE_MERCHANT_USER';

    /**
     * @var string
     */
    public const PLUGINS_MERCHANT_FILE_POST_SAVE = 'PLUGINS_MERCHANT_FILE_POST_SAVE';

    /**
     * @var string
     */
    public const PLUGINS_MERCHANT_FILE_VALIDATION = 'PLUGINS_MERCHANT_FILE_VALIDATION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addMerchantUserFacade($container);
        $container = $this->addFileSystemService($container);
        $container = $this->addMerchantFilePostSavePlugins($container);
        $container = $this->addMerchantFileValidationPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFileSystemService(Container $container): Container
    {
        $container->set(
            static::SERVICE_FILE_SYSTEM,
            static function (Container $container): MerchantFileToFileSystemServiceInterface {
                return new MerchantFileToFileSystemServiceBridge(
                    $container->getLocator()->fileSystem()->service(),
                );
            },
        );

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantUserFacade(Container $container): Container
    {
        $container->set(
            static::FACADE_MERCHANT_USER,
            static function (Container $container): MerchantFileToMerchantUserInterface {
                return new MerchantFileToMerchantUserBridge(
                    $container->getLocator()->merchantUser()->facade(),
                );
            },
        );

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantFilePostSavePlugins(Container $container): Container
    {
        $container->set(
            static::PLUGINS_MERCHANT_FILE_POST_SAVE,
            function (): array {
                return $this->getMerchantFilePostSavePlugins();
            },
        );

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\MerchantFileExtension\Dependency\Plugin\MerchantFilePostSavePluginInterface>
     */
    protected function getMerchantFilePostSavePlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantFileValidationPlugins(Container $container): Container
    {
        $container->set(
            static::PLUGINS_MERCHANT_FILE_VALIDATION,
            function (): array {
                return $this->getMerchantFileValidationPlugins();
            },
        );

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\MerchantFileExtension\Dependency\Plugin\MerchantFileValidationPluginInterface>
     */
    protected function getMerchantFileValidationPlugins(): array
    {
        return [];
    }
}
