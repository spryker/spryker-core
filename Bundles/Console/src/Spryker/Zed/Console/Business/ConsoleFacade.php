<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Console\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Console\Business\ConsoleBusinessFactory getFactory()
 */
class ConsoleFacade extends AbstractFacade implements ConsoleFacadeInterface
{

    /**
     * Specification
     * - Returns an array of console commands
     *
     * @api
     *
     * @return \Symfony\Component\Console\Command\Command[]
     */
    public function getConsoleCommands()
    {
        return $this->getFactory()->getConsoleCommands();
    }

    /**
     * Specification
     * - Returns an array of event subscribers
     *
     * @api
     *
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface[]
     */
    public function getEventSubscriber()
    {
        return $this->getFactory()->getEventSubscriber();
    }

    /**
     * Specification
     * - Returns an array of ServiceProviders
     *
     * @api
     *
     * @return \Silex\ServiceProviderInterface[]
     */
    public function getServiceProvider()
    {
        return $this->getFactory()->getServiceProvider();
    }

}
