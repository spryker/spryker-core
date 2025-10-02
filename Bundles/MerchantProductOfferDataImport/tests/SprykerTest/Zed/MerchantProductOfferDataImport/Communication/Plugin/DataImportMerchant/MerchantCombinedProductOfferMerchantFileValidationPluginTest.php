<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantProductOfferDataImport\Communication\Plugin\DataImportMerchant;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileInfoTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileTransfer;
use Spryker\Zed\MerchantProductOfferDataImport\Communication\Plugin\DataImportMerchant\MerchantCombinedProductOfferMerchantFileValidationPlugin;
use Spryker\Zed\MerchantProductOfferDataImport\MerchantProductOfferDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductOfferDataImport
 * @group Communication
 * @group Plugin
 * @group DataImportMerchant
 * @group MerchantCombinedProductOfferMerchantFileValidationPluginTest
 * Add your own group annotations below this line
 */
class MerchantCombinedProductOfferMerchantFileValidationPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testShouldNotReturnErrorsWhenRequiredHeadersArePresent(): void
    {
        // Arrange
        $dataImportMerchantFileTransfer = $this->createDataImportMerchantFileTransfer(
            MerchantProductOfferDataImportConfig::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT_OFFER,
            'offer_reference,concrete_sku,merchant_reference,merchant_sku,is_active,approval_status,store_name',
        );

        $dataImportMerchantFileCollectionResponseTransfer = (new DataImportMerchantFileCollectionResponseTransfer())
            ->addDataImportMerchantFile($dataImportMerchantFileTransfer);

        // Act
        $dataImportMerchantFileCollectionResponseTransfer = (new MerchantCombinedProductOfferMerchantFileValidationPlugin())
            ->validate($dataImportMerchantFileCollectionResponseTransfer);

        // Assert
        $this->assertEmpty($dataImportMerchantFileCollectionResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testShouldReturnErrorsWhenRequiredHeadersAreMissing(): void
    {
        // Arrange
        $validDataImportMerchantFileTransfer = $this->createDataImportMerchantFileTransfer(
            MerchantProductOfferDataImportConfig::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT_OFFER,
            'merchant_reference,merchant_sku,is_active,approval_status,store_name',
        );

        $invalidDataImportMerchantFileTransfer1 = $this->createDataImportMerchantFileTransfer(
            'user',
            'user_name,user_email',
        );

        $invalidDataImportMerchantFileTransfer2 = $this->createDataImportMerchantFileTransfer(
            'product',
            'product_sku,product_name,product_price',
        );

        $dataImportMerchantFileCollectionResponseTransfer = (new DataImportMerchantFileCollectionResponseTransfer())
            ->addDataImportMerchantFile($validDataImportMerchantFileTransfer)
            ->addDataImportMerchantFile($invalidDataImportMerchantFileTransfer1)
            ->addDataImportMerchantFile($invalidDataImportMerchantFileTransfer2);

        // Act
        $dataImportMerchantFileCollectionResponseTransfer = (new MerchantCombinedProductOfferMerchantFileValidationPlugin())
            ->validate($dataImportMerchantFileCollectionResponseTransfer);

        // Assert
        $this->assertCount(2, $dataImportMerchantFileCollectionResponseTransfer->getErrors());
    }

    /**
     * @param string $importerType
     * @param string $csvHeaders
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileTransfer
     */
    protected function createDataImportMerchantFileTransfer(
        string $importerType,
        string $csvHeaders
    ): DataImportMerchantFileTransfer {
        return (new DataImportMerchantFileTransfer())
            ->setImporterType($importerType)
            ->setFileInfo((new DataImportMerchantFileInfoTransfer())->setContent($csvHeaders));
    }
}
