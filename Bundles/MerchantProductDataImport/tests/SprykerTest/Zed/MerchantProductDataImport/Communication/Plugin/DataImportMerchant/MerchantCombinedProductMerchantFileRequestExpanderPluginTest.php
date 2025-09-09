<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantProductDataImport\Communication\Plugin\DataImportMerchant;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileInfoTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\MerchantProductDataImport\Communication\Plugin\DataImportMerchant\MerchantCombinedProductMerchantFileRequestExpanderPlugin;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;
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
 * @group MerchantCombinedProductMerchantFileRequestExpanderPluginTest
 * Add your own group annotations below this line
 */
class MerchantCombinedProductMerchantFileRequestExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProductDataImport\MerchantProductDataImportCommunicationTester
     */
    protected MerchantProductDataImportCommunicationTester $tester;

    /**
     * @return void
     */
    public function testExpandSetsFileSystemNameForMerchantCombinedProductType(): void
    {
        // Arrange
        $dataImportMerchantFileTransfer = (new DataImportMerchantFileTransfer())
            ->setFileInfo(new DataImportMerchantFileInfoTransfer())
            ->setImporterType(MerchantProductDataImportConfig::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT);

        $dataImportMerchantFileCollectionRequestTransfer = (new DataImportMerchantFileCollectionRequestTransfer())
            ->addDataImportMerchantFile($dataImportMerchantFileTransfer);

        // Act
        $dataImportMerchantFileCollectionRequestTransfer = (new MerchantCombinedProductMerchantFileRequestExpanderPlugin())
            ->expand($dataImportMerchantFileCollectionRequestTransfer);

        // Assert
        $this->assertNotNull(
            $dataImportMerchantFileCollectionRequestTransfer->getDataImportMerchantFiles()->getIterator()->current()->getFileInfo()->getFileSystemName(),
        );
    }

    /**
     * @return void
     */
    public function testExpandDoesNotSetFileSystemNameForOtherTypes(): void
    {
        // Arrange
        $dataImportMerchantFileTransfer = (new DataImportMerchantFileTransfer())
            ->setFileInfo(new DataImportMerchantFileInfoTransfer())
            ->setImporterType('other-import-type');

        $dataImportMerchantFileCollectionRequestTransfer = (new DataImportMerchantFileCollectionRequestTransfer())
            ->addDataImportMerchantFile($dataImportMerchantFileTransfer);

        // Act
        $dataImportMerchantFileCollectionRequestTransfer = (new MerchantCombinedProductMerchantFileRequestExpanderPlugin())
            ->expand($dataImportMerchantFileCollectionRequestTransfer);

        // Assert
        $this->assertNull(
            $dataImportMerchantFileCollectionRequestTransfer->getDataImportMerchantFiles()->getIterator()->current()->getFileInfo()->getFileSystemName(),
        );
    }

    /**
     * @return void
     */
    public function testExpandHandlesMultipleFiles(): void
    {
        // Arrange
        $merchantCombinedProductFileTransfer = (new DataImportMerchantFileTransfer())
            ->setFileInfo(new DataImportMerchantFileInfoTransfer())
            ->setImporterType(MerchantProductDataImportConfig::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT);

        $otherFileTransfer = (new DataImportMerchantFileTransfer())
            ->setFileInfo(new DataImportMerchantFileInfoTransfer())
            ->setImporterType('other-import-type');

        $dataImportMerchantFileCollectionRequestTransfer = (new DataImportMerchantFileCollectionRequestTransfer())
            ->addDataImportMerchantFile($merchantCombinedProductFileTransfer)
            ->addDataImportMerchantFile($otherFileTransfer);

        // Act
        $dataImportMerchantFileCollectionRequestTransfer = (new MerchantCombinedProductMerchantFileRequestExpanderPlugin())
            ->expand($dataImportMerchantFileCollectionRequestTransfer);

        // Assert
        $files = $dataImportMerchantFileCollectionRequestTransfer->getDataImportMerchantFiles()->getArrayCopy();

        $this->assertNotNull($files[0]->getFileInfo()->getFileSystemName());
        $this->assertNull($files[1]->getFileInfo()->getFileSystemName());
    }

    /**
     * @return void
     */
    public function testExpandHandlesEmptyCollection(): void
    {
        // Arrange
        $dataImportMerchantFileCollectionRequestTransfer = new DataImportMerchantFileCollectionRequestTransfer();

        // Act
        $dataImportMerchantFileCollectionRequestTransfer = (new MerchantCombinedProductMerchantFileRequestExpanderPlugin())
            ->expand($dataImportMerchantFileCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $dataImportMerchantFileCollectionRequestTransfer->getDataImportMerchantFiles());
    }

    /**
     * @return void
     */
    public function testExpandRequiresFileInfoProperty(): void
    {
        // Assert
        $this->expectException(NullValueException::class);

        // Arrange
        $dataImportMerchantFileTransfer = (new DataImportMerchantFileTransfer())
            ->setImporterType(MerchantProductDataImportConfig::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT);

        $dataImportMerchantFileCollectionRequestTransfer = (new DataImportMerchantFileCollectionRequestTransfer())
            ->addDataImportMerchantFile($dataImportMerchantFileTransfer);

        // Act
        (new MerchantCombinedProductMerchantFileRequestExpanderPlugin())->expand($dataImportMerchantFileCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testExpandRequiresImporterTypeProperty(): void
    {
        // Assert
        $this->expectException(NullValueException::class);

        // Arrange
        $dataImportMerchantFileTransfer = new DataImportMerchantFileTransfer();

        $dataImportMerchantFileCollectionRequestTransfer = (new DataImportMerchantFileCollectionRequestTransfer())
            ->addDataImportMerchantFile($dataImportMerchantFileTransfer);

        // Act
        (new MerchantCombinedProductMerchantFileRequestExpanderPlugin())->expand($dataImportMerchantFileCollectionRequestTransfer);
    }
}
