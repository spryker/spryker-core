<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Graph\Communication;

use Spryker\Shared\Graph\GraphInterface;
use Spryker\Zed\Graph\Communication\Exception\GraphAdapterNameIsAnObjectException;
use Spryker\Zed\Graph\Communication\Exception\InvalidGraphAdapterException;
use Spryker\Zed\Graph\Communication\Exception\InvalidGraphAdapterNameException;
use Spryker\Zed\Graph\Communication\GraphCommunicationFactory;
use Spryker\Zed\Graph\GraphConfig;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Graph
 * @group Communication
 * @group GraphCommunicationFactoryTest
 */
class GraphCommunicationFactoryTest extends \PHPUnit_Framework_TestCase
{

    const GRAPH_NAME = 'graph name';

    /**
     * @return void
     */
    public function testCreateGraphAdapterWithObjectFromConfigMustThrowException()
    {
        $this->setExpectedException(GraphAdapterNameIsAnObjectException::class);

        $factory = new GraphCommunicationFactory();
        $configMock = $this->getConfigMock($factory);
        $factory->setConfig($configMock);

        $factory->createGraph(self::GRAPH_NAME);
    }

    /**
     * @return void
     */
    public function testCreateGraphAdapterWithInvalidAdapterNameFromConfigMustThrowException()
    {
        $this->setExpectedException(InvalidGraphAdapterNameException::class);

        $factory = new GraphCommunicationFactory();
        $configMock = $this->getConfigMock('not a class name');
        $factory->setConfig($configMock);

        $factory->createGraph(self::GRAPH_NAME);
    }

    /**
     * @return void
     */
    public function testCreateGraphAdapterWithInvalidAdapterInstanceMustThrowException()
    {
        $this->setExpectedException(InvalidGraphAdapterException::class);

        $factory = new GraphCommunicationFactory();
        $configMock = $this->getConfigMock(get_class($factory));
        $factory->setConfig($configMock);

        $factory->createGraph(self::GRAPH_NAME);
    }

    /**
     * @return void
     */
    public function testCreateGraph()
    {
        $factory = new GraphCommunicationFactory();
        $factory->setConfig(new GraphConfig());

        $this->assertInstanceOf(GraphInterface::class, $factory->createGraph(self::GRAPH_NAME));
    }

    /**
     * @param string|object $return
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Graph\GraphConfig
     */
    protected function getConfigMock($return)
    {
        $configMock = $this->getMock(GraphConfig::class, ['getGraphAdapterName']);
        $configMock->method('getGraphAdapterName')->willReturn($return);

        return $configMock;
    }

}
