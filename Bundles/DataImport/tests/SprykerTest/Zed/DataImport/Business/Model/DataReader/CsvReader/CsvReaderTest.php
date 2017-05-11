<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business\Model\DataReader\CsvReader;

use Codeception\Configuration;
use Codeception\TestCase\Test;
use Countable;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataReaderException;
use Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader\CsvReader;
use Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader\CsvReaderConfiguration;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group DataReader
 * @group CsvReader
 * @group CsvReaderTest
 * Add your own group annotations below this line
 */
class CsvReaderTest extends Test
{

    const EXPECTED_NUMBER_OF_DATA_SETS_IN_CSV = 3;
    const EXPECTED_NUMBER_OF_COLUMNS_IN_DATA_SET = 3;

    /**
     * @var \SprykerTest\Zed\DataImport\BusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testDataReaderCanBeUsedAsIteratorAndReturnsArrayObject()
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-standard.csv');
        foreach ($csvReader as $dataSet) {
            $this->assertInstanceOf(DataSet::class, $dataSet);
        }
    }

    /**
     * @return void
     */
    public function testReaderIsCountable()
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-standard.csv');
        $this->assertInstanceOf(Countable::class, $csvReader);
    }

    /**
     * @return void
     */
    public function testDataReaderCountWithColumnHeader()
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-standard.csv');
        $this->tester->assertDataSetCount(static::EXPECTED_NUMBER_OF_DATA_SETS_IN_CSV, $csvReader);
    }

    /**
     * @return void
     */
    public function testDataReaderCountWithoutColumnHeader()
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-without-header.csv', false);
        $this->tester->assertDataSetCount(static::EXPECTED_NUMBER_OF_DATA_SETS_IN_CSV, $csvReader);
    }

    /**
     * @return void
     */
    protected function testKeyReturnsCurrentDataRowNumber()
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-standard.csv');
        $this->assertInternalType('int', $csvReader->key());
    }

    /**
     * @return void
     */
    public function testDataReaderCanBeConfiguredToUseNewFileAfterInstantiation()
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-semicolon-delimiter.csv');
        $dataImportReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImportReaderConfigurationTransfer
            ->setFileName(Configuration::dataDir() . 'import-standard.csv');

        $csvReader->configure($dataImportReaderConfigurationTransfer);
        $currentRow = $csvReader->current();

        $this->tester->assertDataSetWithKeys(1, $currentRow);
    }

    /**
     * @return void
     */
    public function testDataReaderCanBeConfiguredToUseNewFileAndCsvControlAfterInstantiation()
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-standard.csv');
        $dataImportReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImportReaderConfigurationTransfer
            ->setFileName(Configuration::dataDir() . 'import-semicolon-delimiter.csv')
            ->setCsvDelimiter(';')
            ->setCsvEnclosure(CsvReaderConfiguration::DEFAULT_ENCLOSURE)
            ->setCsvEscape(CsvReaderConfiguration::DEFAULT_ESCAPE)
            ->setCsvFlags(CsvReaderConfiguration::DEFAULT_FLAGS)
            ->setCsvHasHeader(CsvReaderConfiguration::DEFAULT_HAS_HEADER);

        $csvReader->configure($dataImportReaderConfigurationTransfer);
        $currentRow = $csvReader->current();

        $this->tester->assertDataSetWithKeys(1, $currentRow);
    }

    /**
     * @return void
     */
    public function testEachDataSetShouldHaveCsvColumnNamesAsKeys()
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-standard.csv');

        $firstRow = $csvReader->current();
        $this->tester->assertDataSetWithKeys(1, $firstRow);
        $csvReader->next();

        $secondRow = $csvReader->current();
        $this->tester->assertDataSetWithKeys(2, $secondRow);
        $csvReader->next();

        $thirdRow = $csvReader->current();
        $this->tester->assertDataSetWithKeys(3, $thirdRow);
    }

    /**
     * @return void
     */
    public function testEachDataSetShouldNotHaveCsvColumnNamesAsKeys()
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-without-header.csv', false);

        $firstRow = $csvReader->current();
        $this->tester->assertDataSetWithoutKeys(1, $firstRow);
        $csvReader->next();

        $secondRow = $csvReader->current();
        $this->tester->assertDataSetWithoutKeys(2, $secondRow);
        $csvReader->next();

        $thirdRow = $csvReader->current();
        $this->tester->assertDataSetWithoutKeys(3, $thirdRow);
    }

    /**
     * @return void
     */
    public function testKeyReturnsCurrentDataSetPosition()
    {
        $csvReader = $this->getCsvReader(Configuration::dataDir() . 'import-standard.csv');
        $this->assertInternalType('int', $csvReader->key());
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenFileInvalid()
    {
        $this->expectException(DataReaderException::class);
        $configuration = $this->getCsvReaderConfiguration(Configuration::dataDir() . 'not-existing.csv');
        $dataSet = new DataSet();

        $reader = new CsvReader($configuration, $dataSet);
    }

    /**
     * @param string $fileName
     * @param bool $hasHeader
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader\CsvReader|\Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface
     */
    protected function getCsvReader($fileName, $hasHeader = true)
    {
        $configuration = $this->getCsvReaderConfiguration($fileName, $hasHeader);
        $dataSet = new DataSet();
        $csvReader = new CsvReader($configuration, $dataSet);

        return $csvReader;
    }

    /**
     * @param string $fileName
     * @param bool $hasHeader
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataReader\CsvReader\CsvReaderConfigurationInterface
     */
    protected function getCsvReaderConfiguration($fileName, $hasHeader = true)
    {
        $dataImporterReaderConfiguration = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfiguration
            ->setFileName($fileName)
            ->setCsvHasHeader($hasHeader);

        return new CsvReaderConfiguration($dataImporterReaderConfiguration);
    }

}
