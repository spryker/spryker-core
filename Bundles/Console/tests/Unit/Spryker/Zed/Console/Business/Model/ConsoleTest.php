<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Console\Business\Model\Console;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Unit\Spryker\Zed\Console\Business\Model\Fixtures\ConsoleMock;

class ConsoleTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testGetCommunicationFactoryShouldReturnInstanceIfSet()
    {
        $console = $this->getConsole();
        $console->setFactory($this->getCommunicationFactoryMock());

        $this->assertInstanceOf('Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory',
            $console->getFactory()
        );
    }

    /**
     * @return void
     */
    public function testGetFacade()
    {
        $console = $this->getConsole();
        $console->setFacade($this->getFacadeMock());

        $this->assertInstanceOf('Spryker\Zed\Kernel\Business\AbstractFacade', $console->getFacade());
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

        $this->assertInstanceOf('Spryker\Zed\Kernel\Persistence\AbstractQueryContainer',
            $console->getQueryContainer()
        );
    }

    /**
     * @return AbstractCommunicationFactory
     */
    private function getCommunicationFactoryMock()
    {
        return $this->getMock('Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory', [], [],
            '', false
        );
    }

    /**
     * @return AbstractFacade
     */
    private function getFacadeMock()
    {
        return $this->getMock('Spryker\Zed\Kernel\Business\AbstractFacade', [], [], '', false);
    }

    /**
     * @return AbstractQueryContainer
     */
    private function getQueryContainerMock()
    {
        return $this->getMock('Spryker\Zed\Kernel\Persistence\AbstractQueryContainer', [], [], '', false);
    }

    /**
     * @return ConsoleMock
     */
    private function getConsole()
    {
        return new ConsoleMock('TestCommand');
    }

}
