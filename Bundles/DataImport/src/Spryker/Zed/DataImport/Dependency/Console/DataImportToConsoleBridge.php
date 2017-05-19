<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Dependency\Console;

class DataImportToConsoleBridge implements DataImportToConsoleInterface
{

    /**
     * @var \Symfony\Component\Console\Logger\ConsoleLogger
     */
    protected $consoleLogger;

    /**
     * @param \Symfony\Component\Console\Logger\ConsoleLogger $consoleLogger
     */
    public function __construct($consoleLogger)
    {
        $this->consoleLogger = $consoleLogger;
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function notice($message)
    {
        $this->consoleLogger->notice($message);
    }

}
