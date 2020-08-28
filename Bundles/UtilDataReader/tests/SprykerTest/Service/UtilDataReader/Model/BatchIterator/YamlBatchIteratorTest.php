<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilDataReader\Model\BatchIterator;

use Codeception\Configuration;
use Codeception\Test\Unit;
use Spryker\Service\UtilDataReader\Dependency\YamlReaderBridge;
use Spryker\Service\UtilDataReader\Exception\ResourceNotFoundException;
use Spryker\Service\UtilDataReader\Model\BatchIterator\YamlBatchIterator;
use Symfony\Component\Yaml\Yaml;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group UtilDataReader
 * @group Model
 * @group BatchIterator
 * @group YamlBatchIteratorTest
 * Add your own group annotations below this line
 */
class YamlBatchIteratorTest extends Unit
{
    /**
     * @return void
     */
    public function testThrowsExceptionIfFileNotValid(): void
    {
        $this->expectException(ResourceNotFoundException::class);
        $yamlBatchIterator = $this->getYamlBatchIteratorWithInvalidFile();

        $this->assertNotNull($yamlBatchIterator->current());
    }

    /**
     * @return void
     */
    public function testCurrentReturnsValidEntry(): void
    {
        $yamlBatchIterator = $this->getYamlBatchIterator();

        $this->assertNotNull($yamlBatchIterator->current());
    }

    /**
     * @return void
     */
    public function testNextIncreasesOffset(): void
    {
        $yamlBatchIterator = $this->getYamlBatchIterator();

        $offset = $yamlBatchIterator->key();
        $yamlBatchIterator->next();

        $this->assertTrue($offset < $yamlBatchIterator->key(), 'Offset not increased');
    }

    /**
     * @return void
     */
    public function testKeyReturnsCurrentOffset(): void
    {
        $yamlBatchIterator = $this->getYamlBatchIterator();

        $this->assertSame(0, $yamlBatchIterator->key(), 'Initial offset is not zero');
    }

    /**
     * @return void
     */
    public function testValidReturnsFalseIfBatchDataIsNotInitialized(): void
    {
        $yamlBatchIterator = $this->getYamlBatchIterator();

        $this->assertFalse($yamlBatchIterator->valid(), 'batchData was expected to be not initialized');
    }

    /**
     * @return void
     */
    public function testValidReturnsTrueIfBatchDataIsInitialized(): void
    {
        $yamlBatchIterator = $this->getYamlBatchIterator();
        $yamlBatchIterator->current();

        $this->assertTrue($yamlBatchIterator->valid(), 'batchData was expected to be initialized');
    }

    /**
     * @return void
     */
    public function testRewindResetOffset(): void
    {
        $yamlBatchIterator = $this->getYamlBatchIterator();
        $yamlBatchIterator->next();
        $yamlBatchIterator->rewind();

        $this->assertSame(0, $yamlBatchIterator->key(), 'Offset was not reset to zero');
    }

    /**
     * @return void
     */
    public function testCountReturnsNumberOfRowsInBatchData(): void
    {
        $yamlBatchIterator = $this->getYamlBatchIterator();

        $this->assertTrue($yamlBatchIterator->count() > 0, 'Count was expected to be higher then zero');
    }

    /**
     * @return void
     */
    public function testIteratorWillReturnExpectedData(): void
    {
        //Arrange
        $expectedData = [
            'foo' => 'bar',
            'baz' => 'bat',
        ];

        //Act
        $yamlBatchIterator = $this->getYamlBatchIterator();
        $resultedData = $yamlBatchIterator->current();

        //Assert
        $this->assertEquals($expectedData, $resultedData, 'Resulted data does not match expected result.');
    }

    /**
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\YamlBatchIterator
     */
    protected function getYamlBatchIterator(): YamlBatchIterator
    {
        $fileName = Configuration::dataDir() . '/BatchIterator/batchIterator.yml';

        return $this->getBatchIterator($fileName);
    }

    /**
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\YamlBatchIterator
     */
    protected function getYamlBatchIteratorWithInvalidFile(): YamlBatchIterator
    {
        $fileName = Configuration::dataDir() . '/BatchIterator/notValid.yml';

        return $this->getBatchIterator($fileName);
    }

    /**
     * @param string $fileName
     *
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\YamlBatchIterator
     */
    protected function getBatchIterator(string $fileName): YamlBatchIterator
    {
        $yamlReader = new YamlReaderBridge(new Yaml());
        $yamlBatchIterator = new YamlBatchIterator($yamlReader, $fileName);

        return $yamlBatchIterator;
    }
}
