<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantOms\Communication\Console;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantOmsTriggerResponseTransfer;
use Spryker\Zed\MerchantOms\Business\MerchantOmsFacade;
use Spryker\Zed\MerchantOms\Communication\Console\TriggerEventFromCsvFileConsole;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantOms
 * @group Communication
 * @group Console
 * @group TriggerEventFromFileConsoleTest
 * Add your own group annotations below this line
 */
class TriggerEventFromFileConsoleTest extends Unit
{
    protected const TEST_STATE_MACHINE = 'Test01';
    protected const TEST_MERCHANT_ORDER_ITEM_REFERENCE = 'TestMerchantOrderItemReference';

    protected const CODE_SUCCESS = 0;
    protected const CODE_ERROR = 1;

    protected const ARGUMENT_FILE_PATH = 'file-path';

    /**
     * @var \SprykerTest\Zed\MerchantOms\MerchantOmsCommunicationTester
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
        $triggerEventFromFileConsole = (new TriggerEventFromCsvFileConsole())->setFacade($this->getMerchantOmsFacadeMock());
        $input = new ArrayInput([static::ARGUMENT_FILE_PATH => codecept_data_dir() . 'import/' . $importFileName]);
        $output = new BufferedOutput();

        // Act
        $outputCode = $triggerEventFromFileConsole->run($input, $output);

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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MerchantOms\Business\MerchantOmsFacade
     */
    protected function getMerchantOmsFacadeMock(): MerchantOmsFacade
    {
        $merchantOmsFacade = $this->createMock(MerchantOmsFacade::class);
        $merchantOmsFacade->method('triggerEventForMerchantOrderItem')->willReturn(
            (new MerchantOmsTriggerResponseTransfer())->setIsSuccessful(true)
        );

        return $merchantOmsFacade;
    }
}
