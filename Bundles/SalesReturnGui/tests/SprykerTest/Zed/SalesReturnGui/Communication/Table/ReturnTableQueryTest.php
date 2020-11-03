<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReturnGui\Communication\Table;

use Codeception\Test\Unit;
use DateTime;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery;
use Spryker\Zed\SalesReturnGui\Dependency\Service\SalesReturnGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\SalesReturnGui\SalesReturnGuiConfig;
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
 * @group ReturnTableQueryTest
 * Add your own group annotations below this line
 */
class ReturnTableQueryTest extends Unit
{
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @uses \Spryker\Zed\SalesReturnGui\Communication\Table\ReturnTable::COL_RETURN_REFERENCE
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
    public function testFetchDataReturnsCorrectReturnData(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $orderTransfer1 = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);
        $orderTransfer2 = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME, $customerTransfer);
        $returnTransfer1 = $this->tester->createReturnByStateMachineProcessName($orderTransfer1, $customerTransfer);
        $returnTransfer2 = $this->tester->createReturnByStateMachineProcessName($orderTransfer2, $customerTransfer);

        $returnTableMock = new ReturnTableMock(
            $this->getUtilDateTimeServiceMock(),
            $this->getSalesReturnGuiConfigMock(),
            SpySalesReturnQuery::create()
        );

        // Act
        $result = $returnTableMock->fetchData();

        // Assert
        $this->assertCount(2, $result);
        $this->assertSame($returnTransfer2->getReturnReference(), $result[0][static::COL_RETURN_REFERENCE]);
        $this->assertSame($returnTransfer1->getReturnReference(), $result[1][static::COL_RETURN_REFERENCE]);
    }

    /**
     * @return void
     */
    protected function registerTwigServiceMock(): void
    {
        $this->tester->getContainer()->set(static::SERVICE_TWIG, $this->getTwigMock());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesReturnGui\Dependency\Service\SalesReturnGuiToUtilDateTimeServiceInterface
     */
    protected function getUtilDateTimeServiceMock(): SalesReturnGuiToUtilDateTimeServiceInterface
    {
        $utilDateTimeServiceMock = $this->getMockBuilder(SalesReturnGuiToUtilDateTimeServiceInterface::class)->getMock();
        $utilDateTimeServiceMock->method('formatDateTime')->willReturn((new DateTime())->format('Y-m-d H:i:s'));

        return $utilDateTimeServiceMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SalesReturnGui\SalesReturnGuiConfig
     */
    protected function getSalesReturnGuiConfigMock(): SalesReturnGuiConfig
    {
        $salesReturnGuiConfigMock = $this->getMockBuilder(SalesReturnGuiConfig::class)->getMock();
        $salesReturnGuiConfigMock->method('getItemStateToLabelClassMapping')->willReturn([]);

        return $salesReturnGuiConfigMock;
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
