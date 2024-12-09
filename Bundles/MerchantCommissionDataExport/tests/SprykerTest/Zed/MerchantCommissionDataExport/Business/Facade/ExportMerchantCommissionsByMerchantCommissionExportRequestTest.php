<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCommissionDataExport\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\MerchantCommissionAmountBuilder;
use Generated\Shared\Transfer\MerchantCommissionAmountTransfer;
use Generated\Shared\Transfer\MerchantCommissionExportRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Service\DataExport\Plugin\DataExport\OutputStreamDataExportConnectionPlugin;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\MerchantCommissionDataExport\MerchantCommissionDataExportBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantCommissionDataExport
 * @group Business
 * @group Facade
 * @group ExportMerchantCommissionsByMerchantCommissionExportRequestTest
 * Add your own group annotations below this line
 */
class ExportMerchantCommissionsByMerchantCommissionExportRequestTest extends Unit
{
    /**
     * @uses \Spryker\Service\DataExport\DataExportDependencyProvider::DATA_EXPORT_CONNECTION_PLUGINS
     *
     * @var string
     */
    protected const DATA_EXPORT_CONNECTION_PLUGINS = 'DATA_EXPORT_CONNECTION_PLUGINS';

    /**
     * @var string
     */
    protected const DESTINATION = 'php://output';

    /**
     * @var list<string>
     */
    protected const FIELDS = [
        'key',
        'name',
        'description',
        'valid_from',
        'valid_to',
        'is_active',
        'amount',
        'calculator_type_plugin',
        'group',
        'priority',
        'item_condition',
        'order_condition',
        'stores',
        'merchants_allow_list',
        'fixed_amount_configuration',
    ];

    /**
     * @var string
     */
    protected const FORMATTER_TYPE = 'csv';

    /**
     * @uses \Spryker\Service\DataExport\Plugin\DataExport\OutputStreamDataExportConnectionPlugin::CONNECTION_TYPE_OUTPUT_STREAM
     *
     * @var string
     */
    protected const CONNECTION_TYPE = 'output-stream';

    /**
     * @var \SprykerTest\Zed\MerchantCommissionDataExport\MerchantCommissionDataExportBusinessTester
     */
    protected MerchantCommissionDataExportBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureMerchantCommissionTableIsEmpty();
        $this->tester->setDependency(static::DATA_EXPORT_CONNECTION_PLUGINS, [
            new OutputStreamDataExportConnectionPlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testReturnsResponseTransferWithCorrectData(): void
    {
        // Arrange
        $this->tester->setDependency('PLUGINS_MERCHANT_COMMISSION_CALCULATOR', [
            $this->tester->getMerchantCommissionCalculatorPlugin(100, 'test-calculator-type-fixed'),
        ]);

        $storeTransfer = $this->tester->haveStore();
        $merchantTransfer = $this->tester->haveMerchant();
        $currencyTransfer = $this->tester->haveCurrencyTransfer();
        $merchantCommissionGroupTransfer = $this->tester->haveMerchantCommissionGroup();
        $merchantCommissionAmountTransfer = (new MerchantCommissionAmountBuilder([
            MerchantCommissionAmountTransfer::CURRENCY => $currencyTransfer,
        ]))->build();
        $merchantCommissionTransfer = $this->tester->haveMerchantCommission([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $merchantCommissionGroupTransfer,
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
            MerchantCommissionTransfer::MERCHANTS => [$merchantTransfer->toArray()],
            MerchantCommissionTransfer::MERCHANT_COMMISSION_AMOUNTS => [$merchantCommissionAmountTransfer->toArray()],
        ]);

        $merchantCommissionExportRequestTransfer = $this->createMerchantCommissionExportRequestTransfer();

        // Act
        ob_start();
        $merchantCommissionExportResponseTransfer = $this->tester->getFacade()->exportMerchantCommissionsByMerchantCommissionExportRequest(
            $merchantCommissionExportRequestTransfer,
        );
        $exportedData = ob_get_clean();

        // Assert
        $this->assertCount(0, $merchantCommissionExportResponseTransfer->getErrors());

        $parsedExportedData = $this->tester->parseExportedData($exportedData);
        $this->assertCount(1, $parsedExportedData);
        $this->assertSame($merchantCommissionTransfer->getKeyOrFail(), $parsedExportedData[0]['key']);
    }

    /**
     * @return void
     */
    public function testReturnsResponseTransferWithNotEmptyErrorMessage(): void
    {
        // Arrange
        $merchantCommissionExportRequestTransfer = $this->createMerchantCommissionExportRequestTransfer();
        $merchantCommissionExportRequestTransfer->setConnection('unknown-connection');

        // Act
        $merchantCommissionExportResponseTransfer = $this->tester->getFacade()->exportMerchantCommissionsByMerchantCommissionExportRequest(
            $merchantCommissionExportRequestTransfer,
        );

        // Assert
        $this->assertCount(1, $merchantCommissionExportResponseTransfer->getErrors());
        $this->assertNotNull($merchantCommissionExportResponseTransfer->getErrors()[0]->getMessage());
    }

    /**
     * @dataProvider throwsNullValueExceptionWhenRequiredFieldIsMissingDataProvider
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionExportRequestTransfer $merchantCommissionExportRequestTransfer
     *
     * @return void
     */
    public function testThrowsNullValueExceptionWhenRequiredFieldIsMissing(
        MerchantCommissionExportRequestTransfer $merchantCommissionExportRequestTransfer
    ): void {
        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->exportMerchantCommissionsByMerchantCommissionExportRequest(
            $merchantCommissionExportRequestTransfer,
        );
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\MerchantCommissionExportResponseTransfer>>
     */
    protected function throwsNullValueExceptionWhenRequiredFieldIsMissingDataProvider(): array
    {
        return [
            'Field "format" is missing' => [
                $this->createMerchantCommissionExportRequestTransfer()->setFormat(null),
            ],
            'Field "connection" is missing' => [
                $this->createMerchantCommissionExportRequestTransfer()->setConnection(null),
            ],
            'Field "destination" is missing' => [
                $this->createMerchantCommissionExportRequestTransfer()->setDestination(null),
            ],
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantCommissionExportRequestTransfer
     */
    public function createMerchantCommissionExportRequestTransfer(): MerchantCommissionExportRequestTransfer
    {
        return (new MerchantCommissionExportRequestTransfer())
            ->setFormat(static::FORMATTER_TYPE)
            ->setConnection(static::CONNECTION_TYPE)
            ->setDestination(static::DESTINATION)
            ->setFields(static::FIELDS);
    }
}
