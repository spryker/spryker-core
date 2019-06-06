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
use Spryker\Zed\CommentDataImport\CommentDataImportConfig;
use Spryker\Zed\CommentDataImport\Communication\Plugin\CommentDataImportPlugin;

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
    protected const TEST_CUSTOMER_REFERENCE = 'test-customer-reference';
    protected const INCORRECT_COMPANY_USER_KEY = 'incorrect-company-user-key';

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
        $this->tester->haveCustomer(
            [
                CustomerTransfer::CUSTOMER_REFERENCE => static::TEST_CUSTOMER_REFERENCE,
            ]
        );

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
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Act
        $commentDataImportPlugin = new CommentDataImportPlugin();

        // Assert
        $this->assertSame(CommentDataImportConfig::IMPORT_TYPE_COMMENT, $commentDataImportPlugin->getImportType());
    }
}
