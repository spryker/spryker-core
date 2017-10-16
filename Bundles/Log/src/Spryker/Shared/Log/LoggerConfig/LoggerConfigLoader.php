<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log\LoggerConfig;

use Spryker\Shared\Log\Exception\LoggerLoaderException;

class LoggerConfigLoader
{
    /**
     * @var \Spryker\Shared\Log\LoggerConfig\LoggerConfigLoaderInterface[]
     */
    protected $loggerLoader;

    /**
     * @param \Spryker\Shared\Log\LoggerConfig\LoggerConfigLoaderInterface[] $loggerLoader
     */
    public function __construct(array $loggerLoader)
    {
        $this->loggerLoader = $loggerLoader;
    }

    /**
     * @throws \Spryker\Shared\Log\Exception\LoggerLoaderException
     *
     * @return \Spryker\Shared\Log\Config\LoggerConfigInterface
     */
    public function getLoggerConfig()
    {
        foreach ($this->loggerLoader as $loggerLoader) {
            if ($loggerLoader->accept()) {
                return $loggerLoader->create();
            }
        }

        throw new LoggerLoaderException('Could not load a Logger class.');
    }
}
