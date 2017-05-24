<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Communication\Plugin\Listener;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AfterDataSetImporterEventTransfer;
use Generated\Shared\Transfer\AfterDataSetImportEventTransfer;
use Generated\Shared\Transfer\AfterImportEventTransfer;
use Generated\Shared\Transfer\BeforeDataSetImporterEventTransfer;
use Generated\Shared\Transfer\BeforeDataSetImportEventTransfer;
use Generated\Shared\Transfer\BeforeImportEventTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\DataImport\Communication\Plugin\Listener\DataImportConsoleTimerListener;
use Spryker\Zed\DataImport\Dependency\Console\DataImportToConsoleInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Communication
 * @group Plugin
 * @group Listener
 * @group ConsoleTimerListenerTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Zed\DataImport\CommunicationTester $tester
 */
class ConsoleTimerListenerTest extends Test
{

    const IMPORT_TYPE = 'import-type';

    /**
     * @dataProvider eventTransferProvider
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $eventTransfer
     * @param bool $isCalled
     *
     * @return void
     */
    public function testHandleOutputsTransferObjectKeyValues(TransferInterface $eventTransfer, $isCalled = false)
    {
        $dataImportConsoleTimerListener = new DataImportConsoleTimerListener();
        $dataImportConsoleTimerListener->handle($eventTransfer);
    }

    /**
     * @return array
     */
    public function eventTransferProvider()
    {
        return [
            [(new BeforeImportEventTransfer())->setImportType(static::IMPORT_TYPE)],
            [(new BeforeDataSetImportEventTransfer())->setImportType(static::IMPORT_TYPE)],
            [(new BeforeDataSetImporterEventTransfer())->setImportType(static::IMPORT_TYPE)],
            [(new AfterDataSetImporterEventTransfer())->setImportType(static::IMPORT_TYPE)->setIsSuccess(true), true],
            [(new AfterDataSetImportEventTransfer())->setImportType(static::IMPORT_TYPE), true],
            [(new AfterImportEventTransfer())->setImportType(static::IMPORT_TYPE), true],
        ];
    }

    /**
     * @param bool $isCalled
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\DataImport\Dependency\Console\DataImportToConsoleInterface
     */
    protected function getConsoleInterfaceMock($isCalled)
    {
        $mockBuilder = $this->getMockBuilder(DataImportToConsoleInterface::class)
            ->setMethods(['notice']);

        $consoleInterfaceMock = $mockBuilder->getMock();
        $consoleInterfaceMock->expects(($isCalled) ? $this->atLeastOnce() : $this->never())->method('notice');

        return $consoleInterfaceMock;
    }

}
