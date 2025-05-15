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
use Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemCollectionPostUpdatePluginInterface;
use Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemsPreUpdatePluginInterface;
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
 * @group UpdateSalesOrderItemCollectionByQuoteTest
 * Add your own group annotations below this line
 */
class UpdateSalesOrderItemCollectionByQuoteTest extends Unit
{
    /**
     * @var int
     */
    protected const FAKE_ID_SALES_ORDER = 123456;

    /**
     * @var int
     */
    protected const FAKE_ID_SALES_ORDER_ITEM = 123456;

    /**
     * @var string
     */
    protected const NEW_SKU = 'new-sku';

    /**
     * @var int
     */
    protected const NEW_QUANTITY = 9;

    /**
     * @var string
     */
    protected const NEW_NAME = 'new-name';

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
     * @uses \Spryker\Zed\Sales\Business\Validator\Rule\SalesOrderItem\DuplicationSalesOrderItemValidatorRule::GLOSSARY_KEY_VALIDATION_SALES_ORDER_ITEM_ENTITY_DUPLICATED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SALES_ORDER_ITEM_ENTITY_DUPLICATED = 'sales.validation.sales_order_item_entity_duplicated';

    /**
     * @uses \Spryker\Zed\Sales\Business\Validator\Rule\SalesOrderItem\ExistenceSalesOrderItemValidatorRule::GLOSSARY_KEY_VALIDATION_SALES_ORDER_ITEM_ENTITY_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SALES_ORDER_ITEM_ENTITY_NOT_FOUND = 'sales.validation.sales_order_item_entity_not_found';

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
    public function testShouldUpdateSalesOrderItemEntity(string $hashColumn): void
    {
        // Arrange
        $this->mockSalesConfig($hashColumn);
        $this->addOrderItemExpanderPreSavePlugins($hashColumn);
        $quoteTransfer = $this->prepareQuoteWithOneItem();
        $quoteTransfer->getItems()->offsetGet(0)
            ->setSku(static::NEW_SKU)
            ->setName(static::NEW_NAME)
            ->setQuantity(static::NEW_QUANTITY);

        $salesOrderItemCollectionRequestTransfer = (new SalesOrderItemCollectionRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setItems($quoteTransfer->getItems());

        // Act
        $salesOrderItemCollectionResponseTransfer = $this->salesFacade
            ->updateSalesOrderItemCollectionByQuote($salesOrderItemCollectionRequestTransfer);

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
        $itemTransfer = (new ItemTransfer())
            ->setFkSalesOrder(static::FAKE_ID_SALES_ORDER)
            ->setIdSalesOrderItem(static::FAKE_ID_SALES_ORDER_ITEM);

        $salesOrderItemCollectionRequestTransfer = (new SalesOrderItemCollectionRequestTransfer())
            ->setQuote(null)
            ->setItems(new ArrayObject([$itemTransfer]));

        // Act
        $this->tester->getFacade()->updateSalesOrderItemCollectionByQuote($salesOrderItemCollectionRequestTransfer);
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
        $itemTransfer = (new ItemTransfer())
            ->setFkSalesOrder(null)
            ->setIdSalesOrderItem(static::FAKE_ID_SALES_ORDER_ITEM);

        $salesOrderItemCollectionRequestTransfer = (new SalesOrderItemCollectionRequestTransfer())
            ->setQuote(new QuoteTransfer())
            ->setItems(new ArrayObject([$itemTransfer]));

        // Act
        $this->tester->getFacade()->updateSalesOrderItemCollectionByQuote($salesOrderItemCollectionRequestTransfer);
    }

    /**
     * @dataProvider getHashColumnDataProvider
     *
     * @param string $hashColumn
     *
     * @return void
     */
    public function testShouldThrowsExceptionWhenSalesOrderItemIdIsNotSet(string $hashColumn): void
    {
        // Assert
        $this->mockSalesConfig($hashColumn);
        $this->addOrderItemExpanderPreSavePlugins($hashColumn);
        $this->expectException(RequiredTransferPropertyException::class);

        // Arrange
        $itemTransfer = (new ItemTransfer())
            ->setFkSalesOrder(static::FAKE_ID_SALES_ORDER)
            ->setIdSalesOrderItem(null);

        $salesOrderItemCollectionRequestTransfer = (new SalesOrderItemCollectionRequestTransfer())
            ->setQuote(new QuoteTransfer())
            ->setItems(new ArrayObject([$itemTransfer]));

        // Act
        $this->tester->getFacade()->updateSalesOrderItemCollectionByQuote($salesOrderItemCollectionRequestTransfer);
    }

    /**
     * @dataProvider getHashColumnDataProvider
     *
     * @param string $hashColumn
     *
     * @return void
     */
    public function testShouldNotUpdateSalesOrderItemEntityWithWrongIdSalesOrder(string $hashColumn): void
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
            ->updateSalesOrderItemCollectionByQuote($salesOrderItemCollectionRequestTransfer);

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
    public function testShouldNotUpdateSalesOrderItemEntityWithWrongIdSalesOrderItem(string $hashColumn): void
    {
        // Arrange
        $this->mockSalesConfig($hashColumn);
        $this->addOrderItemExpanderPreSavePlugins($hashColumn);
        $quoteTransfer = $this->prepareQuoteWithOneItem();
        $newItemTransfer = clone $quoteTransfer->getItems()->offsetGet(0);
        $newItemTransfer->setIdSalesOrderItem(static::FAKE_ID_SALES_ORDER_ITEM);
        $quoteTransfer->addItem($newItemTransfer);

        $salesOrderItemCollectionRequestTransfer = (new SalesOrderItemCollectionRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setItems($quoteTransfer->getItems());

        // Act
        $salesOrderItemCollectionResponseTransfer = $this->salesFacade
            ->updateSalesOrderItemCollectionByQuote($salesOrderItemCollectionRequestTransfer);

        // Assert
        $this->assertSame(
            static::GLOSSARY_KEY_VALIDATION_SALES_ORDER_ITEM_ENTITY_NOT_FOUND,
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
    public function testShouldNotUpdateSalesOrderItemEntityWithItemFromAnotherOrder(string $hashColumn): void
    {
        // Arrange
        $this->mockSalesConfig($hashColumn);
        $this->addOrderItemExpanderPreSavePlugins($hashColumn);
        $quoteTransfer = $this->prepareQuoteWithOneItem();
        $itemTransfer = (new ItemTransfer())
            ->setFkSalesOrder(static::FAKE_ID_SALES_ORDER)
            ->setIdSalesOrderItem(static::FAKE_ID_SALES_ORDER_ITEM);

        $quoteTransfer->addItem($itemTransfer);

        $salesOrderItemCollectionRequestTransfer = (new SalesOrderItemCollectionRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setItems($quoteTransfer->getItems());

        // Act
        $salesOrderItemCollectionResponseTransfer = $this->salesFacade
            ->updateSalesOrderItemCollectionByQuote($salesOrderItemCollectionRequestTransfer);

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
    public function testShouldNotUpdateSalesOrderItemEntityWithDuplicatedItems(string $hashColumn): void
    {
        // Arrange
        $this->mockSalesConfig($hashColumn);
        $this->addOrderItemExpanderPreSavePlugins($hashColumn);
        $quoteTransfer = $this->prepareQuoteWithOneItem();
        $quoteTransfer->addItem(clone ($quoteTransfer->getItems()->offsetGet(0)));

        $salesOrderItemCollectionRequestTransfer = (new SalesOrderItemCollectionRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setItems($quoteTransfer->getItems());

        // Act
        $salesOrderItemCollectionResponseTransfer = $this->salesFacade
            ->updateSalesOrderItemCollectionByQuote($salesOrderItemCollectionRequestTransfer);

        // Assert
        $this->assertSame(
            static::GLOSSARY_KEY_VALIDATION_SALES_ORDER_ITEM_ENTITY_DUPLICATED,
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
    public function testShouldExecuteSalesOrderItemsPreUpdatePluginStack(string $hashColumn): void
    {
        // Assert
        $this->mockSalesConfig($hashColumn);
        $this->addOrderItemExpanderPreSavePlugins($hashColumn);
        $this->tester->setDependency(
            SalesDependencyProvider::PLUGINS_ORDER_ITEMS_PRE_UPDATE,
            [
                $this->getSalesOrderItemsPreUpdatePluginMock(),
            ],
        );

        // Arrange
        $quoteTransfer = $this->prepareQuoteWithOneItem();
        $salesOrderItemCollectionRequestTransfer = (new SalesOrderItemCollectionRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setItems($quoteTransfer->getItems());

        // Act
        $this->salesFacade->updateSalesOrderItemCollectionByQuote($salesOrderItemCollectionRequestTransfer);
    }

    /**
     * @dataProvider getHashColumnDataProvider
     *
     * @param string $hashColumn
     *
     * @return void
     */
    public function testShouldExecuteSalesOrderItemCollectionPostUpdatePluginStack(string $hashColumn): void
    {
        // Assert
        $this->mockSalesConfig($hashColumn);
        $this->addOrderItemExpanderPreSavePlugins($hashColumn);
        $this->tester->setDependency(
            SalesDependencyProvider::PLUGINS_ORDER_ITEM_COLLECTION_POST_UPDATE,
            [
                $this->getSalesOrderItemCollectionPostUpdatePluginMock(),
            ],
        );

        // Arrange
        $quoteTransfer = $this->prepareQuoteWithOneItem();
        $salesOrderItemCollectionRequestTransfer = (new SalesOrderItemCollectionRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setItems($quoteTransfer->getItems());

        // Act
        $this->salesFacade->updateSalesOrderItemCollectionByQuote($salesOrderItemCollectionRequestTransfer);
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
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemsPreUpdatePluginInterface
     */
    protected function getSalesOrderItemsPreUpdatePluginMock(): SalesOrderItemsPreUpdatePluginInterface
    {
        $salesOrderItemsPreUpdatePluginMock = $this
            ->getMockBuilder(SalesOrderItemsPreUpdatePluginInterface::class)
            ->getMock();

        $salesOrderItemsPreUpdatePluginMock
            ->expects($this->once())
            ->method('preUpdate')
            ->willReturnCallback(function (QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer) {
                return $quoteTransfer;
            });

        return $salesOrderItemsPreUpdatePluginMock;
    }

    /**
     * @return \Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemCollectionPostUpdatePluginInterface
     */
    protected function getSalesOrderItemCollectionPostUpdatePluginMock(): SalesOrderItemCollectionPostUpdatePluginInterface
    {
        $salesOrderItemCollectionPostUpdatePluginMock = $this
            ->getMockBuilder(SalesOrderItemCollectionPostUpdatePluginInterface::class)
            ->getMock();

        $salesOrderItemCollectionPostUpdatePluginMock
            ->expects($this->once())
            ->method('postUpdate')
            ->willReturnCallback(function (SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer) {
                return $salesOrderItemCollectionResponseTransfer;
            });

        return $salesOrderItemCollectionPostUpdatePluginMock;
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

        $this->salesFacade->saveSalesOrderItems($quoteTransfer, $saveOrderTransfer);

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
     * @param string$hashColumn
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
