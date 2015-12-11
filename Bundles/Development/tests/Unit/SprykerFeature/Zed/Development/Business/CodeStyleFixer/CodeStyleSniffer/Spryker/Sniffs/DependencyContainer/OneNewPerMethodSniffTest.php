<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Development\Business\CodeStyleSniffer\Spryker\Sniffs\DependencyContainer;

use Spryker\Sniffs\DependencyContainer\OneNewPerMethodSniff;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Development
 * @group Business
 * @group OneNewPerMethodSniff
 */
class OneNewPerMethodSniffTest extends \PHPUnit_Framework_TestCase
{

    public function testSniff()
    {
        $this->markTestSkipped('Find a way to run tests for Sniffs');
        $sniffer = $this->getSniffer();

        $phpCsFixer = new \PHP_CodeSniffer();
    }

    /**
     * @return string
     */
    private function getInValidTestFile()
    {
        $fixtureDirectory = $this->getFixtureDirectory();

        return $fixtureDirectory . '/InValidDependencyContainer.php';
    }

    /**
     * @return string
     */
    private function getFixtureDirectory()
    {
        return __DIR__ . '/Fixtures';
    }

    /**
     * @return OneNewPerMethodSniff
     */
    protected function getSniffer()
    {
        $sniffer = new OneNewPerMethodSniff();

        return $sniffer;
    }

}
