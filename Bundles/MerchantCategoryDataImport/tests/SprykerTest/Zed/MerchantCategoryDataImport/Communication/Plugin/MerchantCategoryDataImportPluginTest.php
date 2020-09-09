<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantCategoryDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategoryQuery;
use Spryker\Zed\MerchantCategoryDataImport\Communication\Plugin\MerchantCategoryDataImportPlugin;
use Spryker\Zed\MerchantCategoryDataImport\MerchantCategoryDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantCategoryDataImport
 * @group Communication
 * @group Plugin
 * @group MerchantCategoryDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantCategoryDataImportPluginTest extends Unit
{
    protected const MERCHANT_REFERENCE = 'MERCHANT_TEST';
    protected const CATEGORY_KEY = 'test_key';

    /**
     * @var \SprykerTest\Zed\MerchantCategoryDataImport\MerchantCategoryDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureMerchantCategoryAbstractTablesIsEmpty();
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        // Arrange
        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => static::MERCHANT_REFERENCE,
        ]);
        $this->tester->haveCategory([
            CategoryTransfer::CATEGORY_KEY => static::CATEGORY_KEY,
        ]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/merchant_category.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        // Act
        $MerchantCategoryDataImportPlugin = new MerchantCategoryDataImportPlugin();
        $dataImporterReportTransfer = $MerchantCategoryDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        self::assertEmpty($dataImporterReportTransfer->getDataImporterReports());
        self::assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        self::assertTrue(
            SpyMerchantCategoryQuery::create()->find()->count() > 0,
            'Expected at least one entry in the database table but database table is empty.'
        );
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Act
        $MerchantCategoryDataImportPlugin = new MerchantCategoryDataImportPlugin();

        // Assert
        self::assertSame(
            MerchantCategoryDataImportConfig::IMPORT_TYPE_MERCHANT_CATEGORY,
            $MerchantCategoryDataImportPlugin->getImportType()
        );
    }
}
