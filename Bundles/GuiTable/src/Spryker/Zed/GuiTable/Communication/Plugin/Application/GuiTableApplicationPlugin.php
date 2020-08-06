<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable\Communication\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\GuiTable\GuiTableConfig getConfig()
 * @method \Spryker\Zed\GuiTable\Communication\GuiTableCommunicationFactory getFactory()
 */
class GuiTableApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    public const SERVICE_GUI_TABLE_FACTORY = 'gui_table_factory';
    public const SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR = 'gui_table_http_data_request_executor';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container = $this->addGuiTableFactoryService($container);
        $container = $this->addGuiTableHttpDataRequestExecutorService($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addGuiTableFactoryService(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_GUI_TABLE_FACTORY, function (): GuiTableFactoryInterface {
            return $this->getFactory()->createGuiTableFactory();
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addGuiTableHttpDataRequestExecutorService(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR, function (): GuiTableDataRequestExecutorInterface {
            return $this->getFactory()->createGuiTableDataRequestExecutor();
        });

        return $container;
    }
}
