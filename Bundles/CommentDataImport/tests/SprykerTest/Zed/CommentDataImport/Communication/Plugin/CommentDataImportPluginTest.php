<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CommentDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CommentDataImport\CommentDataImportConfig;
use Spryker\Zed\CommentDataImport\Communication\Plugin\CommentDataImportPlugin;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CommentDataImport
 * @group Communication
 * @group Plugin
 * @group CommentDataImportPluginTest
 * Add your own group annotations below this line
 * @group Comment
 */
class CommentDataImportPluginTest extends Unit
{
    protected const TEST_COMMENT_CUSTOMER_REFERENCE = 'test-comment-customer-reference';
    protected const INCORRECT_COMMENT_CUSTOMER_REFERENCE = 'incorrect-comment-customer-reference';
    protected const TEST_COMMENT_QUOTE_KEY = 'test-comment-owner-key';
    protected const INCORRECT_COMMENT_QUOTE_KEY = 'incorrect-comment-owner-key';

    /**
     * @var \SprykerTest\Zed\CommentDataImport\CommentDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureCommentTablesIsEmpty();
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        // Arrange
        $this->tester->ensureCustomerWithReferenceDoesNotExist(static::TEST_COMMENT_CUSTOMER_REFERENCE);
        $customerTransfer = $this->tester->createCustomer([
            CustomerTransfer::CUSTOMER_REFERENCE => static::TEST_COMMENT_CUSTOMER_REFERENCE,
        ])->getCustomerTransfer();

        $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
            QuoteTransfer::KEY => static::TEST_COMMENT_QUOTE_KEY,
        ]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/comment.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $commentDataImportPlugin = new CommentDataImportPlugin();
        $dataImporterReportTransfer = $commentDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->tester->assertCommentDatabaseTablesContainsData();
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenCustomerNotFound(): void
    {
        // Arrange
        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/comment_customer_not_found.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage(sprintf('Could not find customer by reference "%s"', static::INCORRECT_COMMENT_CUSTOMER_REFERENCE));

        // Act
        $commentDataImportPlugin = new CommentDataImportPlugin();
        $commentDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenQuoteKeyNotFound(): void
    {
        // Arrange
        $this->tester->ensureCustomerWithReferenceDoesNotExist(static::TEST_COMMENT_CUSTOMER_REFERENCE);
        $this->tester->createCustomer([
            CustomerTransfer::CUSTOMER_REFERENCE => static::TEST_COMMENT_CUSTOMER_REFERENCE,
        ])->getCustomerTransfer();

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/comment_quote_not_found.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage(sprintf('Could not find quote by key "%s"', static::INCORRECT_COMMENT_QUOTE_KEY));

        // Act
        $commentDataImportPlugin = new CommentDataImportPlugin();
        $commentDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenWrongOwnerType(): void
    {
        // Arrange
        $this->tester->ensureCustomerWithReferenceDoesNotExist(static::TEST_COMMENT_CUSTOMER_REFERENCE);
        $customerTransfer = $this->tester->createCustomer([
            CustomerTransfer::CUSTOMER_REFERENCE => static::TEST_COMMENT_CUSTOMER_REFERENCE,
        ])->getCustomerTransfer();

        $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
            QuoteTransfer::KEY => static::TEST_COMMENT_QUOTE_KEY,
        ]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/comment_wrong_owner_type.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage(sprintf('Could not find owner id by owner key "%s"', static::TEST_COMMENT_QUOTE_KEY));

        // Act
        $commentDataImportPlugin = new CommentDataImportPlugin();
        $commentDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenEmptyCommentMessage(): void
    {
        // Arrange
        $this->tester->ensureCustomerWithReferenceDoesNotExist(static::TEST_COMMENT_CUSTOMER_REFERENCE);
        $customerTransfer = $this->tester->createCustomer([
            CustomerTransfer::CUSTOMER_REFERENCE => static::TEST_COMMENT_CUSTOMER_REFERENCE,
        ])->getCustomerTransfer();

        $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
            QuoteTransfer::KEY => static::TEST_COMMENT_QUOTE_KEY,
        ]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/comment_empty_message.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer)
            ->setThrowException(true);

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage(sprintf('The comment message should not be empty.'));

        // Act
        $commentDataImportPlugin = new CommentDataImportPlugin();
        $commentDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Act
        $commentDataImportPlugin = new CommentDataImportPlugin();

        // Assert
        $this->assertSame(CommentDataImportConfig::IMPORT_TYPE_COMMENT, $commentDataImportPlugin->getImportType());
    }
}
