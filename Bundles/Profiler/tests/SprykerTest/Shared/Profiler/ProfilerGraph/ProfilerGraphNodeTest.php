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
 * @group ProfilerGraphNodeTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Shared\Profiler\ProfilerTester $tester
 */
class ProfilerGraphNodeTest extends Unit
{
    /**
     * @return void
     */
    public function testNodeHasValidNodeLinksWhenNewLinksAreAdded(): void
    {
        // Arrange
        $aNode = $this->tester->createProfilerGraphNode('A');
        $bNode = $this->tester->createProfilerGraphNode('B');
        $cNode = $this->tester->createProfilerGraphNode('C');
        $dNode = $this->tester->createProfilerGraphNode('D');

        // Act
        $aNode->addToNode($bNode);
        $aNode->addToNode($cNode);
        $bNode->addToNode($dNode);

        // Assert
        $this->assertSame([], $aNode->getFromNodes());
        $this->assertSame([$bNode, $cNode], $aNode->getToNodes());

        $this->assertSame([$aNode], $bNode->getFromNodes());
        $this->assertSame([$dNode], $bNode->getToNodes());

        $this->assertSame([$aNode], $cNode->getFromNodes());
        $this->assertSame([], $cNode->getToNodes());

        $this->assertSame([$bNode], $dNode->getFromNodes());
        $this->assertSame([], $dNode->getToNodes());
    }

    /**
     * @return void
     */
    public function testRearrangeRelatedNodesWhenNodeIsRemoved(): void
    {
        // Arrange
        $aNode = $this->tester->createProfilerGraphNode('A');
        $bNode = $this->tester->createProfilerGraphNode('B');
        $cNode = $this->tester->createProfilerGraphNode('C');
        $dNode = $this->tester->createProfilerGraphNode('D');

        $aNode->addToNode($bNode);
        $bNode->addToNode($cNode);
        $bNode->addToNode($dNode);
        $dNode->addToNode($aNode);

        // Act
        $bNode->remove();

        // Assert
        $this->assertSame([$dNode], $aNode->getFromNodes());
        $this->assertSame([$cNode, $dNode], $aNode->getToNodes());

        $this->assertSame([$aNode], $cNode->getFromNodes());
        $this->assertSame([], $cNode->getToNodes());

        $this->assertSame([$aNode], $dNode->getFromNodes());
        $this->assertSame([$aNode], $dNode->getToNodes());
    }
}
