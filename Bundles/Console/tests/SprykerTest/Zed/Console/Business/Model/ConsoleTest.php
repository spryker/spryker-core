<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Console\Business\Model;

use Codeception\Test\Unit;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerTest\Zed\Console\Business\Model\Fixtures\ConsoleMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Console
 * @group Business
 * @group Model
 * @group ConsoleTest
 * Add your own group annotations below this line
 */
class ConsoleTest extends Unit
{

    /**
     * @return void
     */
    public function testGetCommunicationFactoryShouldReturnInstanceIfSet()
    {
        $console = $this->getConsole();
        $console->setFactory($this->getCommunicationFactoryMock());

        $this->assertInstanceOf(
            'Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory',
            $console->getFactory()
        );
    }

    /**
     * @return void
     */
    public function testGetQueryContainerShouldReturnNullIfNotSet()
    {
        $console = $this->getConsole();

        $this->assertNull($console->getQueryContainer());
    }

    /**
     * @return void
     */
    public function testGetQueryContainerShouldReturnInstanceIfSet()
    {
        $console = $this->getConsole();
        $console->setQueryContainer($this->getQueryContainerMock());

        $this->assertInstanceOf(
            AbstractQueryContainer::class,
            $console->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    private function getCommunicationFactoryMock()
    {
        return $this->getMockBuilder(AbstractCommunicationFactory::class)->disableOriginalConstructor()->getMock();
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    private function getFacadeMock()
    {
        return $this->getMockBuilder(AbstractFacade::class)->disableOriginalConstructor()->getMock();
    }

    /**
     * @return \Spryker\Zed\Kernel\Persistence\AbstractQueryContainer
     */
    private function getQueryContainerMock()
    {
        return $this->getMockBuilder(AbstractQueryContainer::class)->disableOriginalConstructor()->getMock();
    }

    /**
     * @return \Unit\Spryker\Zed\Console\Business\Model\Fixtures\ConsoleMock
     */
    private function getConsole()
    {
        return new ConsoleMock('TestCommand');
    }

}
