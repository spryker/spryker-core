<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use ReflectionClass;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Sales\Business\SalesBusinessFactory;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Zed\Sales\Persistence\SalesPersistenceFactory;
use Spryker\Zed\Sales\SalesConfig;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemCollectionPostCreatePluginInterface;
use Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemsPreCreatePluginInterface;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;
use SprykerTest\Zed\Sales\SalesBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group CreateSalesOrderItemCollectionByQuoteTest
 * Add your own group annotations below this line
 */
class CreateSalesOrderItemCollectionByQuoteTest extends Unit
{
    /**
     * @var int
     */
    protected const FAKE_ID_SALES_ORDER = 123456;

    /**
     * @uses \Spryker\Zed\Sales\Business\Validator\Rule\SalesOrderItem\SalesOrderItemRelationValidatorRule::GLOSSARY_KEY_VALIDATION_SALES_ORDER_ENTITY_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SALES_ORDER_ENTITY_NOT_FOUND = 'sales.validation.sales_order_entity_not_found';

    /**
     * @uses \Spryker\Zed\Sales\Business\Validator\Rule\SalesOrderItem\SalesOrderItemRelationValidatorRule::GLOSSARY_KEY_VALIDATION_ITEMS_NOT_FROM_SAME_ORDER
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ITEMS_NOT_FROM_SAME_ORDER = 'sales.validation.items_not_from_same_order';

    /**
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
     */
    protected SalesBusinessTester $tester;

    /**
     * @var \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected SalesFacadeInterface $salesFacade;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
        $this->salesFacade = $this->tester->getFacade();

        $this->tester->createInitialState();
    }

    /**
     * @dataProvider getHashColumnDataProvider
     *
     * @param string $hashColumn
     *
     * @return void
     */
    public function testShouldPersistSalesOrderItemEntity(string $hashColumn): void
    {
        // Arrange
        $this->mockSalesConfig($hashColumn);
        $this->addOrderItemExpanderPreSavePlugins($hashColumn);
        $quoteTransfer = $this->prepareQuoteWithOneItem();

        $salesOrderItemCollectionRequestTransfer = (new SalesOrderItemCollectionRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setItems($quoteTransfer->getItems());

        // Act
        $salesOrderItemCollectionResponseTransfer = $this->salesFacade
            ->createSalesOrderItemCollectionByQuote($salesOrderItemCollectionRequestTransfer);

        // Assert
        $itemTransfer = $quoteTransfer->getItems()->offsetGet(0);
        $salesOrderItemEntity = $this->tester->findSalesOrderItemEntityById(
            $salesOrderItemCollectionResponseTransfer->getItems()->offsetGet(0)->getIdSalesOrderItem(),
        );
        $this->assertSame($itemTransfer->getSku(), $salesOrderItemEntity->getSku());
        $this->assertSame($itemTransfer->getName(), $salesOrderItemEntity->getName());
        $this->assertSame($itemTransfer->getQuantity(), $salesOrderItemEntity->getQuantity());
    }

    /**
     * @dataProvider getHashColumnDataProvider
     *
     * @param string $hashColumn
     *
     * @return void
     */
    public function testShouldThrowsExceptionWhenQuoteIsNotSet(string $hashColumn): void
    {
        // Assert
        $this->mockSalesConfig($hashColumn);
        $this->addOrderItemExpanderPreSavePlugins($hashColumn);
        $this->expectException(RequiredTransferPropertyException::class);

        // Arrange
        $salesOrderItemCollectionRequestTransfer = (new SalesOrderItemCollectionRequestTransfer())
            ->setQuote(null)
            ->setItems(new ArrayObject([
                (new ItemTransfer())->setFkSalesOrder(static::FAKE_ID_SALES_ORDER),
            ]));

        // Act
        $this->tester->getFacade()->createSalesOrderItemCollectionByQuote($salesOrderItemCollectionRequestTransfer);
    }

    /**
     * @dataProvider getHashColumnDataProvider
     *
     * @param string $hashColumn
     *
     * @return void
     */
    public function testShouldThrowsExceptionWhenSalesOrderIdIsNotSet(string $hashColumn): void
    {
        // Assert
        $this->mockSalesConfig($hashColumn);
        $this->addOrderItemExpanderPreSavePlugins($hashColumn);
        $this->expectException(RequiredTransferPropertyException::class);

        // Arrange
        $salesOrderItemCollectionRequestTransfer = (new SalesOrderItemCollectionRequestTransfer())
            ->setQuote(new QuoteTransfer())
            ->setItems(new ArrayObject([
                (new ItemTransfer())->setFkSalesOrder(null),
            ]));

        // Act
        $this->tester->getFacade()->createSalesOrderItemCollectionByQuote($salesOrderItemCollectionRequestTransfer);
    }

    /**
     * @dataProvider getHashColumnDataProvider
     *
     * @param string $hashColumn
     *
     * @return void
     */
    public function testShouldNotPersistSalesOrderItemEntityWithWrongIdSalesOrder(string $hashColumn): void
    {
        // Arrange
        $this->mockSalesConfig($hashColumn);
        $this->addOrderItemExpanderPreSavePlugins($hashColumn);
        $quoteTransfer = $this->prepareQuoteWithOneItem();
        $quoteTransfer->getItems()->offsetGet(0)->setFkSalesOrder(static::FAKE_ID_SALES_ORDER);

        $salesOrderItemCollectionRequestTransfer = (new SalesOrderItemCollectionRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setItems($quoteTransfer->getItems());

        // Act
        $salesOrderItemCollectionResponseTransfer = $this->salesFacade
            ->createSalesOrderItemCollectionByQuote($salesOrderItemCollectionRequestTransfer);

        // Assert
        $this->assertSame(
            static::GLOSSARY_KEY_VALIDATION_SALES_ORDER_ENTITY_NOT_FOUND,
            $salesOrderItemCollectionResponseTransfer->getErrors()[0]->getMessage(),
        );
    }

    /**
     * @dataProvider getHashColumnDataProvider
     *
     * @param string $hashColumn
     *
     * @return void
     */
    public function testShouldNotPersistSalesOrderItemEntityWithItemFromAnotherOrder(string $hashColumn): void
    {
        // Arrange
        $this->mockSalesConfig($hashColumn);
        $this->addOrderItemExpanderPreSavePlugins($hashColumn);
        $quoteTransfer = $this->prepareQuoteWithOneItem();
        $quoteTransfer->addItem((new ItemTransfer())->setFkSalesOrder(static::FAKE_ID_SALES_ORDER));

        $salesOrderItemCollectionRequestTransfer = (new SalesOrderItemCollectionRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setItems($quoteTransfer->getItems());

        // Act
        $salesOrderItemCollectionResponseTransfer = $this->salesFacade
            ->createSalesOrderItemCollectionByQuote($salesOrderItemCollectionRequestTransfer);

        // Assert
        $this->assertSame(
            static::GLOSSARY_KEY_VALIDATION_ITEMS_NOT_FROM_SAME_ORDER,
            $salesOrderItemCollectionResponseTransfer->getErrors()[0]->getMessage(),
        );
    }

    /**
     * @dataProvider getHashColumnDataProvider
     *
     * @param string $hashColumn
     *
     * @return void
     */
    public function testShouldExecutesalesOrderItemsPreCreatePluginStack(string $hashColumn): void
    {
        // Assert
        $this->mockSalesConfig($hashColumn);
        $this->addOrderItemExpanderPreSavePlugins($hashColumn);
        $this->tester->setDependency(
            SalesDependencyProvider::PLUGINS_ORDER_ITEMS_PRE_CREATE,
            [
                $this->getSalesOrderItemsPreCreatePluginMock(),
            ],
        );

        // Arrange
        $quoteTransfer = $this->prepareQuoteWithOneItem();
        $salesOrderItemCollectionRequestTransfer = (new SalesOrderItemCollectionRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setItems($quoteTransfer->getItems());

        // Act
        $this->salesFacade->createSalesOrderItemCollectionByQuote($salesOrderItemCollectionRequestTransfer);
    }

    /**
     * @dataProvider getHashColumnDataProvider
     *
     * @param string $hashColumn
     *
     * @return void
     */
    public function testShouldExecuteSalesOrderItemCollectionPostCreatePluginStack(string $hashColumn): void
    {
        // Assert
        $this->mockSalesConfig($hashColumn);
        $this->addOrderItemExpanderPreSavePlugins($hashColumn);
        $this->tester->setDependency(
            SalesDependencyProvider::PLUGINS_ORDER_ITEM_COLLECTION_POST_CREATE,
            [
                $this->getSalesOrderItemCollectionPostCreatePluginMock(),
            ],
        );

        // Arrange
        $quoteTransfer = $this->prepareQuoteWithOneItem();
        $salesOrderItemCollectionRequestTransfer = (new SalesOrderItemCollectionRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setItems($quoteTransfer->getItems());

        // Act
        $this->salesFacade->createSalesOrderItemCollectionByQuote($salesOrderItemCollectionRequestTransfer);
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function getHashColumnDataProvider(): array
    {
        return [
            'test with a hash column' => [
                'hashColumn' => 'OrderItemReference',
            ],
            'test without a hash column' => [
                'hashColumn' => '',
            ],
        ];
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemsPreCreatePluginInterface
     */
    protected function getSalesOrderItemsPreCreatePluginMock(): SalesOrderItemsPreCreatePluginInterface
    {
        $salesOrderItemsPreCreatePluginMock = $this
            ->getMockBuilder(SalesOrderItemsPreCreatePluginInterface::class)
            ->getMock();

        $salesOrderItemsPreCreatePluginMock
            ->expects($this->once())
            ->method('preCreate')
            ->willReturnCallback(function (QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer) {
                return $quoteTransfer;
            });

        return $salesOrderItemsPreCreatePluginMock;
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemCollectionPostCreatePluginInterface
     */
    protected function getSalesOrderItemCollectionPostCreatePluginMock(): SalesOrderItemCollectionPostCreatePluginInterface
    {
        $salesOrderItemCollectionPostCreatePluginMock = $this
            ->getMockBuilder(SalesOrderItemCollectionPostCreatePluginInterface::class)
            ->getMock();

        $salesOrderItemCollectionPostCreatePluginMock
            ->expects($this->once())
            ->method('postCreate')
            ->willReturnCallback(function (SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer) {
                return $salesOrderItemCollectionResponseTransfer;
            });

        return $salesOrderItemCollectionPostCreatePluginMock;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function prepareQuoteWithOneItem(): QuoteTransfer
    {
        $saveOrderTransfer = new SaveOrderTransfer();
        $quoteTransfer = $this->tester->getValidBaseQuoteTransfer();
        $this->salesFacade->saveOrderRaw($quoteTransfer, $saveOrderTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setFkSalesOrder($saveOrderTransfer->getIdSalesOrder());
        }

        return $quoteTransfer;
    }

    /**
     * @param string $hashColumn
     *
     * @return void
     */
    protected function mockSalesConfig(string $hashColumn): void
    {
        $businessFactory = new SalesBusinessFactory();
        $persistenceFactory = new SalesPersistenceFactory();

        $salesConfigMock = $this->getMockBuilder(SalesConfig::class)->onlyMethods([
            'determineProcessForOrderItem',
            'getItemHashColumn',
        ])->getMock();
        $salesConfigMock->method('determineProcessForOrderItem')->willReturn(BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $salesConfigMock->method('getItemHashColumn')->willReturn($hashColumn);

        $reflection = new ReflectionClass($this->salesFacade);
        $method = $reflection->getMethod('getEntityManager');
        $method->setAccessible(true);

        $entityManager = $method->invoke($this->salesFacade, 'getEntityManager');
        $persistenceFactory->setConfig($salesConfigMock);
        $entityManager->setFactory($persistenceFactory);

        $businessFactory->setConfig($salesConfigMock);
        $businessFactory->setEntityManager($entityManager);
        $this->salesFacade->setFactory($businessFactory);
    }

    /**
     * @param string $hashColumn
     *
     * @return void
     */
    protected function addOrderItemExpanderPreSavePlugins(string $hashColumn): void
    {
        if ($hashColumn === '') {
            return;
        }
        $this->tester->setDependency(
            SalesDependencyProvider::ORDER_ITEM_EXPANDER_PRE_SAVE_PLUGINS,
            [
                $this->tester->createHashGeneratorExpanderPlugin($hashColumn),
            ],
        );
    }
}
