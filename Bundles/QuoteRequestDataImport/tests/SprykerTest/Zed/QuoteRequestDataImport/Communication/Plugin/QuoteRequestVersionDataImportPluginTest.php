<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\QuoteRequestDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\QuoteRequestDataImport\Communication\Plugin\QuoteRequestVersionDataImportPlugin;
use Spryker\Zed\QuoteRequestDataImport\QuoteRequestDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group QuoteRequestDataImport
 * @group Communication
 * @group Plugin
 * @group QuoteRequestVersionDataImportPluginTest
 * Add your own group annotations below this line
 * @group QuoteRequest
 */
class QuoteRequestVersionDataImportPluginTest extends Unit
{
    protected const TEST_QUOTE_REQUEST_REFERENCE = 'test-quote-request-reference';
    protected const INCORRECT_QUOTE_REQUEST_REFERENCE = 'incorrect-quote-request-reference';

    /**
     * @var \SprykerTest\Zed\QuoteRequestDataImport\QuoteRequestDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureQuoteRequestTablesIsEmpty();
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $customerTransfer = $this->tester->haveCustomer();

        $quoteTransfer = (new QuoteBuilder())
            ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference()])
            ->withItem([ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(), ItemTransfer::UNIT_PRICE => 1, ItemTransfer::QUANTITY => 1])
            ->build();

        $this->tester->haveQuoteRequest([
            QuoteRequestTransfer::QUOTE_REQUEST_REFERENCE => static::TEST_QUOTE_REQUEST_REFERENCE,
            QuoteRequestTransfer::LATEST_VERSION => $this->tester->createQuoteRequestVersion($quoteTransfer),
            QuoteRequestTransfer::COMPANY_USER => $this->tester->createCompanyUser($customerTransfer),
        ]);

        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/quote_request_version.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $quoteRequestVersionDataImportPlugin = new QuoteRequestVersionDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $quoteRequestVersionDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->tester->assertQuoteRequestVersionDatabaseTablesContainsData();
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenQuoteRequestNotFoundByReference(): void
    {
        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/quote_request_version_quote_request_not_found.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $quoteRequestVersionDataImportPlugin = new QuoteRequestVersionDataImportPlugin();

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage(sprintf('Could not find quote request by reference "%s"', static::INCORRECT_QUOTE_REQUEST_REFERENCE));

        // Act
        $quoteRequestVersionDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Act
        $quoteRequestVersionDataImportPlugin = new QuoteRequestVersionDataImportPlugin();

        // Assert
        $this->assertSame(QuoteRequestDataImportConfig::IMPORT_TYPE_QUOTE_REQUEST_VERSION, $quoteRequestVersionDataImportPlugin->getImportType());
    }
}
