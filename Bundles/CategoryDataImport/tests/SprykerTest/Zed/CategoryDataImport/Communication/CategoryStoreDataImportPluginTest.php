<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CategoryDataImport\Communication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Category\CategoryDependencyProvider;
use Spryker\Zed\Category\Communication\Plugin\Category\MainChildrenPropagationCategoryStoreAssignerPlugin;
use Spryker\Zed\CategoryDataImport\Communication\Plugin\DataImport\CategoryStoreDataImportPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CategoryDataImport
 * @group Communication
 * @group CategoryStoreDataImportPluginTest
 * Add your own group annotations below this line
 */
class CategoryStoreDataImportPluginTest extends Unit
{
    protected const STORE_NAME_DE = 'DE';
    protected const STORE_NAME_AT = 'AT';
    protected const CATEGORY_NAME_TEST = 'test-category';
    protected const CATEGORY_PARENT_NAME_TEST = 'parent-test-category';
    protected const CATEGORY_CHILD_NAME_TEST = 'child-test-category';

    protected const EXPECTED_IMPORT_COUNT = 1;

    /**
     * @uses \Spryker\Zed\CategoryDataImport\CategoryDataImportConfig::IMPORT_TYPE_CATEGORY_STORE
     */
    protected const IMPORT_TYPE_CATEGORY_STORE = 'category-store';

    /**
     * @var \SprykerTest\Zed\CategoryDataImport\CategoryDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureDatabaseTableIsEmpty();
        $this->tester->setDependency(
            CategoryDependencyProvider::PLUGIN_CATEGORY_STORE_ASSIGNER,
            new MainChildrenPropagationCategoryStoreAssignerPlugin()
        );
    }

    /**
     * @return void
     */
    public function testImportWillImportCategoryStoreRelationshipsData(): void
    {
        // Arrange
        $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $this->tester->haveCategory([CategoryTransfer::CATEGORY_KEY => static::CATEGORY_NAME_TEST]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/category_store.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $categoryStoreDataImportPlugin = new CategoryStoreDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $categoryStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->assertSame(
            static::EXPECTED_IMPORT_COUNT,
            $dataImporterReportTransfer->getImportedDataSetCount(),
            sprintf(
                'Imported number of category stores is %s, expected %s.',
                $dataImporterReportTransfer->getImportedDataSetCount(),
                static::EXPECTED_IMPORT_COUNT
            )
        );
    }

    /**
     * @return void
     */
    public function testImportWillImportCategoryStoreRelationshipsDataWithParentInheritance(): void
    {
        // Arrange
        $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $parentCategoryTransfer = $this->tester->haveCategory([
            CategoryTransfer::CATEGORY_KEY => static::CATEGORY_PARENT_NAME_TEST,
        ]);

        $categoryTransfer = $this->tester->haveCategory([
            CategoryTransfer::CATEGORY_KEY => static::CATEGORY_CHILD_NAME_TEST,
            CategoryTransfer::PARENT_CATEGORY_NODE => $parentCategoryTransfer->getCategoryNode(),
        ]);

        $dataImporterReaderConfigurationTransfer = (new DataImporterReaderConfigurationTransfer())
            ->setFileName(codecept_data_dir() . 'import/category_store_parent.csv');

        $dataImportConfigurationTransfer = (new DataImporterConfigurationTransfer())
            ->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $categoryStoreDataImportPlugin = new CategoryStoreDataImportPlugin();

        // Act
        $dataImporterReportTransfer = $categoryStoreDataImportPlugin->import($dataImportConfigurationTransfer);

        // Assert
        $this->assertTrue($dataImporterReportTransfer->getIsSuccess());

        $this->assertSame(
            2,
            $this->tester->countCategoryStoreRelations($parentCategoryTransfer->getIdCategoryOrFail()),
            'Number of store does not match expected value.'
        );
        $this->assertSame(
            1,
            $this->tester->countCategoryStoreRelations($categoryTransfer->getIdCategoryOrFail()),
            'Number of store does not match expected value.'
        );
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsExpectedTypeOfImporter(): void
    {
        //Assign
        $priceProductScheduleDataImportPlugin = new CategoryStoreDataImportPlugin();

        //Act
        $importType = $priceProductScheduleDataImportPlugin->getImportType();

        //Assert
        $this->assertSame(static::IMPORT_TYPE_CATEGORY_STORE, $importType);
    }
}
