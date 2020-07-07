<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOms\Communication\Console;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SalesOrderItemTransfer;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Oms\Business\OmsFacadeInterface;
use Spryker\Zed\SalesOms\Business\SalesOmsFacade;
use Spryker\Zed\SalesOms\Communication\Console\ImportOrderItemsStatusConsole;
use Spryker\Zed\SalesOms\Dependency\Facade\SalesOmsToOmsFacadeBridge;
use Spryker\Zed\SalesOms\SalesOmsDependencyProvider;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOms
 * @group Communication
 * @group Console
 * @group ImportOrderItemsStatusConsoleTest
 * Add your own group annotations below this line
 */
class ImportOrderItemsStatusConsoleTest extends Unit
{
    protected const CODE_SUCCESS = 0;
    protected const CODE_ERROR = 1;

    protected const ARGUMENT_FILE_PATH = 'file-path';

    /**
     * @var \SprykerTest\Zed\SalesOms\SalesOmsCommunicationTester
     */
    protected $tester;

    /**
     * @dataProvider filenameDataProvider
     *
     * @param string $importFileName
     * @param int $resultCode
     *
     * @return void
     */
    public function testTriggerEventFromFileConsoleReturnsSuccessWithValidImport(string $importFileName, int $resultCode): void
    {
        // Arrange
        $this->setOmsFacadeDependency();
        $importOrderItemsStatusConsole = (new ImportOrderItemsStatusConsole())->setFacade($this->getSalesOmsFacadeMock());
        $input = new ArrayInput([static::ARGUMENT_FILE_PATH => codecept_data_dir() . 'import/' . $importFileName]);
        $output = new BufferedOutput();

        // Act
        $outputCode = $importOrderItemsStatusConsole->run($input, $output);

        // Assert
        $this->assertSame($outputCode, $resultCode);
    }

    /**
     * @return array
     */
    public function filenameDataProvider(): array
    {
        return [
            'Valid import file' => ['valid_import.csv', static::CODE_SUCCESS],
            'Valid empty import file' => ['valid_empty_import.csv', static::CODE_SUCCESS],
            'Invalid non existing file' => ['invalid_not_existing_import.csv', static::CODE_ERROR],
            'Invalid without headers' => ['invalid_without_headers_import.csv', static::CODE_ERROR],
            'Invalid missing column' => ['invalid_missing_column_import.csv', static::CODE_ERROR],
        ];
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesOms\Business\SalesOmsFacade
     */
    protected function getSalesOmsFacadeMock(): SalesOmsFacade
    {
        $salesOmsFacadeMock = $this->createMock(SalesOmsFacade::class);

        $salesOmsFacadeMock->method('findSalesOrderItemByOrderItemReference')->willReturn(
            (new SalesOrderItemTransfer())
        );

        return $salesOmsFacadeMock;
    }

    /**
     * @return void
     */
    protected function setOmsFacadeDependency(): void
    {
        $this->tester->setDependency(SalesOmsDependencyProvider::FACADE_OMS, function (Container $container) {
            $omsFacadeMock = $this->createMock(OmsFacadeInterface::class);

            $omsFacadeMock->method('triggerEventForOneOrderItem')->willReturn([]);

            return new SalesOmsToOmsFacadeBridge($omsFacadeMock);
        });
    }
}
