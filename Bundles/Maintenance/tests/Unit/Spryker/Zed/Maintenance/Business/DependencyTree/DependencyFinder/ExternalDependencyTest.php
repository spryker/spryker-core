<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */


namespace Unit\Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\ExternalDependency;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;
use Spryker\Zed\Maintenance\Business\DependencyTree\FileInfoExtractor;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

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
     * @return SplFileInfo
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
        $testFile = $this->getTestFile();
        $dependencyFinder = new ExternalDependency();
        $dependencyTree = new DependencyTree(new FileInfoExtractor(), []);
        $dependencyFinder->setDependencyTree($dependencyTree);
        $dependencyFinder->addDependencies($testFile);

        $this->assertCount(5, $dependencyFinder->getDependencyTree()->getDependencyTree());
    }
}
