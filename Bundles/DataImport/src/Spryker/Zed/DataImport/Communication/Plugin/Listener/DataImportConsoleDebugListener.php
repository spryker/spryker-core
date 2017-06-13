<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Communication\Plugin\Listener;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventListenerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\DataImport\Business\DataImportFacade getFacade()
 * @method \Spryker\Zed\DataImport\Communication\DataImportCommunicationFactory getFactory()
 */
class DataImportConsoleDebugListener extends AbstractPlugin implements EventListenerInterface
{

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Console\DataImportToConsoleLoggerInterface
     */
    protected $consoleMessenger;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $eventTransfer
     *
     * @return void
     */
    public function handle(TransferInterface $eventTransfer)
    {
        $this->printToConsole($eventTransfer);
    }

    /**
     * @return \Spryker\Zed\DataImport\Dependency\Console\DataImportToConsoleLoggerInterface
     */
    protected function getConsoleMessenger()
    {
        if (!$this->consoleMessenger) {
            $this->consoleMessenger = $this->getFactory()->getConsoleMessenger();
        }

        return $this->consoleMessenger;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $eventTransfer
     *
     * @return void
     */
    protected function printToConsole(TransferInterface $eventTransfer)
    {
        $logString = '<fg=yellow>' . get_class($eventTransfer) . '</>' . PHP_EOL;
        foreach ($eventTransfer->modifiedToArray() as $key => $value) {
            if (is_bool($value)) {
                $value = ($value) ? 'true' : '<fg=red>false</>';
            }
            $logString .= '<fg=white>' . $key . ':</> ' . '<fg=green>' . $value . '</> ';
        }
        $logString .= PHP_EOL;

        $this->getConsoleMessenger()->notice($logString);
    }

}
