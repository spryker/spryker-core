<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Library\Writer\Csv;

use Spryker\Shared\Library\Writer\Csv\CsvWriter;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @group Writer
 */
class CsvWriterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var string
     */
    protected $fixtureDirectory;

    const TEST_DATA = [
        ['first'=>1, 'second'=>2, 'third'=>3],
        ['first'=>'A string with "spaces"', 'second'=>null, 'third'=>9],
    ];

    const TEST_CONTENTS = "first,second,third\n1,2,3\n\"A string with \"\"spaces\"\"\",,9\n";

    const TEST_CONTENTS_FORMATTED = "first|second|third\n1|2|3\n'A string with \"spaces\"'||9\n";

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
        $filename = $directory . DIRECTORY_SEPARATOR . 'bar';

        $writer = new CsvWriter($filename);
        $writer->write(self::TEST_DATA);

        $this->assertFileExists($filename);
    }

    /**
     * @return void
     */
    public function testCsvWriterFileContents()
    {
        $directory = $this->fixtureDirectory . DIRECTORY_SEPARATOR . 'Foo';
        $filename = $directory . DIRECTORY_SEPARATOR . 'bar1';

        $writer = new CsvWriter($filename);
        $writer->write(self::TEST_DATA);

        $this->assertStringEqualsFile($filename, self::TEST_CONTENTS);
    }

    /**
     * @return void
     */
    public function testCsvWriterPartialInserts()
    {
        $directory = $this->fixtureDirectory . DIRECTORY_SEPARATOR . 'Foo';
        $filename = $directory . DIRECTORY_SEPARATOR . 'bar2';

        $writer = new CsvWriter($filename);
        foreach (self::TEST_DATA as $data) {
            $writer->write([$data]);
        }

        $this->assertStringEqualsFile($filename, self::TEST_CONTENTS);
    }

    /**
     * @return void
     */
    public function testCsvWriterFileFormattedContents()
    {
        $directory = $this->fixtureDirectory . DIRECTORY_SEPARATOR . 'Foo';
        $filename = $directory . DIRECTORY_SEPARATOR . 'bar3';

        $writer = new CsvWriter($filename);
        $writer->setCsvFormat("|", "'");
        $writer->write(self::TEST_DATA);

        $this->assertStringEqualsFile($filename, self::TEST_CONTENTS_FORMATTED);
    }

}
