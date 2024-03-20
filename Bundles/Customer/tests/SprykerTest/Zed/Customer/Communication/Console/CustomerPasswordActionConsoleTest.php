<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Communication\Console;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Customer\Communication\Console\CustomerPasswordResetConsole;
use Spryker\Zed\Customer\Communication\Console\CustomerPasswordSetConsole;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Communication
 * @group Console
 * @group CustomerPasswordActionConsoleTest
 * Add your own group annotations below this line
 */
class CustomerPasswordActionConsoleTest extends Unit
{
    /**
     * @var string
     */
    protected const OPTION_NO_TOKEN = '--no-token';

    /**
     * @var string
     */
    protected const OPTION_STORE = '--store';

    /**
     * @var string
     */
    protected const NAME_STORE = 'DE';

    /**
     * @var string
     */
    protected const FLAG_FORCE = '--force';

    /**
     * @var \SprykerTest\Zed\Customer\CustomerCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @dataProvider getCustomerPasswordConsoleCommands
     *
     * @param string $consoleCommand
     *
     * @return void
     */
    public function testExecuteSetUserStoreForDynamicMultiStore(string $consoleCommand): void
    {
        if ($this->tester->isDynamicStoreEnabled() === false) {
            $this->markTestSkipped('This test requires DynamicStore to be enabled.');
        }
        // Arrange
        $inputMock = new ArgvInput([static::OPTION_NO_TOKEN . '=' . true, static::OPTION_STORE . '=' . static::NAME_STORE, static::FLAG_FORCE]);
        $output = $this->createMock(OutputInterface::class);

        $facadeMock = $this->tester->createCustomerFacadeMock();
        $facadeMock->method('getCustomerCollectionByCriteria')->willReturn(
            (new CustomerCollectionTransfer())->addCustomer(new CustomerTransfer()),
        );
        $facadeMock->method('sendPasswordRestoreMailForCustomerCollection')->with(
            (new CustomerCollectionTransfer())
                ->addCustomer(
                    (new CustomerTransfer())->setStoreName(static::NAME_STORE),
                ),
            $output,
        );
        $factoryMock = $this->tester->createCustomerCommunicationFactoryMock();
        $storeFacadeMock = $this->tester->createStoreFacadeMock();

        $storeFacadeMock->method('isDynamicStoreEnabled')->willReturn(true);
        $factoryMock->method('getStoreFacade')->willReturn($storeFacadeMock);

        $consoleComand = new $consoleCommand();
        $consoleComand->setFactory($factoryMock);
        $consoleComand->setFacade($facadeMock);

        // Act
        $result = $consoleComand->run($inputMock, $output);

        // Assert
        $this->assertEquals($consoleCommand::CODE_SUCCESS, $result);
    }

    /**
     * @dataProvider getCustomerPasswordConsoleCommands
     *
     * @param string $consoleCommand
     *
     * @return void
     */
    public function testExecuteReturnsErrorCodeIfStoreOptionIsEmpty(string $consoleCommand): void
    {
        if ($this->tester->isDynamicStoreEnabled() === false) {
            $this->markTestSkipped('This test requires DynamicStore to be enabled.');
        }

        // Arrange
        $inputMock = new ArgvInput([static::OPTION_NO_TOKEN, static::FLAG_FORCE]);
        $output = $this->createMock(OutputInterface::class);

        $facadeMock = $this->tester->createCustomerFacadeMock();
        $facadeMock->method('getCustomerCollectionByCriteria')->willReturn(
            (new CustomerCollectionTransfer())->addCustomer(new CustomerTransfer()),
        );
        $factoryMock = $this->tester->createCustomerCommunicationFactoryMock();
        $storeFacadeMock = $this->tester->createStoreFacadeMock();

        $storeFacadeMock->method('isDynamicStoreEnabled')->willReturn(true);
        $factoryMock->method('getStoreFacade')->willReturn($storeFacadeMock);

        $consoleComand = new $consoleCommand();
        $consoleComand->setFactory($factoryMock);
        $consoleComand->setFacade($facadeMock);

        // Act
        $result = $consoleComand->run($inputMock, $output);

        // Assert
        $this->assertEquals(defined('APPLICATION_STORE') ? $consoleCommand::CODE_SUCCESS : $consoleCommand::CODE_ERROR, $result);
    }

    /**
     * @return array<string>
     */
    public function getCustomerPasswordConsoleCommands(): array
    {
        return [
            'CustomerPasswordResetConsole' => [CustomerPasswordResetConsole::class],
            'CustomerPasswordSetConsole' => [CustomerPasswordSetConsole::class],
        ];
    }
}
