<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantProductOfferDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\DataSet\MerchantProductOfferDataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\MerchantSkuValidationStep;
use Spryker\Zed\MerchantProductOfferDataImport\Communication\Plugin\MerchantProductOfferDataImportPlugin;
use Spryker\Zed\MerchantProductOfferDataImport\MerchantProductOfferDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductOfferDataImport
 * @group Communication
 * @group Plugin
 * @group MerchantProductOfferDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantProductOfferDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProductOfferDataImport\MerchantProductOfferDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        // Arrange
        $this->tester->truncateProductOffers();
        $this->tester->assertProductOfferDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/merchant_product_offer.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImportPlugin = new MerchantProductOfferDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->tester->assertProductOfferDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Arrange
        $dataImportPlugin = new MerchantProductOfferDataImportPlugin();

        // Assert
        $this->assertSame(MerchantProductOfferDataImportConfig::IMPORT_TYPE_MERCHANT_PRODUCT_OFFER, $dataImportPlugin->getImportType());
    }

    /**
     * @return void
     */
    public function testMerchantSkuValidationStepDoesNotThrowExceptionIfMerchantSkuIsEmpty(): void
    {
        // Arrange
        $merchantSkuValidationStep = new MerchantSkuValidationStep();

        $dataSet = new DataSet([
            MerchantProductOfferDataSetInterface::MERCHANT_SKU => '',
            MerchantProductOfferDataSetInterface::ID_MERCHANT => uniqid(),
            MerchantProductOfferDataSetInterface::PRODUCT_OFFER_REFERENCE => uniqid(),
        ]);

        // Act
        try {
            $merchantSkuValidationStep->execute($dataSet);
        } catch (EntityNotFoundException $exception) {
            $this->fail($exception->getMessage());
        }

        // Assert
        $this->assertTrue(true);
    }

    /**
     * @return void
     */
    public function testMerchantSkuValidationStepThrowsExceptionIfProductOfferAlreadyExistForMerchantIdAndSku(): void
    {
        // Arrange
        $merchantSku = uniqid();
        $merchantTransfer = $this->tester->haveMerchant();

        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::MERCHANT_SKU => $merchantSku,
            ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
        ]);

        $merchantSkuValidationStep = new MerchantSkuValidationStep();

        $dataSet = new DataSet([
            MerchantProductOfferDataSetInterface::MERCHANT_SKU => $merchantSku,
            MerchantProductOfferDataSetInterface::MERCHANT_REFERENCE => $productOfferTransfer->getMerchantReference(),
            MerchantProductOfferDataSetInterface::PRODUCT_OFFER_REFERENCE => uniqid(),
        ]);

        // Assert
        $this->expectException(EntityNotFoundException::class);

        // Act
        $merchantSkuValidationStep->execute($dataSet);
    }
}
