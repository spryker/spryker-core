<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\PaymentDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\PaymentDataImport\Communication\Plugin\PaymentMethodStoreDataImportPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PaymentDataImport
 * @group Communication
 * @group Plugin
 * @group PaymentMethodStoreDataImportPluginTest
 * Add your own group annotations below this line
 */
class PaymentMethodStoreDataImportPluginTest extends Unit
{
    /**
     * @var int
     */
    protected const EXPECTED_IMPORT_COUNT = 2;

    /**
     * @var \SprykerTest\Zed\PaymentDataImport\PaymentDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsPaymentMethodStores(): void
    {
        //Arrange
        $this->tester->ensurePaymentMethodStoreTableIsEmpty();
        $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $this->tester->haveStore([
            StoreTransfer::NAME => 'AT',
        ]);
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => 'method-1',
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
        ]);
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/payment_method_store.csv');
        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);
        $paymentMethodStoreDataImportPlugin = new PaymentMethodStoreDataImportPlugin();
        //Act
        $dataImporterReportTransfer = $paymentMethodStoreDataImportPlugin->import($dataImportConfigurationTransfer);
        //Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess(), 'Data import should finish successfully');
        $this->assertSame(
            static::EXPECTED_IMPORT_COUNT,
            $dataImporterReportTransfer->getImportedDataSetCount(),
            sprintf(
                'Imported number of payment method stores is %s expected %s.',
                $dataImporterReportTransfer->getImportedDataSetCount(),
                static::EXPECTED_IMPORT_COUNT
            )
        );
    }
}
