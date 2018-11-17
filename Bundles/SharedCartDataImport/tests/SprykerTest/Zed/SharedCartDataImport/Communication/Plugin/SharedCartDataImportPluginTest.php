<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\SharedCartDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\Permission\PermissionDependencyProvider;
use Spryker\Zed\Quote\QuoteDependencyProvider;
use Spryker\Zed\SharedCart\Communication\Plugin\ReadSharedCartPermissionPlugin;
use Spryker\Zed\SharedCart\Communication\Plugin\RemoveSharedQuoteBeforeQuoteDeletePlugin;
use Spryker\Zed\SharedCartDataImport\Communication\Plugin\SharedCartDataImportPlugin;
use Spryker\Zed\SharedCartDataImport\SharedCartDataImportConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group SharedCartDataImport
 * @group Communication
 * @group Plugin
 * @group SharedCartDataImportPluginTest
 * Add your own group annotations below this line
 * @group Quote
 * @group SharedCart
 */
class SharedCartDataImportPluginTest extends Unit
{
    protected const TEST_QUOTE_KEY = 'test-shared-cart';
    protected const TEST_COMPANY_USER_KEY = 'test-company-user-key';

    /**
     * @var \SprykerTest\Zed\SharedCartDataImport\SharedCartDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected $companyUser;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION, [
            new ReadSharedCartPermissionPlugin(),
        ]);

        $this->tester->setDependency(
            QuoteDependencyProvider::PLUGINS_QUOTE_DELETE_BEFORE,
            [
            new RemoveSharedQuoteBeforeQuoteDeletePlugin(),
            ]
        );

        $this->tester->getLocator()->permission()->facade()->syncPermissionPlugins();

        $this->tester->haveQuotePermissionGroup('READ_ONLY', [
            ReadSharedCartPermissionPlugin::KEY,
        ]);

        $companyTransfer = $this->tester->haveCompany();

        $ownerCustomerTransfer = $this->tester->haveCustomer();
        $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $ownerCustomerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $otherCustomerTransfer = $this->tester->haveCustomer();
        $this->companyUser = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $otherCustomerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::KEY => static::TEST_COMPANY_USER_KEY,
        ]);

        $this->quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $ownerCustomerTransfer,
            QuoteTransfer::KEY => static::TEST_QUOTE_KEY,
        ]);
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::setUp();

        $this->tester->deleteQuote($this->quoteTransfer);
        $this->tester->deleteCompanyUser($this->companyUser);
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();

        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/shared_cart.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $sharedCartDataImportPlugin = new SharedCartDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $sharedCartDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTablesContainsData();
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenCompanyUserNotFound(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();

        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/shared_cart_company_user_not_found.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $sharedCartDataImportPlugin = new SharedCartDataImportPlugin();

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find company user by key "INCORRECT_COMPANY_USER_KEY"');

        // Act
        $sharedCartDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenQuoteNotFound(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();

        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/shared_cart_quote_not_found.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $sharedCartDataImportPlugin = new SharedCartDataImportPlugin();

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find quote by key "INCORRECT_QUOTE_KEY"');

        // Act
        $sharedCartDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenPermissionGroupNotFound(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty();

        // Arrange
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/shared_cart_permission_group_not_found.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        $sharedCartDataImportPlugin = new SharedCartDataImportPlugin();

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find quote permission group by name "INCORRECT_PERMISSION_GROUP_NAME"');

        // Act
        $sharedCartDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Act
        $sharedCartDataImportPlugin = new SharedCartDataImportPlugin();

        // Assert
        $this->assertSame(SharedCartDataImportConfig::IMPORT_TYPE_SHARED_CART, $sharedCartDataImportPlugin->getImportType());
    }
}
