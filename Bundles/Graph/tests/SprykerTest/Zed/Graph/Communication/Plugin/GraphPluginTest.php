<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Graph\Communication\Plugin;

use Codeception\Test\Unit;
use Spryker\Shared\Graph\GraphInterface;
use Spryker\Zed\Graph\Communication\Exception\GraphNotInitializedException;
use Spryker\Zed\Graph\Communication\GraphCommunicationFactory;
use Spryker\Zed\Graph\Communication\Plugin\GraphPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Graph
 * @group Communication
 * @group Plugin
 * @group GraphPluginTest
 * Add your own group annotations below this line
 */
class GraphPluginTest extends Unit
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
    public function testGetGraphMustThrowExceptionIfGraphWasNotInitialized()
    {
        $this->expectException(GraphNotInitializedException::class);

        $graphPlugin = new GraphPlugin();
        $this->assertInstanceOf(GraphPlugin::class, $graphPlugin->addNode(self::NODE_A));
    }

    /**
     * @return void
     */
    public function testInit()
    {
        $graphMock = $this->getMockBuilder(GraphInterface::class)->setMethods(['create', 'addNode', 'addEdge', 'addCluster', 'render'])->getMock();
        $graphMock->method('render')->willReturn('');

        $factoryMock = $this->getMockBuilder(GraphCommunicationFactory::class)->getMock();
        $factoryMock->method('createGraph')->willReturn($graphMock);

        $pluginMock = $this->getMockBuilder(GraphPlugin::class)->setMethods(['getFactory'])->setConstructorArgs(['name'])->disableOriginalConstructor()->getMock();
        $pluginMock->method('getFactory')->willReturn($factoryMock);

        $this->assertInstanceOf(GraphPlugin::class, $this->getPluginMock()->init(self::GRAPH_NAME));
    }

    /**
     * @return void
     */
    public function testAddNode()
    {
        $this->assertInstanceOf(GraphPlugin::class, $this->getPluginMock()->addNode(self::NODE_A));
    }

    /**
     * @return void
     */
    public function testAddNodeWithAttributes()
    {
        $this->assertInstanceOf(GraphPlugin::class, $this->getPluginMock()->addNode(self::NODE_A, self::ATTRIBUTES));
    }

    /**
     * @return void
     */
    public function testAddNodeWithGroup()
    {
        $this->assertInstanceOf(GraphPlugin::class, $this->getPluginMock()->addNode(self::NODE_A, [], self::GROUP_NAME));
    }

    /**
     * @return void
     */
    public function testAddEdge()
    {
        $this->assertInstanceOf(GraphPlugin::class, $this->getPluginMock()->addEdge(self::NODE_A, self::NODE_B));
    }

    /**
     * @return void
     */
    public function testAddEdgeWithAttributes()
    {
        $this->assertInstanceOf(GraphPlugin::class, $this->getPluginMock()->addEdge(self::NODE_A, self::NODE_B, self::ATTRIBUTES));
    }

    /**
     * @return void
     */
    public function testAddCluster()
    {
        $this->assertInstanceOf(GraphPlugin::class, $this->getPluginMock()->addCluster(self::CLUSTER_NAME));
    }

    /**
     * @return void
     */
    public function testAddClusterWithAttributes()
    {
        $this->assertInstanceOf(GraphPlugin::class, $this->getPluginMock()->addCluster(self::CLUSTER_NAME, self::ATTRIBUTES));
    }

    /**
     * @return void
     */
    public function testRender()
    {
        $this->assertInternalType('string', $this->getPluginMock()->render('svg'));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Graph\Communication\Plugin\GraphPlugin
     */
    protected function getPluginMock()
    {
        $graphMock = $this->getMockBuilder(GraphInterface::class)->setMethods(['create', 'addNode', 'addEdge', 'addCluster', 'render'])->getMock();
        $graphMock->method('render')->willReturn('');

        $factoryMock = $this->getMockBuilder(GraphCommunicationFactory::class)->getMock();
        $factoryMock->method('createGraph')->willReturn($graphMock);

        $pluginMock = $this->getMockBuilder(GraphPlugin::class)->setMethods(['getFactory'])->setConstructorArgs(['name'])->disableOriginalConstructor()->getMock();
        $pluginMock->method('getFactory')->willReturn($factoryMock);

        return $pluginMock->init(self::GRAPH_NAME);
    }
}
