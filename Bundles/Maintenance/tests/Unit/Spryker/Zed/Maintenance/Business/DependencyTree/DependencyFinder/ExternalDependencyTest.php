<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */


namespace Unit\Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\ExternalDependency;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;
use Spryker\Zed\Maintenance\Business\DependencyTree\FileInfoExtractor;
use Symfony\Component\Finder\Finder;

/**
 * @group Spryker
 * @group Zed
 * @group Maintenance
 * @group Business
 * @group DependencyTree
 */
class ExternalDependencyTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return \Symfony\Component\Finder\SplFileInfo
     */
    public function getTestFile()
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__ . '/Fixtures');

        foreach ($finder as $item) {
            return $item;
        }

        return false;
    }

    /**
     * @return void
     */
    public function testAddDependency()
    {
        $this->markTestSkipped('PHP_CodeSniffer_File is adding codeception autoloader for what ever reason');

        $testFile = $this->getTestFile();
        $dependencyFinder = new ExternalDependency();
        $dependencyTree = new DependencyTree(new FileInfoExtractor(), []);
        $dependencyFinder->setDependencyTree($dependencyTree);
        $dependencyFinder->addDependencies($testFile);

        $dependencyTree = $dependencyFinder->getDependencyTree()->getDependencyTree();

        $this->assertCount(6, $dependencyTree);
    }
}
