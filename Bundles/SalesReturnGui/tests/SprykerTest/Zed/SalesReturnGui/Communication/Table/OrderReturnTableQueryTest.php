<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReturnGui\Communication\Table;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MoneyTransfer;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToMoneyFacadeInterface;
use Twig\Environment;
use Twig\Loader\ChainLoader;
use Twig\Loader\LoaderInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesReturnGui
 * @group Communication
 * @group Table
 * @group OrderReturnTableQueryTest
 * Add your own group annotations below this line
 */
class OrderReturnTableQueryTest extends Unit
{
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Table\OrderReturnTable::COL_ITEMS
     */
    protected const COL_ITEMS = 'items';

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Table\OrderReturnTable::COL_RETURN_REFERENCE
     */
    protected const COL_RETURN_REFERENCE = 'return_reference';

    /**
     * @uses \Spryker\Zed\Twig\Communication\Plugin\Application\TwigApplicationPlugin::SERVICE_TWIG
     */
    public const SERVICE_TWIG = 'twig';

    /**
     * @var \SprykerTest\Zed\SalesReturnGui\SalesReturnGuiCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->registerTwigServiceMock();
        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testFetchDataReturnsCorrectOrderReturnData(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);
        $returnTransfer = $this->tester->createReturnByStateMachineProcessName($orderTransfer, $customerTransfer);

        $orderReturnTableMock = new OrderReturnTableMock(
            $orderTransfer,
            $this->getMoneyFacadeMock(),
            SpySalesReturnQuery::create()
        );

        // Act
        $result = $orderReturnTableMock->fetchData();

        // Assert
        $this->assertCount(1, $result);
        $this->assertEquals(2, $result[0][static::COL_ITEMS]);
        $this->assertEquals($returnTransfer->getReturnReference(), $result[0][static::COL_RETURN_REFERENCE]);
    }

    /**
     * @return void
     */
    protected function registerTwigServiceMock(): void
    {
        $this->tester->getContainer()->set(static::SERVICE_TWIG, $this->getTwigMock());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToMoneyFacadeInterface
     */
    protected function getMoneyFacadeMock(): SalesReturnGuiToMoneyFacadeInterface
    {
        $moneyFacadeMock = $this->getMockBuilder(SalesReturnGuiToMoneyFacadeInterface::class)->getMock();
        $moneyFacadeMock->method('fromInteger')->willReturn(new MoneyTransfer());
        $moneyFacadeMock->method('formatWithSymbol')->willReturn('1000 $');

        return $moneyFacadeMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Twig\Environment
     */
    protected function getTwigMock(): Environment
    {
        $twigMock = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();
        $twigMock->method('render')
            ->willReturn('Fully rendered template');
        $twigMock->method('getLoader')->willReturn($this->getChainLoader());

        return $twigMock;
    }

    /**
     * @return \Twig\Loader\LoaderInterface
     */
    protected function getChainLoader(): LoaderInterface
    {
        return new ChainLoader();
    }
}
