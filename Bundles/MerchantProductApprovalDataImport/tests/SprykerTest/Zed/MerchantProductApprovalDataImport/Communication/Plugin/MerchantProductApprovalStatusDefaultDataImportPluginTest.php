<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantProductApprovalDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\MerchantProductApprovalDataImport\Business\DataSet\MerchantProductApprovalStatusDefaultDataSetInterface;
use Spryker\Zed\MerchantProductApprovalDataImport\Communication\Plugin\DataImport\MerchantProductApprovalStatusDefaultDataImportPlugin;
use Spryker\Zed\MerchantProductApprovalDataImport\MerchantProductApprovalDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductApprovalDataImport
 * @group Communication
 * @group Plugin
 * @group MerchantProductApprovalStatusDefaultDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantProductApprovalStatusDefaultDataImportPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const MERCHANT_REFERENCE_1 = 'MER-1';

    /**
     * @var string
     */
    protected const MERCHANT_REFERENCE_2 = 'MER-2';

    /**
     * @var string
     */
    protected const MERCHANT_REFERENCE_3 = 'MER-3';

    /**
     * @var string
     */
    protected const MERCHANT_REFERENCE_4 = 'MER-4';

    /**
     * @var \SprykerTest\Zed\MerchantProductApprovalDataImport\MerchantProductApprovalDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\MerchantProductApprovalDataImport\Communication\Plugin\DataImport\MerchantProductApprovalStatusDefaultDataImportPlugin
     */
    protected $plugin;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->plugin = new MerchantProductApprovalStatusDefaultDataImportPlugin();
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
        $dataImportConfigurationTransfer = $this->tester->createDataImporterConfigurationTransfer($fileName);

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage(sprintf(
            '"%s" should have one of the values: %s.',
            MerchantProductApprovalStatusDefaultDataSetInterface::APPROVAL_STATUS,
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
            ['import/merchant_product_approval_status_default_with_empty_approval_status.csv'],
            ['import/merchant_product_approval_status_default_with_incorrect_approval_status.csv'],
        ];
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenMerchantReferenceIsEmpty(): void
    {
        // Arrange
        $dataImportConfigurationTransfer = $this->tester->createDataImporterConfigurationTransfer(
            'import/merchant_product_approval_status_default_with_empty_merchant_reference.csv',
        );

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage(sprintf(
            '"%s" is required.',
            MerchantProductApprovalStatusDefaultDataSetInterface::MERCHANT_REFERENCE,
        ));

        // Act
        $this->plugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenMerchantNotFoundByReference(): void
    {
        // Arrange
        $this->tester->deleteMerchantByReferences([
            static::MERCHANT_REFERENCE_1,
        ]);
        $dataImportConfigurationTransfer = $this->tester->createDataImporterConfigurationTransfer(
            'import/merchant_product_approval_status_default.csv',
        );

        // Assert
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage(sprintf(
            'Could not find Merchant by reference "%s".',
            static::MERCHANT_REFERENCE_1,
        ));

        // Act
        $this->plugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        // Arrange
        $this->tester->deleteMerchantByReferences([
            static::MERCHANT_REFERENCE_1,
            static::MERCHANT_REFERENCE_2,
            static::MERCHANT_REFERENCE_3,
            static::MERCHANT_REFERENCE_4,
        ]);
        $expectedDataSet = [
            static::MERCHANT_REFERENCE_1 => 'approved',
            static::MERCHANT_REFERENCE_2 => 'waiting_for_approval',
            static::MERCHANT_REFERENCE_3 => 'denied',
            static::MERCHANT_REFERENCE_4 => 'draft',
        ];
        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => static::MERCHANT_REFERENCE_1,
        ]);
        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => static::MERCHANT_REFERENCE_2,
        ]);
        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => static::MERCHANT_REFERENCE_3,
        ]);
        $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => static::MERCHANT_REFERENCE_4,
        ]);
        $dataImportConfigurationTransfer = $this->tester->createDataImporterConfigurationTransfer(
            'import/merchant_product_approval_status_default.csv',
        );

        // Act
        $dataImporterReportTransfer = $this->plugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->tester->assertDefaultProductAbstractApprovalStatuses($expectedDataSet);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Assert
        $this->assertSame(
            MerchantProductApprovalDataImportConfig::IMPORT_TYPE_MERCHANT_PRODUCT_APPROVAL_STATUS_DEFAULT,
            $this->plugin->getImportType(),
        );
    }
}
