<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Console\Business;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface ConsoleFacadeInterface
{
    /**
     * Specification
     * - Returns an array of console commands
     *
     * @api
     *
     * @return \Symfony\Component\Console\Command\Command[]
     */
    public function getConsoleCommands();

    /**
     * Specification
     * - Returns an array of event subscribers.
     *
     * @api
     *
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface[]
     */
    public function getEventSubscriber();

    /**
     * Specification:
     * - Returns an array of ApplicationPlugins.
     *
     * @api
     *
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[]
     */
    public function getApplicationPlugins(): array;

    /**
     * Specification:
     * - Returns an array of ServiceProviders.
     *
     * @api
     *
     * @deprecated Use `\Spryker\Zed\Console\Business\ConsoleFacadeInterface::getApplicationPlugins()` instead.
     *
     * @return \Silex\ServiceProviderInterface[]
     */
    public function getServiceProviders();

    /**
     * Specification
     * - Executes pre-run plugins which provided in ConsoleDependencyProvider.
     *
     * @api
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function preRun(InputInterface $input, OutputInterface $output);

    /**
     * Specification
     * - Executes pos-run plugins which provided in ConsoleDependencyProvider.
     *
     * @api
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function postRun(InputInterface $input, OutputInterface $output);
}
