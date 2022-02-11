<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ProductApprovalDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\ProductApprovalDataImport\Business\DataSet\ProductApprovalStatusDataSetInterface;
use Spryker\Zed\ProductApprovalDataImport\Communication\Plugin\DataImport\ProductAbstractApprovalStatusDataImportPlugin;
use Spryker\Zed\ProductApprovalDataImport\ProductApprovalDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductApprovalDataImport
 * @group Communication
 * @group Plugin
 * @group ProductAbstractApprovalStatusDataImportPluginTest
 * Add your own group annotations below this line
 */
class ProductAbstractApprovalStatusDataImportPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const PRODUCT_ABSTRACT_SKU_1 = 'SKU-1';

    /**
     * @var string
     */
    protected const PRODUCT_ABSTRACT_SKU_2 = 'SKU-2';

    /**
     * @var string
     */
    protected const PRODUCT_ABSTRACT_SKU_3 = 'SKU-3';

    /**
     * @var string
     */
    protected const PRODUCT_ABSTRACT_SKU_4 = 'SKU-4';

    /**
     * @var \SprykerTest\Zed\ProductApprovalDataImport\ProductApprovalDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductApprovalDataImport\Communication\Plugin\DataImport\ProductAbstractApprovalStatusDataImportPlugin
     */
    protected $plugin;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new ProductAbstractApprovalStatusDataImportPlugin();
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenProductAbstractSkuIsEmpty(): void
    {
        // Arrange
        $dataImportConfigurationTransfer = $this->tester->createDataImporterConfigurationTransfer(
            'import/product_approval_status_with_empty_sku.csv',
        );

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage(sprintf('"%s" is required', ProductApprovalStatusDataSetInterface::PRODUCT_ABSTRACT_SKU));

        // Act
        $this->plugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenProductAbstractIsNotFoundBySku(): void
    {
        // Arrange
        $dataImportConfigurationTransfer = $this->tester->createDataImporterConfigurationTransfer(
            'import/product_approval_status.csv',
        );

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage(sprintf('Could not find Product Abstract by SKU "%s".', static::PRODUCT_ABSTRACT_SKU_1));

        // Act
        $this->plugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @dataProvider getNotCorrectApprovalStatusImportFileNames
     *
     * @param string $fileName
     *
     * @return void
     */
    public function testImportThrowsExceptionWhenApprovalStatusIsNotCorrect(string $fileName): void
    {
        // Arrange
        $this->tester->deleteAbstractProductsBySkus([
            static::PRODUCT_ABSTRACT_SKU_1,
        ]);
        $this->tester->haveProductAbstract([
            ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_SKU_1,
        ]);
        $dataImportConfigurationTransfer = $this->tester->createDataImporterConfigurationTransfer($fileName);

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage(sprintf(
            '"%s" should have one of the values: %s.',
            ProductApprovalStatusDataSetInterface::APPROVAL_STATUS,
            implode(', ', $this->tester->getApprovalStatusAllowedValues()),
        ));

        // Act
        $this->plugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return array<int, array<int, mixed>>
     */
    public function getNotCorrectApprovalStatusImportFileNames(): array
    {
        return [
            ['import/product_approval_status_with_empty_approval_status.csv'],
            ['import/product_approval_status_with_incorrect_approval_status.csv'],
        ];
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        // Arrange
        $this->tester->deleteAbstractProductsBySkus([
            static::PRODUCT_ABSTRACT_SKU_1,
            static::PRODUCT_ABSTRACT_SKU_2,
            static::PRODUCT_ABSTRACT_SKU_3,
            static::PRODUCT_ABSTRACT_SKU_4,
        ]);

        $expectedDataSet = [
            static::PRODUCT_ABSTRACT_SKU_1 => 'approved',
            static::PRODUCT_ABSTRACT_SKU_2 => 'waiting_for_approval',
            static::PRODUCT_ABSTRACT_SKU_3 => 'denied',
            static::PRODUCT_ABSTRACT_SKU_4 => 'draft',
        ];

        $this->tester->haveProductAbstract([
            ProductAbstractTransfer::ID_PRODUCT_ABSTRACT => 1,
            ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_SKU_1,
        ]);
        $this->tester->haveProductAbstract([
            ProductAbstractTransfer::ID_PRODUCT_ABSTRACT => 2,
            ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_SKU_2,
        ]);
        $this->tester->haveProductAbstract([
            ProductAbstractTransfer::ID_PRODUCT_ABSTRACT => 3,
            ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_SKU_3,
        ]);
        $this->tester->haveProductAbstract([
            ProductAbstractTransfer::ID_PRODUCT_ABSTRACT => 4,
            ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_SKU_4,
        ]);

        $dataImportConfigurationTransfer = $this->tester->createDataImporterConfigurationTransfer(
            'import/product_approval_status.csv',
        );

        // Act
        $dataImporterReportTransfer = $this->plugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->tester->assertProductApprovalStatuses($expectedDataSet);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Assert
        $this->assertSame(
            ProductApprovalDataImportConfig::IMPORT_TYPE_PRODUCT_APPROVAL_STATUS,
            $this->plugin->getImportType(),
        );
    }
}
