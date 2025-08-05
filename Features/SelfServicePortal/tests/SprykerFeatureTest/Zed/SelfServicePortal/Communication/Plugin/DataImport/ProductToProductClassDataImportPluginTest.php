<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\DataImport;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\DataImport\ProductToProductClassDataImportPlugin;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group DataImport
 * @group ProductToProductClassDataImportPluginTest
 */
class ProductToProductClassDataImportPluginTest extends Unit
{
    /**
     * @var int
     */
    protected const EXPECTED_IMPORT_COUNT = 2;

    /**
     * @var string
     */
    protected const IMPORT_FILE_PATH = 'import/product_to_product_class.csv';

    /**
     * @var string
     */
    protected const IMPORT_FILE_PATH_INVALID = 'import/product_to_product_class_invalid.csv';

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected $tester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureProductClassTableIsEmpty();
    }

    public function testImportImportsProductToProductClassRelations(): void
    {
        // Arrange
        $productClass1 = $this->tester->haveProductClass(['key' => 'hardware']);
        $productClass2 = $this->tester->haveProductClass(['key' => 'software']);
        $product = $this->tester->haveProduct(['sku' => 'demo-1']);

        $configurationTransfer = new DataImporterReaderConfigurationTransfer();
        $configurationTransfer->setFileName(codecept_data_dir() . static::IMPORT_FILE_PATH);

        $dataImporterConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($configurationTransfer)
            ->setThrowException(true);

        $productToProductClassDataImportPlugin = new ProductToProductClassDataImportPlugin();
        $productToProductClassDataImportPlugin = $this->overwriteConfig($productToProductClassDataImportPlugin);

        // Act
        $dataImporterReportTransfer = $productToProductClassDataImportPlugin->import($dataImporterConfigurationTransfer);

        // Assert
        $this->assertSame(static::EXPECTED_IMPORT_COUNT, $dataImporterReportTransfer->getImportedDataSetCount());

        $productClassNames = $this->tester->getProductClassNamesByIdProductConcrete($product->getIdProductConcrete());

        $this->assertContains($productClass1->getName(), $productClassNames);
        $this->assertContains($productClass2->getName(), $productClassNames);
    }

    public function testImportWithInvalidDataThrowsException(): void
    {
        // Arrange
        $configurationTransfer = new DataImporterReaderConfigurationTransfer();
        $configurationTransfer->setFileName(codecept_data_dir() . static::IMPORT_FILE_PATH_INVALID);

        $dataImporterConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($configurationTransfer)
            ->setThrowException(true);

        // Assert
        $this->expectException(DataImportException::class);

        // Act
        $productToProductClassDataImportPlugin = new ProductToProductClassDataImportPlugin();
        $productToProductClassDataImportPlugin->import($dataImporterConfigurationTransfer);
    }

    public function testGetImportType(): void
    {
        // Arrange
        $productToProductClassDataImportPlugin = new ProductToProductClassDataImportPlugin();

        // Act
        $importType = $productToProductClassDataImportPlugin->getImportType();

        // Assert
        $this->assertSame('product-to-product-class', $importType);
    }

    protected function overwriteConfig(ProductToProductClassDataImportPlugin $productToProductClassDataImportPlugin): ProductToProductClassDataImportPlugin
    {
        $moduleNameConstant = '\Pyz\Zed\SelfServicePortal\SelfServicePortalConfig::MODULE_NAME';

        if (!defined($moduleNameConstant)) {
            return $productToProductClassDataImportPlugin;
        }

        $configMock = $this->createPartialMock(SelfServicePortalConfig::class, ['getProductToProductClassDataImporterConfiguration']);
        $configMock->method('getProductToProductClassDataImporterConfiguration')
            ->willReturn(
                (new SelfServicePortalConfig())
                    ->getProductClassDataImporterConfiguration()
                    ->setModuleName(
                        constant($moduleNameConstant),
                    ),
            );

        $productToProductClassDataImportPlugin->setBusinessFactory(
            (new SelfServicePortalBusinessFactory())
                ->setConfig($configMock),
        );

        return $productToProductClassDataImportPlugin;
    }

    protected function _after(): void
    {
        parent::_after();

        $this->tester->truncateProductClassTable();
    }
}
