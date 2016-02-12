<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Console\Business;

interface ConsoleFacadeInterface
{

    /**
     * @return \Symfony\Component\Console\Command\Command[]
     */
    public function getConsoleCommands();

}
