<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Graph\Communication;

use Codeception\Test\Unit;
use Spryker\Shared\Graph\GraphInterface;
use Spryker\Zed\Graph\Communication\Exception\GraphAdapterNameIsAnObjectException;
use Spryker\Zed\Graph\Communication\Exception\InvalidGraphAdapterException;
use Spryker\Zed\Graph\Communication\Exception\InvalidGraphAdapterNameException;
use Spryker\Zed\Graph\Communication\GraphCommunicationFactory;
use Spryker\Zed\Graph\GraphConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Graph
 * @group Communication
 * @group GraphCommunicationFactoryTest
 * Add your own group annotations below this line
 */
class GraphCommunicationFactoryTest extends Unit
{
    public const GRAPH_NAME = 'graph name';

    /**
     * @return void
     */
    public function testCreateGraphAdapterWithObjectFromConfigMustThrowException(): void
    {
        $this->expectException(GraphAdapterNameIsAnObjectException::class);

        $factory = new GraphCommunicationFactory();
        $configMock = $this->getConfigMock($factory);
        $factory->setConfig($configMock);

        $factory->createGraph(self::GRAPH_NAME);
    }

    /**
     * @return void
     */
    public function testCreateGraphAdapterWithInvalidAdapterNameFromConfigMustThrowException(): void
    {
        $this->expectException(InvalidGraphAdapterNameException::class);

        $factory = new GraphCommunicationFactory();
        $configMock = $this->getConfigMock('not a class name');
        $factory->setConfig($configMock);

        $factory->createGraph(self::GRAPH_NAME);
    }

    /**
     * @return void
     */
    public function testCreateGraphAdapterWithInvalidAdapterInstanceMustThrowException(): void
    {
        $this->expectException(InvalidGraphAdapterException::class);

        $factory = new GraphCommunicationFactory();
        $configMock = $this->getConfigMock(get_class($factory));
        $factory->setConfig($configMock);

        $factory->createGraph(self::GRAPH_NAME);
    }

    /**
     * @return void
     */
    public function testCreateGraph(): void
    {
        $factory = new GraphCommunicationFactory();
        $factory->setConfig(new GraphConfig());

        $this->assertInstanceOf(GraphInterface::class, $factory->createGraph(self::GRAPH_NAME));
    }

    /**
     * @param string|object $return
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Graph\GraphConfig
     */
    protected function getConfigMock($return): GraphConfig
    {
        $configMock = $this->getMockBuilder(GraphConfig::class)->setMethods(['getGraphAdapterName'])->getMock();
        $configMock->method('getGraphAdapterName')->willReturn($return);

        return $configMock;
    }
}
