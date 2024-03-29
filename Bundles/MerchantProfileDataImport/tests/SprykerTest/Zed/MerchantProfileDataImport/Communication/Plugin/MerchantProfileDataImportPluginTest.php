<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantProfileDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Spryker\Zed\MerchantProfileDataImport\Communication\Plugin\MerchantProfileDataImportPlugin;
use Spryker\Zed\MerchantProfileDataImport\MerchantProfileDataImportConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProfileDataImport
 * @group Communication
 * @group Plugin
 * @group MerchantProfileDataImportPluginTest
 * Add your own group annotations below this line
 */
class MerchantProfileDataImportPluginTest extends Unit
{
    /**
     * @var string
     */
    public const MERCHANT_REFERENCE = 'merchant-profile-data-import-test-reference';

    /**
     * @var \SprykerTest\Zed\MerchantProfileDataImport\MerchantProfileDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testMerchantProfileDataImportFacade(): void
    {
        // Arrange
        $merchantEntity = $this->tester->findMerchantByReference(static::MERCHANT_REFERENCE);
        if ($merchantEntity === null) {
            $this->tester->haveMerchant([
                'merchant_reference' => static::MERCHANT_REFERENCE,
            ]);
        }

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/merchant_profile.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImportPlugin = new MerchantProfileDataImportPlugin();

        // Act
        $dataImportPlugin->import($dataImportConfigurationTransfer);
        $merchantEntity = $this->tester->findMerchantByReference(static::MERCHANT_REFERENCE);

        // Assert

        $this->assertNotEmpty($merchantEntity);
        $this->assertNotEmpty($merchantEntity->getSpyMerchantProfiles()->getArrayCopy());
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        // Arrange
        $dataImportPlugin = new MerchantProfileDataImportPlugin();

        // Act
        $importType = $dataImportPlugin->getImportType();

        // Assert
        $this->assertSame(MerchantProfileDataImportConfig::IMPORT_TYPE_MERCHANT_PROFILE, $importType);
    }
}
