<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Business\DependencyTree\DependencyFinder;

use Codeception\Test\Unit;
use RuntimeException;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFinder\ExternalDependency;
use Spryker\Zed\Development\Business\DependencyTree\DependencyTree;
use Spryker\Zed\Development\Business\DependencyTree\FileInfoExtractor;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Finder\Finder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Development
 * @group Business
 * @group DependencyTree
 * @group DependencyFinder
 * @group ExternalDependencyTest
 * Add your own group annotations below this line
 */
class ExternalDependencyTest extends Unit
{
    /**
     * @throws \RuntimeException
     *
     * @return \Symfony\Component\Finder\SplFileInfo
     */
    public function getTestFile()
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__ . '/test_files');

        foreach ($finder as $item) {
            return $item;
        }

        throw new RuntimeException('Not Found');
    }

    /**
     * @return void
     */
    public function testAddDependency()
    {
        $developmentConfig = new DevelopmentConfig();
        $testFile = $this->getTestFile();
        $dependencyFinder = new ExternalDependency($developmentConfig->getExternalToInternalNamespaceMap());
        $dependencyTree = new DependencyTree(new FileInfoExtractor(), []);
        $dependencyFinder->setDependencyTree($dependencyTree);
        $dependencyFinder->addDependencies($testFile);

        $dependencyTree = $dependencyFinder->getDependencyTree()->getDependencyTree();

        $this->assertCount(5, $dependencyTree);
    }
}
