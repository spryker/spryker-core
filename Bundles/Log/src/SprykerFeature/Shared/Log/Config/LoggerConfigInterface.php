<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Log\Config;

use Monolog\Handler\HandlerInterface;

interface LoggerConfigInterface
{

    /**
     * @return string
     */
    public function getChannelName();

    /**
     * @return HandlerInterface[]
     */
    public function getHandlers();

    /**
     * @return \callable[]
     */
    public function getProcessors();

}
