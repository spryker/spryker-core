<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable\Communication\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\GuiTable\Http\GuiTableDataRequestHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\GuiTable\GuiTableConfig getConfig()
 * @method \Spryker\Zed\GuiTable\Communication\GuiTableCommunicationFactory getFactory()
 */
class GuiTableHttpDataRequestHandlerApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    protected const SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_HANDLER = 'gui_table_http_data_request_handler';

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
        $container = $this->addGuiTableHttpDataRequestHandlerService($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addGuiTableHttpDataRequestHandlerService(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_HANDLER, function (): GuiTableDataRequestHandlerInterface {
            return $this->getFactory()->createGuiTableDataRequestHandler();
        });

        return $container;
    }
}
