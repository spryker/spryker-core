<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Collector\Business\Exporter\Writer\File;

use Spryker\Zed\Collector\Business\Exporter\Writer\File\Adapter\CsvAdapter;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @group Spryker
 * @group Zed
 * @group Collector
 * @group Business
 * @group CsvWriterAdapter
 */
class CsvWriterAdapterTest extends \PHPUnit_Framework_TestCase
{

    const TEST_DATA = [
        ['first' => 1, 'second' => 2, 'third' => 3],
        ['first' => 'A string with "spaces"', 'second' => null, 'third' => 9],
    ];

    const TEST_CONTENTS = "first,second,third\n1,2,3\n\"A string with \"\"spaces\"\"\",,9\n";

    const TEST_CONTENTS_FORMATTED = "first|second|third\n1|2|3\n'A string with \"spaces\"'||9\n";

    /**
     * @var string
     */
    protected $fixtureDirectory;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->fixtureDirectory = __DIR__ . '/Fixtures';
        $directory = $this->fixtureDirectory . DIRECTORY_SEPARATOR . 'Foo';
        mkdir($directory, 0775, true);
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->fixtureDirectory);
    }

    /**
     * @return void
     */
    public function testCsvWriterShouldCreateNewFile()
    {
        $directory = $this->fixtureDirectory . DIRECTORY_SEPARATOR . 'Foo';
        $filename = 'bar';

        $writer = new CsvAdapter($directory);
        $writer->setFileName($filename);
        $writer->write(self::TEST_DATA);

        $this->assertFileExists($directory . DIRECTORY_SEPARATOR . $filename);
    }

    /**
     * @return void
     */
    public function testCsvWriterFileContents()
    {
        $directory = $this->fixtureDirectory . DIRECTORY_SEPARATOR . 'Foo';
        $filename = 'bar1';

        $writer = new CsvAdapter($directory);
        $writer->setFileName($filename);
        $writer->write(self::TEST_DATA);

        $this->assertStringEqualsFile($directory . DIRECTORY_SEPARATOR . $filename, self::TEST_CONTENTS);
    }

    /**
     * @return void
     */
    public function testCsvWriterPartialInserts()
    {
        $directory = $this->fixtureDirectory . DIRECTORY_SEPARATOR . 'Foo';
        $filename = 'bar2';

        $writer = new CsvAdapter($directory);
        $writer->setFileName($filename);
        foreach (self::TEST_DATA as $data) {
            $writer->write([$data]);
        }

        $this->assertStringEqualsFile($directory . DIRECTORY_SEPARATOR . $filename, self::TEST_CONTENTS);
    }

    /**
     * @return void
     */
    public function testCsvWriterFileFormattedContents()
    {
        $directory = $this->fixtureDirectory . DIRECTORY_SEPARATOR . 'Foo';
        $filename = 'bar3';

        $writer = new CsvAdapter($directory, '|', '\'');
        $writer->setFileName($filename);
        $writer->write(self::TEST_DATA);

        $this->assertStringEqualsFile($directory . DIRECTORY_SEPARATOR . $filename, self::TEST_CONTENTS_FORMATTED);
    }

}
