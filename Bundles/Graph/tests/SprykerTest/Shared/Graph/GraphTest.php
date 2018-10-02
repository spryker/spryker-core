<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Graph;

use Codeception\Test\Unit;
use Spryker\Shared\Graph\Graph;
use Spryker\Shared\Graph\GraphAdapterInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Shared
 * @group Graph
 * @group GraphTest
 * Add your own group annotations below this line
 */
class GraphTest extends Unit
{
    public const GRAPH_NAME = 'graph name';
    public const NODE_A = 'node A';
    public const NODE_B = 'node B';
    public const GROUP_NAME = 'group name';
    public const CLUSTER_NAME = 'cluster name';
    public const ATTRIBUTES = ['attribute' => 'value', 'html attribute' => '<h1>Html Value</h1>'];

    /**
     * @return void
     */
    public function testCreateInstance()
    {
        $this->assertInstanceOf(Graph::class, $this->getGraph(self::GRAPH_NAME));
    }

    /**
     * @return void
     */
    public function testCreateInstanceWithAttributes()
    {
        $this->assertInstanceOf(Graph::class, $this->getGraph(self::GRAPH_NAME, self::ATTRIBUTES));
    }

    /**
     * @return void
     */
    public function testCreateInstanceUnDirectedGraph()
    {
        $this->assertInstanceOf(Graph::class, $this->getGraph(self::GRAPH_NAME, [], false));
    }

    /**
     * @return void
     */
    public function testCreateInstanceTolerantGraph()
    {
        $this->assertInstanceOf(Graph::class, $this->getGraph(self::GRAPH_NAME, [], true, false));
    }

    /**
     * @return void
     */
    public function testAddNode()
    {
        $this->assertInstanceOf(Graph::class, $this->getGraph()->addNode(self::NODE_A));
    }

    /**
     * @return void
     */
    public function testAddNodeWithAttributes()
    {
        $this->assertInstanceOf(Graph::class, $this->getGraph()->addNode(self::NODE_A, self::ATTRIBUTES));
    }

    /**
     * @return void
     */
    public function testAddNodeWithGroup()
    {
        $this->assertInstanceOf(Graph::class, $this->getGraph()->addNode(self::NODE_A, [], self::GROUP_NAME));
    }

    /**
     * @return void
     */
    public function testAddEdge()
    {
        $adapter = $this->getGraphWithNodes();

        $this->assertInstanceOf(Graph::class, $adapter->addEdge(self::NODE_A, self::NODE_B));
    }

    /**
     * @return void
     */
    public function testAddEdgeWithAttributes()
    {
        $adapter = $this->getGraphWithNodes();

        $this->assertInstanceOf(Graph::class, $adapter->addEdge(self::NODE_A, self::NODE_B, self::ATTRIBUTES));
    }

    /**
     * @return void
     */
    public function testAddCluster()
    {
        $this->assertInstanceOf(Graph::class, $this->getGraph()->addCluster(self::CLUSTER_NAME));
    }

    /**
     * @return void
     */
    public function testAddClusterWithAttributes()
    {
        $this->assertInstanceOf(Graph::class, $this->getGraph()->addCluster(self::CLUSTER_NAME, self::ATTRIBUTES));
    }

    /**
     * @return void
     */
    public function testRender()
    {
        $this->assertInternalType('string', $this->getGraph()->render('svg'));
    }

    /**
     * @return void
     */
    public function testRenderWithFileName()
    {
        $this->assertInternalType('string', $this->getGraph()->render('svg', 'filename'));
    }

    /**
     * @param string $name
     * @param array $attributes
     * @param bool $directed
     * @param bool $strict
     *
     * @return \Spryker\Shared\Graph\Graph
     */
    private function getGraph($name = self::GRAPH_NAME, array $attributes = [], $directed = true, $strict = true)
    {
        $adapterMock = $this->createAdapterMock();

        return new Graph($adapterMock, $name, $attributes, $directed, $strict);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\Graph\GraphAdapterInterface
     */
    private function createAdapterMock()
    {
        $adapterMock = $this->getMockBuilder(GraphAdapterInterface::class)->setMethods(['create', 'addNode', 'addEdge', 'addCluster', 'render'])->getMock();
        $adapterMock->method('render')->willReturn('');

        return $adapterMock;
    }

    /**
     * @return \Spryker\Shared\Graph\Graph
     */
    private function getGraphWithNodes()
    {
        $adapter = $this->getGraph();
        $adapter->addNode(self::NODE_A);
        $adapter->addNode(self::NODE_B);

        return $adapter;
    }
}
