<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Log\Config;

interface LoggerConfigInterface
{

    /**
     * @return string
     */
    public function getChannelName();

    /**
     * @return \Monolog\Handler\HandlerInterface[]
     */
    public function getHandlers();

    /**
     * @return \callable[]
     */
    public function getProcessors();

}
