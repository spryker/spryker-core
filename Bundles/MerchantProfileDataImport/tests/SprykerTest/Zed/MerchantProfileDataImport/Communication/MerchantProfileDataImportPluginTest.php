<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProfileDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
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
    public const MERCHANT_KEY = 'wolf-gmbh-und-co-kg';
    /**
     * @var \SprykerTest\Zed\MerchantProfileDataImport\MerchantProfileDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testMerchantProfileDataImportFacade(): void
    {
        $merchantEntity = $this->tester->findMerchantByKey(static::MERCHANT_KEY);
        if ($merchantEntity === null) {
            $this->tester->haveMerchant([
                'merchant_key' => static::MERCHANT_KEY,
            ]);
        }

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/merchant_profile.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImportPlugin = new MerchantProfileDataImportPlugin();

        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);
        $merchantEntity = $this->tester->findMerchantByKey(static::MERCHANT_KEY);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->assertNotEmpty($merchantEntity);
        $this->assertNotEmpty($merchantEntity->getSpyMerchantProfiles()->getArrayCopy());
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $dataImportPlugin = new MerchantProfileDataImportPlugin();
        $this->assertSame(MerchantProfileDataImportConfig::IMPORT_TYPE_MERCHANT_PROFILE, $dataImportPlugin->getImportType());
    }
}
