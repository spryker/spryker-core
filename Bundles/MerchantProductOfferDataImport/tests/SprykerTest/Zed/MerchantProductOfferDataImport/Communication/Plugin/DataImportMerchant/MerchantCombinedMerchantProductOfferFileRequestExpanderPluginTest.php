<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantProductOfferDataImport\Communication\Plugin\DataImportMerchant;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileInfoTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\MerchantProductOfferDataImport\Communication\Plugin\DataImportMerchant\MerchantCombinedMerchantProductOfferFileRequestExpanderPlugin;
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
 * @group MerchantCombinedMerchantProductOfferFileRequestExpanderPluginTest
 * Add your own group annotations below this line
 */
class MerchantCombinedMerchantProductOfferFileRequestExpanderPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testExpandSetsFileSystemNameForMerchantCombinedProductOfferType(): void
    {
        // Arrange
        $dataImportMerchantFileTransfer = (new DataImportMerchantFileTransfer())
            ->setFileInfo(new DataImportMerchantFileInfoTransfer())
            ->setImporterType(MerchantProductOfferDataImportConfig::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT_OFFER);

        $dataImportMerchantFileCollectionRequestTransfer = (new DataImportMerchantFileCollectionRequestTransfer())
            ->addDataImportMerchantFile($dataImportMerchantFileTransfer);

        // Act
        $dataImportMerchantFileCollectionRequestTransfer = (new MerchantCombinedMerchantProductOfferFileRequestExpanderPlugin())
            ->expand($dataImportMerchantFileCollectionRequestTransfer);

        // Assert
        $this->assertNotNull(
            $dataImportMerchantFileCollectionRequestTransfer->getDataImportMerchantFiles()->getIterator()->current()->getFileInfo()->getFileSystemName(),
        );
    }

    /**
     * @return void
     */
    public function testExpandDoesNotSetFileSystemNameForUndefinedImportType(): void
    {
        // Arrange
        $dataImportMerchantFileTransfer = (new DataImportMerchantFileTransfer())
            ->setFileInfo(new DataImportMerchantFileInfoTransfer())
            ->setImporterType('undefined-import-type');

        $dataImportMerchantFileCollectionRequestTransfer = (new DataImportMerchantFileCollectionRequestTransfer())
            ->addDataImportMerchantFile($dataImportMerchantFileTransfer);

        // Act
        $dataImportMerchantFileCollectionRequestTransfer = (new MerchantCombinedMerchantProductOfferFileRequestExpanderPlugin())
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
        $merchantCombinedProductOfferFileTransfer = (new DataImportMerchantFileTransfer())
            ->setFileInfo(new DataImportMerchantFileInfoTransfer())
            ->setImporterType(MerchantProductOfferDataImportConfig::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT_OFFER);

        $otherFileTransfer = (new DataImportMerchantFileTransfer())
            ->setFileInfo(new DataImportMerchantFileInfoTransfer())
            ->setImporterType('other-import-type');

        $dataImportMerchantFileCollectionRequestTransfer = (new DataImportMerchantFileCollectionRequestTransfer())
            ->addDataImportMerchantFile($merchantCombinedProductOfferFileTransfer)
            ->addDataImportMerchantFile($otherFileTransfer);

        // Act
        $dataImportMerchantFileCollectionRequestTransfer = (new MerchantCombinedMerchantProductOfferFileRequestExpanderPlugin())
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
        $dataImportMerchantFileCollectionRequestTransfer = (new MerchantCombinedMerchantProductOfferFileRequestExpanderPlugin())
            ->expand($dataImportMerchantFileCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $dataImportMerchantFileCollectionRequestTransfer->getDataImportMerchantFiles());
    }

    /**
     * @return void
     */
    public function testExpandShouldFailWhenFileInfoPropertyIsMissing(): void
    {
        // Assert
        $this->expectException(NullValueException::class);

        // Arrange
        $dataImportMerchantFileTransfer = (new DataImportMerchantFileTransfer())
            ->setImporterType(MerchantProductOfferDataImportConfig::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT_OFFER);

        $dataImportMerchantFileCollectionRequestTransfer = (new DataImportMerchantFileCollectionRequestTransfer())
            ->addDataImportMerchantFile($dataImportMerchantFileTransfer);

        // Act
        (new MerchantCombinedMerchantProductOfferFileRequestExpanderPlugin())->expand($dataImportMerchantFileCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testExpandShouldFailWhenImporterTypePropertyIsMissing(): void
    {
        // Assert
        $this->expectException(NullValueException::class);

        // Arrange
        $dataImportMerchantFileTransfer = new DataImportMerchantFileTransfer();

        $dataImportMerchantFileCollectionRequestTransfer = (new DataImportMerchantFileCollectionRequestTransfer())
            ->addDataImportMerchantFile($dataImportMerchantFileTransfer);

        // Act
        (new MerchantCombinedMerchantProductOfferFileRequestExpanderPlugin())->expand($dataImportMerchantFileCollectionRequestTransfer);
    }
}
