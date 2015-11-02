<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel\Communication;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerEngine\Zed\Propel\PropelConfig;

/**
 * @method PropelConfig getConfig()
 */
class PropelDependencyContainer extends AbstractCommunicationDependencyContainer
{

    const LOGGER_NAME = 'defaultLogger';

    /**
     * @return Logger[]
     */
    public function createLogger()
    {
        $defaultLogger = new Logger(self::LOGGER_NAME);
        $defaultLogger->pushHandler(new StreamHandler(
            $this->getConfig()->getLogPath()
        ));

        return [$defaultLogger];
    }

}
