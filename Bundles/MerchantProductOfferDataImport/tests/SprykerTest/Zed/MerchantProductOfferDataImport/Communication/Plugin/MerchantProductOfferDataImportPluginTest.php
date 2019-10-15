<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferDataImport\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use ReflectionClass;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBroker;
use Spryker\Zed\MerchantProductOfferDataImport\Business\MerchantProductOfferDataImportBusinessFactory;
use Spryker\Zed\MerchantProductOfferDataImport\Business\MerchantProductOfferDataImportFacade;
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
     * @var \SprykerTest\Zed\MerchantProductOfferDataImport\Helper\MerchantProductOfferDataImportHelper
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsData(): void
    {
        $this->tester->truncateProductOffers();
        $this->tester->assertDatabaseTableIsEmpty();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(codecept_data_dir() . 'import/merchant_product_offer.csv');

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);

        $dataImportPlugin = new MerchantProductOfferDataImportPlugin();
        $pluginReflection = new ReflectionClass($dataImportPlugin);

        $facadePropertyReflection = $pluginReflection->getParentClass()->getProperty('facade');
        $facadePropertyReflection->setAccessible(true);
        $facadePropertyReflection->setValue($dataImportPlugin, $this->getFacadeMock());

        $dataImporterReportTransfer = $dataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testGetImportTypeReturnsTypeOfImporter(): void
    {
        $dataImportPlugin = new MerchantProductOfferDataImportPlugin();
        $this->assertSame(MerchantProductOfferDataImportConfig::IMPORT_TYPE_MERCHANT_PRODUCT_OFFER, $dataImportPlugin->getImportType());
    }

    /**
     * @return \Spryker\Zed\MerchantProductOfferDataImport\Business\MerchantProductOfferDataImportFacade
     */
    public function getFacadeMock(): MerchantProductOfferDataImportFacade
    {
        $factoryMock = $this->getMockBuilder(MerchantProductOfferDataImportBusinessFactory::class)
            ->setMethods(
                [
                    'createTransactionAwareDataSetStepBroker',
                    'getConfig',
                ]
            )
            ->getMock();

        $factoryMock
            ->method('createTransactionAwareDataSetStepBroker')
            ->willReturn(new DataSetStepBroker());

        $factoryMock->method('getConfig')
            ->willReturn(new MerchantProductOfferDataImportConfig());

        $facade = new MerchantProductOfferDataImportFacade();
        $facade->setFactory($factoryMock);

        return $facade;
    }
}
