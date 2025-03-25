<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ContentProductDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Service\UtilEncoding\UtilEncodingService;
use Spryker\Zed\ContentProductDataImport\Communication\Plugin\ContentProductAbstractListDataImportPlugin;
use Spryker\Zed\ContentProductDataImport\ContentProductDataImportConfig;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ContentProductDataImport
 * @group Communication
 * @group Plugin
 * @group ContentProductDataImportPluginTest
 * Add your own group annotations below this line
 */
class ContentProductDataImportPluginTest extends Unit
{
    use LocatorHelperTrait;

    /**
     * @var string
     */
    protected const EXCEPTION_ERROR_MESSAGE = 'Found invalid skus in a row with the provided key: "apl1", column: "skus.default"';

    /**
     * @var \SprykerTest\Zed\ContentProductDataImport\ContentProductDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @var array<string>
     */
    protected static $productAbstractSkuIds = [];

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();
        $this->tester->ensureDatabaseTableIsEmpty();
        $this->addNeededProductAbstract();
    }

    /**
     * @return void
     */
    protected function addNeededProductAbstract(): void
    {
        $skus = [
            '12314204',
            '12314205',
            '12314156',
            '12314154',
            '12314152',
            '12314151',
            '12314191',
            '12314190',
            '12314180',
            '12314171',
        ];

        foreach ($skus as $sku) {
            static::$productAbstractSkuIds[$sku] =
                $this->tester->haveProductAbstract(['sku' => $sku])->getIdProductAbstract();
        }
    }

    /**
     * @return void
     */
    public function testImportProductAbstractListsData(): void
    {
        // Assign
        $dataImportConfigurationTransfer = $this->createConfigurationTransfer(
            'import/content_product_abstract_list.csv',
        );

        // Act
        $dataImporterReportTransfer = (new ContentProductAbstractListDataImportPlugin())->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData('apl1');
        $this->tester->assertDatabaseTableContainsData('apl2');
        $this->tester->assertDatabaseTableContainsData('apl3');
    }

    /**
     * @return void
     */
    public function testImportProductAbstractListsDataWrongSkus(): void
    {
        // Assign
        $dataImportConfigurationTransfer = $this->createConfigurationTransfer(
            'import/content_product_abstract_list_wrong_skus.csv',
        )->setThrowException(true);

        // Assert
        $this->expectExceptionObject(new DataImportException(static::EXCEPTION_ERROR_MESSAGE));

        // Act
        (new ContentProductAbstractListDataImportPlugin())->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Arrange
        $contentProductAbstractListDataImportPlugin = new ContentProductAbstractListDataImportPlugin();

        // Assert
        $this->assertSame(ContentProductDataImportConfig::IMPORT_TYPE_CONTENT_PRODUCT, $contentProductAbstractListDataImportPlugin->getImportType());
    }

    /**
     * @return void
     */
    public function testUpdateLocale(): void
    {
        // Assign
        //these locale values come from import/content_product_abstract_list(update).csv
        $enLocaleTransfer = $this->getLocaleFacade()->getLocale('en_US');
        $deLocaleTransfer = $this->getLocaleFacade()->getLocale('de_DE');
        $dataImportConfigurationTransfer = $this->createConfigurationTransfer(
            'import/content_product_abstract_list(update).csv',
        );

        // Act
        $dataImporterReportTransfer = (new ContentProductAbstractListDataImportPlugin())->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertContentLocalizedHasProducts(
            $enLocaleTransfer->getIdLocale(),
            $this->createUtilEncodingService()->encodeJson([
                static::$productAbstractSkuIds['12314152'],
                static::$productAbstractSkuIds['12314151']]),
        );
        $this->tester->assertContentLocalizedHasProducts(
            $deLocaleTransfer->getIdLocale(),
            $this->createUtilEncodingService()->encodeJson([
                static::$productAbstractSkuIds['12314156'],
                static::$productAbstractSkuIds['12314154']]),
        );
    }

    /**
     * @return void
     */
    public function testUpdateLocaleFromDefault(): void
    {
        // Assign
        $dataImportConfigurationTransfer = $this->createConfigurationTransfer(
            'import/content_product_abstract_list(update_locale_from_default).csv',
        );
        //these locale values come from import/content_product_abstract_list(update).csv
        $enLocaleTransfer = $this->getLocaleFacade()->getLocale('en_US');
        $deLocaleTransfer = $this->getLocaleFacade()->getLocale('de_DE');

        // Act
        $dataImporterReportTransfer = (new ContentProductAbstractListDataImportPlugin())->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertContentLocalizedHasProducts(
            $enLocaleTransfer->getIdLocale(),
            $this->createUtilEncodingService()->encodeJson([
                static::$productAbstractSkuIds['12314152'],
                static::$productAbstractSkuIds['12314151']]),
        );
        $this->tester->assertContentLocalizedHasProducts(
            $deLocaleTransfer->getIdLocale(),
            $this->createUtilEncodingService()->encodeJson([
                    static::$productAbstractSkuIds['12314152'],
                    static::$productAbstractSkuIds['12314151']]),
        );
    }

    /**
     * @return void
     */
    public function testUpdateLocaleToDefault(): void
    {
        // Assign
        $dataImportConfigurationTransfer = $this->createConfigurationTransfer(
            'import/content_product_abstract_list(update_locale_to_default).csv',
        );
        //these locale values come from import/content_product_abstract_list(update).csv
        $enLocaleTransfer = $this->getLocaleFacade()->getLocale('en_US');
        $deLocaleTransfer = $this->getLocaleFacade()->getLocale('de_DE');

        // Act
        $dataImporterReportTransfer = (new ContentProductAbstractListDataImportPlugin())->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->tester->assertContentLocalizedHasProducts(
            $enLocaleTransfer->getIdLocale(),
            $this->createUtilEncodingService()->encodeJson([
                    static::$productAbstractSkuIds['12314152'],
                    static::$productAbstractSkuIds['12314151']]),
        );

        $this->tester->assertContentLocalizedDoesNotExist($deLocaleTransfer->getIdLocale());
    }

    /**
     * @param string $importFilePath
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    protected function createConfigurationTransfer(string $importFilePath): DataImporterConfigurationTransfer
    {
        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . $importFilePath);

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();

        return $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);
    }

    /**
     * @return \Spryker\Service\UtilEncoding\UtilEncodingService
     */
    protected function createUtilEncodingService(): UtilEncodingService
    {
        return new UtilEncodingService();
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->getLocator()->locale()->facade();
    }
}
