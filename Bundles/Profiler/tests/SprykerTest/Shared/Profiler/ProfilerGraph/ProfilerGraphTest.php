<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Profiler\ProfilerGraph;

use Codeception\Test\Unit;
use Spryker\Shared\Profiler\ProfilerGraph\ProfilerGraphNode;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Profiler
 * @group ProfilerGraph
 * @group ProfilerGraphTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Shared\Profiler\ProfilerTester $tester
 */
class ProfilerGraphTest extends Unit
{
    /**
     * @return void
     */
    public function testBuildProfilerGraphWhenAddTheEdges(): void
    {
        // Arrange
        $profilerGraph = $this->tester->createProfilerGraph();

        // Act
        $profilerGraph->addGraphEdge('A', 'B');
        $profilerGraph->addGraphEdge('B', 'C');
        $profilerGraph->addGraphEdge('A', 'C');

        // Assert
        $aNode = $profilerGraph->findNodeByName('A');
        $bNode = $profilerGraph->findNodeByName('B');
        $cNode = $profilerGraph->findNodeByName('C');

        $this->assertSame([], $aNode->getFromNodes());
        $this->assertSame([$bNode, $cNode], $aNode->getToNodes());

        $this->assertSame([$aNode], $bNode->getFromNodes());
        $this->assertSame([$cNode], $bNode->getToNodes());

        $this->assertSame([$bNode, $aNode], $cNode->getFromNodes());
        $this->assertSame([], $cNode->getToNodes());
    }

    /**
     * @return void
     */
    public function testBuildSingleNodesProfilerGraphWhenDuplicatedEdges(): void
    {
        // Arrange
        $profilerGraph = $this->tester->createProfilerGraph();

        // Act
        $profilerGraph->addGraphEdge('A', 'B');
        $profilerGraph->addGraphEdge('B', 'C');
        $profilerGraph->addGraphEdge('B', 'C');
        $profilerGraph->addGraphEdge('B', 'C');
        $profilerGraph->addGraphEdge('B', 'C');

        // Assert
        $aNode = $profilerGraph->findNodeByName('A');
        $bNode = $profilerGraph->findNodeByName('B');
        $cNode = $profilerGraph->findNodeByName('C');

        $this->assertSame([], $aNode->getFromNodes());
        $this->assertSame([$bNode], $aNode->getToNodes());

        $this->assertSame([$aNode], $bNode->getFromNodes());
        $this->assertSame([$cNode], $bNode->getToNodes());
    }

    /**
     * @return void
     */
    public function testRestructureProfilerGraphWhenNodeFiltered(): void
    {
        // Arrange
        $profilerGraph = $this->tester->createProfilerGraph();

        // Act
        $profilerGraph->addGraphEdge('A', 'B');
        $profilerGraph->addGraphEdge('B', 'C');
        $profilerGraph->addGraphEdge('B', 'D');
        $profilerGraph->addGraphEdge('D', 'A');

        $filterCondition = static fn (ProfilerGraphNode $node) => $node->getName() !== 'B';

        $profilerGraph->filterGraph($this->tester->getProfilerGraphNotBFilterCondition($filterCondition));

        // Assert
        $aNode = $profilerGraph->findNodeByName('A');
        $cNode = $profilerGraph->findNodeByName('C');
        $dNode = $profilerGraph->findNodeByName('D');

        $this->assertSame([$dNode], $aNode->getFromNodes());
        $this->assertSame([$cNode, $dNode], $aNode->getToNodes());

        $this->assertSame([$aNode], $cNode->getFromNodes());
        $this->assertSame([], $cNode->getToNodes());

        $this->assertSame([$aNode], $dNode->getFromNodes());
        $this->assertSame([$aNode], $dNode->getToNodes());
    }
}
