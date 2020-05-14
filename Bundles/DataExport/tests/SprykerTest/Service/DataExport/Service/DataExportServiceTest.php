<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\DataExport\Service;

use Codeception\Configuration;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataExportBatchTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportConnectionConfigurationTransfer;
use Generated\Shared\Transfer\DataExportFormatConfigurationTransfer;
use Spryker\Service\DataExport\DataExportConfig;
use Spryker\Service\DataExport\DataExportService;
use Spryker\Service\DataExport\DataExportServiceFactory;
use Spryker\Service\DataExport\DataExportServiceInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group DataExport
 * @group Service
 * @group DataExportServiceTest
 * Add your own group annotations below this line
 */
class DataExportServiceTest extends Unit
{
    /**
     * @uses \Spryker\Service\DataExport\Writer\DataExportLocalWriter::LOCAL_CONNECTION_PARAM_EXPORT_ROOT_DIR
     */
    protected const LOCAL_CONNECTION_PARAM_EXPORT_ROOT_DIR = 'export_root_dir';

    protected const DATA_ENTITY_MASTER = 'data-entity-master';
    protected const DATA_ENTITY_SLAVE = 'data-entity-slave';

    protected const HOOK_KEY_TIMESTAMP = 'timestamp';
    protected const HOOK_KEY_EXTENSION = 'extension';

    protected const DESTINATION_DIR = 'test-folder';
    protected const DESTINATION_FILE = 'test-export.csv';

    /**
     * @var \SprykerTest\Service\DataExport\DataExportServiceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testParseConfigurationWillParseConfigurationYmlFile(): void
    {
        //Arrange
        $filePath = Configuration::dataDir() . 'defaults_config.yml';

        //Act
        $dataExportConfigurationsTransfer = $this->tester->getService()->parseConfiguration($filePath);

        //Assert
        $this->tester->assertNotNull($dataExportConfigurationsTransfer->getVersion());
        $this->tester->assertNotNull($dataExportConfigurationsTransfer->getDefaults());
        $this->tester->assertNotNull($dataExportConfigurationsTransfer->getActions());
    }

    /**
     * @return void
     */
    public function testMergeDataExportConfigurationsWillMergeConfigurationCorrectly(): void
    {
        //Arrange
        $masterDataExportConfigurationTransfer = (new DataExportConfigurationTransfer())
            ->setDataEntity(static::DATA_ENTITY_MASTER);
        $slaveDataExportConfigurationTransfer = (new DataExportConfigurationTransfer())
            ->setDataEntity(static::DATA_ENTITY_SLAVE);

        //Act
        $dataExportConfigurationTransfer = $this->tester->getService()->mergeDataExportConfigurationTransfers(
            $masterDataExportConfigurationTransfer,
            $slaveDataExportConfigurationTransfer
        );

        //Assert
        $this->assertEquals(static::DATA_ENTITY_MASTER, $dataExportConfigurationTransfer->getDataEntity());
    }

    /**
     * @return void
     */
    public function testMergeDataExportConfigurationsWillMergeHooks(): void
    {
        //Arrange
        $masterDataExportConfigurationTransfer = (new DataExportConfigurationTransfer())
            ->addHook(static::HOOK_KEY_TIMESTAMP, time());
        $slaveDataExportConfigurationTransfer = (new DataExportConfigurationTransfer())
            ->addHook(static::HOOK_KEY_EXTENSION, 'csv');

        //Act
        $dataExportConfigurationTransfer = $this->tester->getService()->mergeDataExportConfigurationTransfers(
            $masterDataExportConfigurationTransfer,
            $slaveDataExportConfigurationTransfer
        );

        //Assert
        $hooks = $dataExportConfigurationTransfer->getHooks();
        $this->assertArrayHasKey(static::HOOK_KEY_TIMESTAMP, $hooks);
        $this->assertArrayHasKey(static::HOOK_KEY_EXTENSION, $hooks);
    }

    /**
     * @return void
     */
    public function testWriteWillCreateNewCsvFile(): void
    {
        //Arrange
        $data = [
            ['header 1' => 'data 1.1', 'header 2' => 'data 1.2'],
            ['header 1' => 'data 2.1', 'header 2' => 'data 2.2'],
        ];

        $dataExportFormatConfigurationTransfer = (new DataExportFormatConfigurationTransfer())->setType('csv');
        $dataExportConnectionConfigurationTransfer = (new DataExportConnectionConfigurationTransfer())
            ->setType('local')
            ->setParams([
                static::LOCAL_CONNECTION_PARAM_EXPORT_ROOT_DIR => rtrim(Configuration::outputDir(), '\\'),
            ]);
        $destination = static::DESTINATION_DIR . DIRECTORY_SEPARATOR . static::DESTINATION_FILE;
        $dataExportConfigurationTransfer = (new DataExportConfigurationTransfer())
            ->setConnection($dataExportConnectionConfigurationTransfer)
            ->setFormat($dataExportFormatConfigurationTransfer)
            ->setDestination($destination);

        $batch = (new DataExportBatchTransfer())
            ->setOffset(0)
            ->setData($data)
            ->setFields(array_keys($data[0]));

        //Act
        $dataExportWriteResponseTransfer = $this->tester->getService()->write(
            $batch,
            $dataExportConfigurationTransfer
        );

        //Assert
        $expectedFilePath = Configuration::outputDir() . $destination;
        $this->assertTrue($dataExportWriteResponseTransfer->getIsSuccessful());
        $this->assertFileExists($expectedFilePath);

        $fileData = $this->tester->getCsvFileData($expectedFilePath);
        $header = array_shift($fileData); // header is not part of object count

        $this->assertCount(count($data), $fileData);
        $this->assertEquals($data, $fileData);

        $this->tester->removeCreatedFiles(static::DESTINATION_DIR);
    }

    /**
     * @return void
     */
    public function testGetFormatExtensionWillReturnExtensionCsv(): void
    {
        //Arrange
        $dataExportFormatConfigurationTransfer = (new DataExportFormatConfigurationTransfer())->setType('csv');
        $dataExportConfigurationTransfer = (new DataExportConfigurationTransfer())
            ->setFormat($dataExportFormatConfigurationTransfer);

        //Act
        $extension = $this->tester->getService()->getFormatExtension($dataExportConfigurationTransfer);

        //Assert
        $this->assertEquals('csv', $extension, 'Expected extension is "csv"');
    }

    /**
     * @deprecated
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Service\DataExport\DataExportConfig
     */
    protected function mockDataExportConfig(): DataExportConfig
    {
        $dataExportConfigMock = $this->getMockBuilder(DataExportConfig::class)->getMock();

        $dataExportConfigMock->method('getDataExportDefaultLocalPath')
            ->willReturn(Configuration::outputDir());

        return $dataExportConfigMock;
    }

    /**
     * @deprecated
     *
     * @return \Spryker\Service\DataExport\DataExportServiceInterface
     */
    public function getDataExportServiceWithConfigMock(): DataExportServiceInterface
    {
        $dataExportServiceFactory = new DataExportServiceFactory();
        $dataExportServiceFactory->setConfig($this->mockDataExportConfig());

        $dataExportService = new DataExportService();
        $dataExportService->setFactory($dataExportServiceFactory);

        return $dataExportService;
    }
}
