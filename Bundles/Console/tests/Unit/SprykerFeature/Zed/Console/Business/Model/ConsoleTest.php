<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Console\Business\Model\Console;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerEngine\Zed\Kernel\Persistence\Factory;
use SprykerFeature\Zed\Console\Business\Model\Console;
use Unit\SprykerFeature\Zed\Console\Business\Model\Fixtures\ConsoleMock;
use Unit\SprykerFeature\Zed\Console\Business\Model\Fixtures\QueryContainer;

class ConsoleTest extends \PHPUnit_Framework_TestCase
{

    public function testGetDependencyContainerShouldReturnNullIfNotSet()
    {
        $console = new ConsoleMock('TestCommand');

        $this->assertNull($console->getDependencyContainer());
    }

    public function testGetDependencyContainerShouldReturnInstanceIfSet()
    {
        $console = new ConsoleMock('TestCommand');
        $console->setDependencyContainer($this->getDependencyContainerMock());

        $this->assertInstanceOf('SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer', $console->getDependencyContainer());
    }

    public function testGetFacadeShouldReturnNullIfNotSet()
    {
        $console = new ConsoleMock('TestCommand');

        $this->assertNull($console->getFacade());
    }

    public function testGetFacadeShouldReturnInstanceIfSet()
    {
        $console = new ConsoleMock('TestCommand');
        $console->setFacade($this->getFacadeMock());

        $this->assertInstanceOf('SprykerEngine\Zed\Kernel\Business\AbstractFacade', $console->getFacade());
    }

    public function testGetQueryContainerShouldReturnNullIfNotSet()
    {
        $console = new ConsoleMock('TestCommand');

        $this->assertNull($console->getQueryContainer());
    }

    public function testGetQueryContainerShouldReturnInstanceIfSet()
    {
        $console = new ConsoleMock('TestCommand');
        $console->setQueryContainer($this->getQueryContainerMock());

        $this->assertInstanceOf('SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer', $console->getQueryContainer());
    }

    /**
     * @return AbstractDependencyContainer
     */
    private function getDependencyContainerMock()
    {
        return $this->getMock('SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer', [], [], '', false);
    }

    /**
     * @return AbstractFacade
     */
    private function getFacadeMock()
    {
        return $this->getMock('SprykerEngine\Zed\Kernel\Business\AbstractFacade', [], [], '', false);
    }

    /**
     * @return AbstractQueryContainer
     */
    private function getQueryContainerMock()
    {
        return $this->getMock('SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer', [], [], '', false);
    }
}
