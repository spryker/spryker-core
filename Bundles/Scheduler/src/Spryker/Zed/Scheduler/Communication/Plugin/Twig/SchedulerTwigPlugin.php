<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Communication\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \Spryker\Zed\Scheduler\SchedulerConfig getConfig()
 * @method \Spryker\Zed\Scheduler\Business\SchedulerFacadeInterface getFacade()
 * @method \Spryker\Zed\Scheduler\Communication\SchedulerCommunicationFactory getFactory()
 */
class SchedulerTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    public const FUNCTION_NAME_GET_ENV = 'getenv';

    /**
     * {@inheritdoc}
     * - Extends twig with "getenv" function to get the value of an environment variable.
     *
     * @api
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $twig->addFunction($this->getEnvironmentVariableValueByNameFunction());

        return $twig;
    }

    /**
     * @return \Twig\TwigFunction
     */
    protected function getEnvironmentVariableValueByNameFunction(): TwigFunction
    {
        return new TwigFunction(static::FUNCTION_NAME_GET_ENV, function (string $which) {
            return $this->getEnvironmentVariableValueByName($which);
        });
    }

    /**
     * @param string $which
     *
     * @return string|false
     */
    protected function getEnvironmentVariableValueByName(string $which)
    {
        return getenv($which);
    }
}
