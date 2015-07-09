<?php

namespace Unit\SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer;

use SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer\EmptyEnclosingLinesFixer;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Maintenance
 * @group CodeStyleFixer
 */
class EmptyEnclosingLinesFixerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var EmptyEnclosingLinesFixer
     */
    protected $fixer;

    protected function setUp()
    {
        parent::setUp();
        $this->fixer = new EmptyEnclosingLinesFixer();
    }

    /**
     * @dataProvider provideFixCases
     *
     * @param string $expected
     * @param string $input
     */
    public function testFix($expected, $input = null)
    {
        $fileInfo = new \SplFileInfo(__FILE__);
        $this->assertSame($expected, $this->fixer->fix($fileInfo, $input));
    }

    public function provideFixCases()
    {
        $fixturePath = __DIR__ . '/Fixtures/';

        return [
            [
                file_get_contents($fixturePath . '/Expected/TestClass1Expected.php'),
                file_get_contents($fixturePath . '/Input/TestClass1Input.php'),
            ],
            [
                file_get_contents($fixturePath . '/Expected/TestClass2Expected.php'),
                file_get_contents($fixturePath . '/Input/TestClass2Input.php'),
            ],
            [
                file_get_contents($fixturePath . '/Expected/TestClass3Expected.php'),
                file_get_contents($fixturePath . '/Input/TestClass3Input.php'),
            ],
            [
                file_get_contents($fixturePath . '/Expected/TestClass4Expected.php'),
                file_get_contents($fixturePath . '/Input/TestClass4Input.php'),
            ],
            [
                file_get_contents($fixturePath . '/Expected/TestClass5Expected.php'),
                file_get_contents($fixturePath . '/Input/TestClass5Input.php'),
            ],
        ];
    }

}
