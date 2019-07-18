<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\IndexGenerator;

use Codeception\Actor;
use Codeception\Stub;
use Spryker\Zed\IndexGenerator\Business\IndexGeneratorBusinessFactory;
use Spryker\Zed\IndexGenerator\Business\IndexGeneratorFacade;
use Spryker\Zed\IndexGenerator\Business\IndexGeneratorFacadeInterface;
use Spryker\Zed\IndexGenerator\IndexGeneratorConfig;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class IndexGeneratorBusinessTester extends Actor
{
    use _generated\IndexGeneratorBusinessTesterActions;

    /**
     * @param string $relativeSourceDirectory
     * @param array|null $excludedTables
     *
     * @return \Spryker\Zed\IndexGenerator\Business\IndexGeneratorFacadeInterface
     */
    public function getFacadeWithMockedConfig(string $relativeSourceDirectory, ?array $excludedTables = []): IndexGeneratorFacadeInterface
    {
        $configMock = $this->getConfigMock($relativeSourceDirectory, $excludedTables);
        $indexGeneratorBusinessFactory = new IndexGeneratorBusinessFactory();
        $indexGeneratorBusinessFactory->setConfig($configMock);

        $indexGeneratorFacade = new IndexGeneratorFacade();
        $indexGeneratorFacade->setFactory($indexGeneratorBusinessFactory);

        return $indexGeneratorFacade;
    }

    /**
     * @param string $relativeSourceDirectory
     * @param array|null $excludedTables
     *
     * @return \Spryker\Zed\IndexGenerator\IndexGeneratorConfig|object
     */
    protected function getConfigMock(string $relativeSourceDirectory, ?array $excludedTables = [])
    {
        $configMock = Stub::make(IndexGeneratorConfig::class, [
            'getExcludedTables' => function () use ($excludedTables) {
                return $excludedTables;
            },
            'getTargetDirectory' => function () {
                return $this->getTargetDirectory();
            },
            'getPathToMergedSchemas' => function () use ($relativeSourceDirectory) {
                return codecept_data_dir() . $relativeSourceDirectory;
            },
        ]);

        return $configMock;
    }

    /**
     * @return string
     */
    protected function getTargetDirectory(): string
    {
        return codecept_data_dir() . 'GeneratedSchemas';
    }

    /**
     * @return string
     */
    protected function getPathToGeneratedSchema(): string
    {
        return $this->getTargetDirectory() . DIRECTORY_SEPARATOR . 'test.schema.xml';
    }

    /**
     * @return void
     */
    public function assertSchemaFileExists(): void
    {
        $this->assertFileExists($this->getPathToGeneratedSchema(), 'Schema file was not created as expected.');
    }

    /**
     * @return void
     */
    public function assertSchemaFileNotExists(): void
    {
        $this->assertFileNotExists($this->getPathToGeneratedSchema(), 'Schema file was not expected to be created but was.');
    }

    /**
     * @return void
     */
    public function assertSchemaHasIndex(): void
    {
        $this->assertSchemaFileExists();

        $simpleXmlElement = simplexml_load_file($this->getPathToGeneratedSchema());
        $indexColumns = $simpleXmlElement->xpath('//index/index-column');

        $this->assertTrue(count($indexColumns) > 0);
        $this->assertSame('fk_zip_zap', (string)$indexColumns[0]['name']);
    }

    public function __destruct()
    {
        if (file_exists($this->getPathToGeneratedSchema())) {
            unlink($this->getPathToGeneratedSchema());
        }
        if (is_dir($this->getTargetDirectory())) {
            rmdir($this->getTargetDirectory());
        }
    }
}
