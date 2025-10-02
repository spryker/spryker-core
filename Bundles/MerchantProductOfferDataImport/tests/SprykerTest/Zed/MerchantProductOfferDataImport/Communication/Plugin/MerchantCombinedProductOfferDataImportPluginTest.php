<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantProductOfferDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use InvalidArgumentException;
use Spryker\Zed\MerchantProductOfferDataImport\Communication\Plugin\DataImport\MerchantCombinedProductOfferDataImportPlugin;
use Spryker\Zed\MerchantProductOfferDataImport\MerchantProductOfferDataImportConfig;
use SprykerTest\Zed\MerchantProductOfferDataImport\MerchantProductOfferDataImportCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductOfferDataImport
 * @group Communication
 * @group Plugin
 * @group MerchantCombinedProductOfferDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantCombinedProductOfferDataImportPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProductOfferDataImport\MerchantProductOfferDataImportCommunicationTester
     */
    protected MerchantProductOfferDataImportCommunicationTester $tester;

    /**
     * @return void
     */
    public function testImportFailsWhenDataImporterConfigurationNotProvided(): void
    {
        // Arrange
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s cannot be null.', DataImporterConfigurationTransfer::class));

        // Act
        $merchantCombinedProductOfferDataImportPlugin = new MerchantCombinedProductOfferDataImportPlugin();
        $merchantCombinedProductOfferDataImportPlugin->import();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Arrange
        $dataImportPlugin = new MerchantCombinedProductOfferDataImportPlugin();

        // Assert
        $this->assertSame(
            MerchantProductOfferDataImportConfig::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT_OFFER,
            $dataImportPlugin->getImportType(),
        );
    }
}
