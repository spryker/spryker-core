<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Propel\PropelDependencyProvider;

/**
 * @method \Spryker\Zed\Propel\PropelConfig getConfig()
 */
class PropelCommunicationFactory extends AbstractCommunicationFactory
{
    public const LOGGER_NAME = 'defaultLogger';

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

    /**
     * @return \Spryker\Zed\Propel\Dependency\Facade\PropelToLogInterface
     */
    public function getLogFacade()
    {
        return $this->getProvidedDependency(PropelDependencyProvider::FACADE_LOG);
    }
}
