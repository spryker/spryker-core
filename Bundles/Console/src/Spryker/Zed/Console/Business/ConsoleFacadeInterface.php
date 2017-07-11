<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Console\Business;

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
     * - Returns an array of event subscribers
     *
     * @api
     *
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface[]
     */
    public function getEventSubscriber();

    /**
     * Specification
     * - Returns an array of ServiceProviders
     *
     * @api
     *
     * @return \Silex\ServiceProviderInterface[]
     */
    public function getServiceProviders();

}
