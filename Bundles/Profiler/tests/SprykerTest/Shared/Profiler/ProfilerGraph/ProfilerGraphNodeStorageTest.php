<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Profiler\ProfilerGraph;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Profiler
 * @group ProfilerGraph
 * @group ProfilerGraphNodeStorageTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Shared\Profiler\ProfilerTester $tester
 */
class ProfilerGraphNodeStorageTest extends Unit
{
    /**
     * @return void
     */
    public function testNodeStorageStoreNodesWhenNodesAreAdded(): void
    {
        // Arrange
        $aNode = $this->tester->createProfilerGraphNode('A');
        $bNode = $this->tester->createProfilerGraphNode('B');
        $cNode = $this->tester->createProfilerGraphNode('C');

        $profilerGraphNodeStorage = $this->tester->createProfilerGraphNodeStorage();

        // Act
        $profilerGraphNodeStorage->addNode($aNode);
        $profilerGraphNodeStorage->addNode($bNode);
        $profilerGraphNodeStorage->addNode($cNode);

        // Assert
        $this->assertSame($aNode, $profilerGraphNodeStorage->findNodeByName('A'));
        $this->assertSame($bNode, $profilerGraphNodeStorage->findNodeByName('B'));
        $this->assertSame($cNode, $profilerGraphNodeStorage->findNodeByName('C'));
    }

    /**
     * @return void
     */
    public function testNodeStorageStoreOnlyOneInstanceNodeWhenNodeIsAddedTwice(): void
    {
        // Arrange
        $aOneNode = $this->tester->createProfilerGraphNode('A');
        $aTwoNode = $this->tester->createProfilerGraphNode('A');

        $profilerGraphNodeStorage = $this->tester->createProfilerGraphNodeStorage();

        // Act
        $profilerGraphNodeStorage->addNode($aOneNode);
        $profilerGraphNodeStorage->addNode($aTwoNode);

        // Assert
        $this->assertSame(['A' => $aOneNode], $profilerGraphNodeStorage->getNodes());
    }

    /**
     * @return void
     */
    public function testNodeStorageRemoveNodeWhenNodeIsAdded(): void
    {
        // Arrange
        $aNode = $this->tester->createProfilerGraphNode('A');
        $bNode = $this->tester->createProfilerGraphNode('B');

        $profilerGraphNodeStorage = $this->tester->createProfilerGraphNodeStorage();
        $profilerGraphNodeStorage->addNode($aNode);
        $profilerGraphNodeStorage->addNode($bNode);

        // Act
        $profilerGraphNodeStorage->removeNode($bNode);

        // Assert
        $this->assertSame(['A' => $aNode], $profilerGraphNodeStorage->getNodes());
    }
}
