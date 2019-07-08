<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentFileGui;

use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Spryker\Zed\ContentFileGui\Dependency\Facade\ContentFileGuiToContentFileFacadeBridge;
use Spryker\Zed\ContentFileGui\Dependency\Facade\ContentFileGuiToFileManagerFacadeBridge;
use Spryker\Zed\ContentFileGui\Dependency\Facade\ContentFileGuiToLocaleFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\ContentFileGui\ContentFileGuiConfig getConfig()
 */
class ContentFileGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_CONTENT_FILE = 'FACADE_CONTENT_FILE';
    public const FACADE_FILE_MANAGER = 'FACADE_FILE_MANAGER';
    public const PROPEL_QUERY_FILE = 'PROPEL_QUERY_FILE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $this->addFileQueryContainer($container);
        $this->addLocaleFacade($container);
        $this->addContentFileFacade($container);
        $this->addFileManagerFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addFileQueryContainer(Container $container): void
    {
        $container->set(static::PROPEL_QUERY_FILE, function (Container $container) {
            return SpyFileQuery::create();
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addLocaleFacade(Container $container): void
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new ContentFileGuiToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addContentFileFacade(Container $container): void
    {
        $container->set(static::FACADE_CONTENT_FILE, function (Container $container) {
            return new ContentFileGuiToContentFileFacadeBridge($container->getLocator()->contentFile()->facade());
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addFileManagerFacade(Container $container): void
    {
        $container->set(static::FACADE_FILE_MANAGER, function (Container $container) {
            return new ContentFileGuiToFileManagerFacadeBridge($container->getLocator()->fileManager()->facade());
        });
    }
}
