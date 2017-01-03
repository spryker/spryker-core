<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Development\Business\DependencyTree\DependencyFinder;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\ExternalDependency;
use Spryker\Zed\Development\Business\DependencyTree\DependencyTree;
use Spryker\Zed\Development\Business\DependencyTree\FileInfoExtractor;
use Symfony\Component\Finder\Finder;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Development
 * @group Business
 * @group DependencyTree
 * @group DependencyFinder
 * @group ExternalDependencyTest
 */
class ExternalDependencyTest extends PHPUnit_Framework_TestCase
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
        $testFile = $this->getTestFile();
        $dependencyFinder = new ExternalDependency();
        $dependencyTree = new DependencyTree(new FileInfoExtractor(), []);
        $dependencyFinder->setDependencyTree($dependencyTree);
        $dependencyFinder->addDependencies($testFile);

        $dependencyTree = $dependencyFinder->getDependencyTree()->getDependencyTree();

        $this->assertCount(6, $dependencyTree);
    }

}
