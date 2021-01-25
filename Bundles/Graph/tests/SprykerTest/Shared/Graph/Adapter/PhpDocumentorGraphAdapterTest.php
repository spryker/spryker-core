<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Graph\Adapter;

use Codeception\Test\Unit;
use Spryker\Shared\Graph\Adapter\PhpDocumentorGraphAdapter;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Graph
 * @group Adapter
 * @group PhpDocumentorGraphAdapterTest
 * Add your own group annotations below this line
 */
class PhpDocumentorGraphAdapterTest extends Unit
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
    public function testCreate(): void
    {
        $this->assertInstanceOf(PhpDocumentorGraphAdapter::class, $this->getAdapter()->create(self::GRAPH_NAME));
    }

    /**
     * @return void
     */
    public function testCreateWithAttributes(): void
    {
        $this->assertInstanceOf(PhpDocumentorGraphAdapter::class, $this->getAdapter()->create(self::GRAPH_NAME, self::ATTRIBUTES));
    }

    /**
     * @return void
     */
    public function testCreateUnDirectedGraph(): void
    {
        $this->assertInstanceOf(PhpDocumentorGraphAdapter::class, $this->getAdapter()->create(self::GRAPH_NAME, [], false));
    }

    /**
     * @return void
     */
    public function testCreateTolerantGraph(): void
    {
        $this->assertInstanceOf(PhpDocumentorGraphAdapter::class, $this->getAdapter()->create(self::GRAPH_NAME, [], true, false));
    }

    /**
     * @return void
     */
    public function testAddNode(): void
    {
        $this->assertInstanceOf(PhpDocumentorGraphAdapter::class, $this->getGraph()->addNode(self::NODE_A));
    }

    /**
     * @return void
     */
    public function testAddNodeWithAttributes(): void
    {
        $this->assertInstanceOf(PhpDocumentorGraphAdapter::class, $this->getGraph()->addNode(self::NODE_A, self::ATTRIBUTES));
    }

    /**
     * @return void
     */
    public function testAddNodeWithGroup(): void
    {
        $this->assertInstanceOf(PhpDocumentorGraphAdapter::class, $this->getGraph()->addNode(self::NODE_A, [], self::GROUP_NAME));
    }

    /**
     * @return void
     */
    public function testAddEdge(): void
    {
        $adapter = $this->getGraph();
        $adapter->addNode(self::NODE_A);
        $adapter->addNode(self::NODE_B);

        $this->assertInstanceOf(PhpDocumentorGraphAdapter::class, $adapter->addEdge(self::NODE_A, self::NODE_B));
    }

    /**
     * @return void
     */
    public function testAddEdgeWithAttributes(): void
    {
        $adapter = $this->getGraph();
        $adapter->addNode(self::NODE_A);
        $adapter->addNode(self::NODE_B);

        $this->assertInstanceOf(PhpDocumentorGraphAdapter::class, $adapter->addEdge(self::NODE_A, self::NODE_B, self::ATTRIBUTES));
    }

    /**
     * @return void
     */
    public function testAddCluster(): void
    {
        $this->assertInstanceOf(PhpDocumentorGraphAdapter::class, $this->getGraph()->addCluster(self::CLUSTER_NAME));
    }

    /**
     * @return void
     */
    public function testAddClusterWithAttributes(): void
    {
        $this->assertInstanceOf(PhpDocumentorGraphAdapter::class, $this->getGraph()->addCluster(self::CLUSTER_NAME, self::ATTRIBUTES));
    }

    /**
     * @return void
     */
    public function testRender(): void
    {
        $adapter = new PhpDocumentorGraphAdapter();
        $adapter->create(self::GRAPH_NAME);

        $this->assertIsString($adapter->render('svg'));
    }

    /**
     * @return void
     */
    public function testRenderWithFileName(): void
    {
        $adapter = new PhpDocumentorGraphAdapter();
        $adapter->create(self::GRAPH_NAME);

        $this->assertIsString($adapter->render('svg', sys_get_temp_dir() . '/filename'));
    }

    /**
     * @return \Spryker\Shared\Graph\Adapter\PhpDocumentorGraphAdapter
     */
    private function getAdapter(): PhpDocumentorGraphAdapter
    {
        $adapter = new PhpDocumentorGraphAdapter();

        return $adapter;
    }

    /**
     * @return \Spryker\Shared\Graph\Adapter\PhpDocumentorGraphAdapter
     */
    private function getGraph(): PhpDocumentorGraphAdapter
    {
        return $this->getAdapter()->create(self::GRAPH_NAME);
    }
}
