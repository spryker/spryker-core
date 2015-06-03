<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Oms;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandInterface;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;
use SprykerFeature\Zed\Oms\Persistence\OmsQueryContainer;

class OmsDependencyProvider extends AbstractBundleDependencyProvider
{

    const CONDITION_PLUGINS = 'CONDITION_PLUGINS';

    const COMMAND_PLUGINS = 'COMMAND_PLUGINS';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {

        $container[OmsDependencyProvider::CONDITION_PLUGINS] = $this->getConditionPlugins($container);

        $container[OmsDependencyProvider::COMMAND_PLUGINS] = $this->getCommandPlugins($container);

        return $container;
    }


    /**
     * Overwrite in project
     * @param Container $container
     *
     * @return ConditionInterface[]
     */
    protected function getConditionPlugins(Container $container)
    {
        return [];
    }

    /**
     * Overwrite in project
     * @param Container $container
     *
     * @return CommandInterface[]
     */
    protected function getCommandPlugins(Container $container)
    {
        return [
            // $container->getLocator()->oms()->pluginOmsConditionAlwaysFalse()
        ];
    }
}
