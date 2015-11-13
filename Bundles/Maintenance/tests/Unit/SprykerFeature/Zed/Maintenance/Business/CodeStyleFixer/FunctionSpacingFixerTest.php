<?php

namespace Unit\SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer;

use SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer\FunctionSpacingFixer;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Maintenance
 * @group CodeStyleFixer
 */
class FunctionSpacingFixerTest extends \PHPUnit_Framework_TestCase
{

    const FIXER_NAME = 'FunctionSpacingFixer';

    /**
     * @var FunctionSpacingFixer
     */
    protected $fixer;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->fixer = new FunctionSpacingFixer();
    }

    /**
     * @dataProvider provideFixCases
     *
     * @param string $expected
     * @param string $input
     *
     * @return void
     */
    public function testFix($expected, $input = null)
    {
        $fileInfo = new \SplFileInfo(__FILE__);
        $this->assertSame($expected, $this->fixer->fix($fileInfo, $input));
    }

    /**
     * @return array
     */
    public function provideFixCases()
    {
        $fixturePath = __DIR__ . '/Fixtures/' . self::FIXER_NAME . '/';

        return [
            [
                file_get_contents($fixturePath . 'Expected/TestClass1Expected.php'),
                file_get_contents($fixturePath . 'Input/TestClass1Input.php'),
            ],
            [
                file_get_contents($fixturePath . 'Expected/TestClass2Expected.php'),
                file_get_contents($fixturePath . 'Input/TestClass2Input.php'),
            ],
        ];
    }

}
