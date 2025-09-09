<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantProductDataImport\Communication\Plugin\DataImportMerchant;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer;
use Spryker\Zed\MerchantProductDataImport\Communication\Plugin\DataImportMerchant\MerchantCombinedProductMerchantFileValidationPlugin;
use SprykerTest\Zed\MerchantProductDataImport\MerchantProductDataImportCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductDataImport
 * @group Communication
 * @group Plugin
 * @group DataImportMerchant
 * @group MerchantCombinedProductMerchantFileValidationPluginTest
 * Add your own group annotations below this line
 */
class MerchantCombinedProductMerchantFileValidationPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProductDataImport\MerchantProductDataImportCommunicationTester
     */
    protected MerchantProductDataImportCommunicationTester $tester;

    /**
     * @return void
     */
    public function testShouldNotReturnErrorWhenRequiredHeadersArePresent(): void
    {
        // Arrange
        $dataImportMerchantFileCollectionResponseTransfer = (new DataImportMerchantFileCollectionResponseTransfer())
            ->addDataImportMerchantFile($this->tester->createDataImportMerchantFileTransfer());

        // Act
        $dataImportMerchantFileCollectionResponseTransfer = (new MerchantCombinedProductMerchantFileValidationPlugin())
            ->validate($dataImportMerchantFileCollectionResponseTransfer);

        // Assert
        $this->assertEmpty($dataImportMerchantFileCollectionResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testShouldReturnErrorWhenRequiredHeadersAreMissing(): void
    {
        $dataImportMerchantFileTransfer1 = $this->tester->createDataImportMerchantFileTransfer(
            'user',
            'user_name,user_email',
        );
        $dataImportMerchantFileTransfer2 = $this->tester->createDataImportMerchantFileTransfer(
            'merchant-combined-product',
            'abstract_sku,merchant_email',
        );
        $dataImportMerchantFileTransfer3 = $this->tester->createDataImportMerchantFileTransfer(
            'product',
            'product_sku,product_name,product_price',
        );

        // Arrange
        $dataImportMerchantFileCollectionResponseTransfer = (new DataImportMerchantFileCollectionResponseTransfer())
            ->addDataImportMerchantFile($dataImportMerchantFileTransfer1)
            ->addDataImportMerchantFile($dataImportMerchantFileTransfer2)
            ->addDataImportMerchantFile($dataImportMerchantFileTransfer3);

        // Act
        $dataImportMerchantFileCollectionResponseTransfer = (new MerchantCombinedProductMerchantFileValidationPlugin())
            ->validate($dataImportMerchantFileCollectionResponseTransfer);

        // Assert
        $this->assertCount(1, $dataImportMerchantFileCollectionResponseTransfer->getErrors());
    }
}
