<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Log\Config;

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
