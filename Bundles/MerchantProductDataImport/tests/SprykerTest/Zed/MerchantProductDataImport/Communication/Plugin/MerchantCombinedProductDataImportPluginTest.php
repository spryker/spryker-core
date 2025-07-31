<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantProductDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use InvalidArgumentException;
use Spryker\Zed\MerchantProductDataImport\Communication\Plugin\DataImport\MerchantCombinedProductDataImportPlugin;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductDataImport
 * @group Communication
 * @group Plugin
 * @group MerchantCombinedProductDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantCombinedProductDataImportPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testImportFailsWhenDataImporterConfigurationNotProvided(): void
    {
        // Arrange
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf('%s cannot be null.', DataImporterConfigurationTransfer::class),
        );

        // Act
        $merchantCombinedProductDataImportPlugin = new MerchantCombinedProductDataImportPlugin();
        $merchantCombinedProductDataImportPlugin->import();
    }

    /**
     * @return void
     */
    public function testReturnsImportType(): void
    {
        // Arrange
        $merchantCombinedProductDataImportPlugin = new MerchantCombinedProductDataImportPlugin();

        // Assert
        $this->assertEquals(
            MerchantProductDataImportConfig::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT,
            $merchantCombinedProductDataImportPlugin->getImportType(),
        );
    }
}
