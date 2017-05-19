<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Communication\Plugin\Listener;

use Generated\Shared\Transfer\AfterDataSetImporterEventTransfer;
use Generated\Shared\Transfer\AfterDataSetImportEventTransfer;
use Generated\Shared\Transfer\AfterImportEventTransfer;
use Generated\Shared\Transfer\BeforeDataSetImporterEventTransfer;
use Generated\Shared\Transfer\BeforeDataSetImportEventTransfer;
use Generated\Shared\Transfer\BeforeImportEventTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventListenerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\DataImport\Business\DataImportFacade getFacade()
 * @method \Spryker\Zed\DataImport\Communication\DataImportCommunicationFactory getFactory()
 */
class DataImportConsoleTimerListener extends AbstractPlugin implements EventListenerInterface
{

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Timer\DataImportToTimerInterface
     */
    protected $timer;

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Console\DataImportToConsoleInterface
     */
    protected $consoleMessenger;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $eventTransfer
     *
     * @return void
     */
    public function handle(TransferInterface $eventTransfer)
    {
        $this->handleImportEvents($eventTransfer);
        $this->handleDataSetImportEvents($eventTransfer);
        $this->handleDataSetImporterEvents($eventTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $eventTransfer
     *
     * @return void
     */
    protected function handleImportEvents(TransferInterface $eventTransfer)
    {
        if ($eventTransfer instanceof BeforeImportEventTransfer) {
            $this->getTimer()->start();
        }
        if ($eventTransfer instanceof AfterImportEventTransfer) {
            $seconds = $this->getTimer()->stop();
            $this->printToConsole('Import', $eventTransfer->getImportType(), $this->getTimer()->secondsToTimeString($seconds));
        }
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $eventTransfer
     *
     * @return void
     */
    protected function handleDataSetImportEvents(TransferInterface $eventTransfer)
    {
        if ($eventTransfer instanceof BeforeDataSetImportEventTransfer) {
            $this->getTimer()->start();
        }
        if ($eventTransfer instanceof AfterDataSetImportEventTransfer) {
            $seconds = $this->getTimer()->stop();
            $this->printToConsole('DataSetImport', $eventTransfer->getImportType(), $this->getTimer()->secondsToTimeString($seconds));
        }
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $eventTransfer
     *
     * @return void
     */
    protected function handleDataSetImporterEvents(TransferInterface $eventTransfer)
    {
        if ($eventTransfer instanceof BeforeDataSetImporterEventTransfer) {
            $this->getTimer()->start();
        }
        if ($eventTransfer instanceof AfterDataSetImporterEventTransfer) {
            $seconds = $this->getTimer()->stop();
            $this->printToConsole('DataSetImporter', $eventTransfer->getImportType(), $this->getTimer()->secondsToTimeString($seconds));
        }
    }

    /**
     * @return \Spryker\Zed\DataImport\Dependency\Timer\DataImportToTimerInterface
     */
    protected function getTimer()
    {
        if (!$this->timer) {
            $this->timer = $this->getFactory()->getTimer();
        }

        return $this->timer;
    }

    /**
     * @return \Spryker\Zed\DataImport\Dependency\Console\DataImportToConsoleInterface
     */
    protected function getConsoleMessenger()
    {
        if (!$this->consoleMessenger) {
            $this->consoleMessenger = $this->getFactory()->getConsoleMessenger();
        }

        return $this->consoleMessenger;
    }

    /**
     * @param string $name
     * @param string $importerType
     * @param string $timeString
     *
     * @return void
     */
    protected function printToConsole($name, $importerType, $timeString)
    {
        $this->getConsoleMessenger()->notice(sprintf(
            '<fg=white><fg=yellow>%s</> for <fg=green>%s</> runs in: <fg=green>%s</></>',
            $name,
            $importerType,
            $timeString
        ));
    }

}
