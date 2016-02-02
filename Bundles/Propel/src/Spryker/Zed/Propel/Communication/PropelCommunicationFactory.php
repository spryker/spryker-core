<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Propel\Communication;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Propel\PropelConfig;

/**
 * @method PropelConfig getConfig()
 */
class PropelCommunicationFactory extends AbstractCommunicationFactory
{

    const LOGGER_NAME = 'defaultLogger';

    /**
     * @return \Monolog\Logger[]
     */
    public function createLogger()
    {
        $defaultLogger = new Logger(self::LOGGER_NAME);
        $defaultLogger->pushHandler(
            $this->createStreamHandler()
        );

        return [$defaultLogger];
    }

    /**
     * @return \Monolog\Handler\StreamHandler
     */
    protected function createStreamHandler()
    {
        return new StreamHandler(
            $this->getConfig()->getLogPath()
        );
    }

}
