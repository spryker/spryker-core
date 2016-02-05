<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */


namespace Unit\Spryker\Zed\Graph\Communication\Plugin;

use Spryker\Shared\Graph\GraphInterface;
use Spryker\Zed\Graph\Communication\GraphCommunicationFactory;
use Spryker\Zed\Graph\Communication\Plugin\GraphPlugin;

/**
 * @group Spryker
 * @group Zed
 * @group Graph
 * @group Communication
 */
class GraphPluginTest extends \PHPUnit_Framework_TestCase
{

    const GRAPH_NAME = 'graph name';
    const NODE_A = 'node A';
    const NODE_B = 'node B';
    const GROUP_NAME = 'group name';
    const CLUSTER_NAME = 'cluster name';
    const ATTRIBUTES = ['attribute' => 'value', 'html attribute' => '<h1>Html Value</h1>'];

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
     * @return \PHPUnit_Framework_MockObject_MockObject|GraphPlugin
     */
    protected function getPluginMock()
    {
        $graphMock = $this->getMock(GraphInterface::class, ['create', 'addNode', 'addEdge', 'addCluster', 'render']);
        $graphMock->method('render')->willReturn('');

        $factoryMock = $this->getMock(GraphCommunicationFactory::class);
        $factoryMock->method('createGraph')->willReturn($graphMock);

        $pluginMock = $this->getMock(GraphPlugin::class, ['getFactory', 'getGraph'], ['name'], '', false);
        $pluginMock->method('getFactory')->willReturn($factoryMock);
        $pluginMock->method('getGraph')->willReturn($graphMock);

        return $pluginMock;
    }
}
