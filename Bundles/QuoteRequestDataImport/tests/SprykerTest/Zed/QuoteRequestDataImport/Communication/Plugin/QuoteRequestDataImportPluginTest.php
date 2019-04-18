<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\QuoteRequestDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\QuoteRequestDataImport\Communication\Plugin\QuoteRequestDataImportPlugin;
use Spryker\Zed\QuoteRequestDataImport\QuoteRequestDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group QuoteRequestDataImport
 * @group Communication
 * @group Plugin
 * @group QuoteRequestDataImportPluginTest
 * Add your own group annotations below this line
 * @group QuoteRequest
 */
class QuoteRequestDataImportPluginTest extends Unit
{
    protected const TEST_COMPANY_USER_KEY = 'test-company-user-key';
    protected const INCORRECT_COMPANY_USER_KEY = 'incorrect-company-user-key';

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
        // Arrange
        $this->tester->createCompanyUser($this->tester->haveCustomer(), static::TEST_COMPANY_USER_KEY);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/quote_request.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $quoteRequestDataImportPlugin = new QuoteRequestDataImportPlugin();
        $dataImporterReportTransfer = $quoteRequestDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->tester->assertQuoteRequestDatabaseTablesContainsData();
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenCompanyUserNotFound(): void
    {
        // Arrange
        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/quote_request_company_user_not_found.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage(sprintf('Could not find company user by key "%s"', static::INCORRECT_COMPANY_USER_KEY));

        // Act
        $quoteRequestDataImportPlugin = new QuoteRequestDataImportPlugin();
        $quoteRequestDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Act
        $quoteRequestDataImportPlugin = new QuoteRequestDataImportPlugin();

        // Assert
        $this->assertSame(QuoteRequestDataImportConfig::IMPORT_TYPE_QUOTE_REQUEST, $quoteRequestDataImportPlugin->getImportType());
    }
}
