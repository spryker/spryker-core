<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui;

use Spryker\Zed\FileImportMerchantPortalGui\Dependency\Facade\FileImportMerchantPortalGuiToDataImportFacadeBridge;
use Spryker\Zed\FileImportMerchantPortalGui\Dependency\Facade\FileImportMerchantPortalGuiToMerchantFileFacadeBridge;
use Spryker\Zed\FileImportMerchantPortalGui\Dependency\Facade\FileImportMerchantPortalGuiToTranslatorFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\FileImportMerchantPortalGui\FileImportMerchantPortalGuiConfig getConfig()
 */
class FileImportMerchantPortalGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_MERCHANT_FILE = 'FACADE_MERCHANT_FILE';

    /**
     * @var string
     */
    public const FACADE_DATA_IMPORT = 'FACADE_DATA_IMPORT';

    /**
     * @var string
     */
    public const FACADE_TRANSLATOR = 'FACADE_TRANSLATOR';

    /**
     * @uses \Spryker\Zed\GuiTable\Communication\Plugin\Application\GuiTableApplicationPlugin::SERVICE_GUI_TABLE_FACTORY
     *
     * @var string
     */
    public const SERVICE_GUI_TABLE_FACTORY = 'gui_table_factory';

    /**
     * @uses \Spryker\Zed\ZedUi\Communication\Plugin\Application\ZedUiApplicationPlugin::SERVICE_ZED_UI_FACTORY
     *
     * @var string
     */
    public const SERVICE_ZED_UI_FACTORY = 'SERVICE_ZED_UI_FACTORY';

    /**
     * @uses \Spryker\Zed\GuiTable\Communication\Plugin\Application\GuiTableApplicationPlugin::SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR
     *
     * @var string
     */
    public const SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR = 'gui_table_http_data_request_executor';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addMerchantFileFacade($container);
        $container = $this->addDataImportFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addGuiTableFactory($container);
        $container = $this->addGuiTableHttpDataRequestExecutor($container);
        $container = $this->addZedUiFactory($container);
        $container = $this->addTranslatorFacade($container);
        $container = $this->addMerchantFileFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantFileFacade(Container $container): Container
    {
        $container->set(
            static::FACADE_MERCHANT_FILE,
            static fn (Container $container) => new FileImportMerchantPortalGuiToMerchantFileFacadeBridge(
                $container->getLocator()->merchantFile()->facade(),
            ),
        );

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDataImportFacade(Container $container): Container
    {
        $container->set(
            static::FACADE_DATA_IMPORT,
            static fn (Container $container) => new FileImportMerchantPortalGuiToDataImportFacadeBridge(
                $container->getLocator()->dataImport()->facade(),
            ),
        );

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGuiTableFactory(Container $container): Container
    {
        $container->set(
            static::SERVICE_GUI_TABLE_FACTORY,
            static fn (Container $container) => $container->getApplicationService(static::SERVICE_GUI_TABLE_FACTORY),
        );

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGuiTableHttpDataRequestExecutor(Container $container): Container
    {
        $container->set(
            static::SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR,
            static fn (Container $container) => $container->getApplicationService(static::SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR),
        );

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTranslatorFacade(Container $container): Container
    {
        $container->set(
            static::FACADE_TRANSLATOR,
            static fn (Container $container) => new FileImportMerchantPortalGuiToTranslatorFacadeBridge(
                $container->getLocator()->translator()->facade(),
            ),
        );

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addZedUiFactory(Container $container): Container
    {
        $container->set(
            static::SERVICE_ZED_UI_FACTORY,
            static fn (Container $container) => $container->getApplicationService(static::SERVICE_ZED_UI_FACTORY),
        );

        return $container;
    }
}
