<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable\Communication\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\GuiTable\GuiTableConfig getConfig()
 * @method \Spryker\Zed\GuiTable\Communication\GuiTableCommunicationFactory getFactory()
 */
class GuiTableConfigurationBuilderApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    public const SERVICE_GUI_TABLE_CONFIGURATION_BUILDER = 'gui_table_configuration_builder';

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
        $container = $this->addGuiTableConfigurationBuilderService($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addGuiTableConfigurationBuilderService(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_GUI_TABLE_CONFIGURATION_BUILDER, function (): GuiTableConfigurationBuilderInterface {
            return $this->getFactory()->createGuiTableConfigurationBuilder();
        });

        return $container;
    }
}
