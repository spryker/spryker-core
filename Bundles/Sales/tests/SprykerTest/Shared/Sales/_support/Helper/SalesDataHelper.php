<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Sales\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ExpenseBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\SaveOrderBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesExpenseQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery;
use ReflectionClass;
use Spryker\Zed\Oms\Business\OrderStateMachine\PersistenceManager;
use Spryker\Zed\Sales\Business\SalesBusinessFactory;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesExpenseMapper;
use Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesExpenseMapperInterface;
use SprykerTest\Shared\Sales\Helper\Config\TesterSalesConfig;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Zed\Oms\Helper\OmsHelperTrait;

class SalesDataHelper extends Module
{
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;
    use OmsHelperTrait;

    /**
     * @var array<\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface>
     */
    protected array $saveOrderStack = [];

    /**
     * @param array $override
     * @param string|null $stateMachineProcessName
     * @param array<\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface> $saveOrderStack
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function haveOrder(
        array $override = [],
        ?string $stateMachineProcessName = null,
        array $saveOrderStack = []
    ): SaveOrderTransfer {
        $this->saveOrderStack = $saveOrderStack;
        $quoteTransfer = $this->createQuoteTransfer($override);

        return $this->persistOrder($quoteTransfer, $stateMachineProcessName);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string|null $stateMachineProcessName
     * @param array<\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface> $checkoutDoSaveOrderPlugins
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function haveOrderFromQuote(
        QuoteTransfer $quoteTransfer,
        ?string $stateMachineProcessName = null,
        array $checkoutDoSaveOrderPlugins = []
    ): SaveOrderTransfer {
        $this->saveOrderStack = $checkoutDoSaveOrderPlugins;

        return $this->persistOrder($quoteTransfer, $stateMachineProcessName);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function haveSalesOrderAddress(AddressTransfer $addressTransfer): AddressTransfer
    {
        $addressTransfer = $this->saveSalesOrderAddress($addressTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($addressTransfer): void {
            $this->cleanupSalesOrderAddress($addressTransfer);
        });

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $stateMachineProcessName
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function persistOrder(QuoteTransfer $quoteTransfer, string $stateMachineProcessName): SaveOrderTransfer
    {
        $saveOrderTransfer = $this->createOrder($quoteTransfer, $stateMachineProcessName);
        $this->executeSaveOrderPlugins($quoteTransfer, $saveOrderTransfer);

        return $saveOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string|null $stateMachineProcessName
     * @param array<\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface> $saveOrderStack
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function haveOrderUsingPreparedQuoteTransfer(
        QuoteTransfer $quoteTransfer,
        ?string $stateMachineProcessName = null,
        array $saveOrderStack = []
    ): SaveOrderTransfer {
        $this->getOmsHelper()->configureTestStateMachine([$stateMachineProcessName]);

        $this->saveOrderStack = $saveOrderStack;

        $saveOrderTransfer = $this->createOrder($quoteTransfer, $stateMachineProcessName);
        $this->executeSaveOrderPlugins($quoteTransfer, $saveOrderTransfer);

        return $saveOrderTransfer;
    }

    /**
     * @param \Spryker\Zed\Sales\Business\SalesFacadeInterface $salesFacade
     * @param string $stateMachineProcessName
     *
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function configureSalesFacadeForTests(SalesFacadeInterface $salesFacade, string $stateMachineProcessName): SalesFacadeInterface
    {
        $salesBusinessFactory = new SalesBusinessFactory();

        $salesConfig = new TesterSalesConfig();
        $salesConfig->setStateMachineProcessName($stateMachineProcessName);
        $salesBusinessFactory->setConfig($salesConfig);

        /** @phpstan-var \Spryker\Zed\Kernel\Business\AbstractFacade $salesFacade */
        $salesFacade->setFactory($salesBusinessFactory);

        return $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string|null $stateMachineProcessName
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function createOrder(QuoteTransfer $quoteTransfer, ?string $stateMachineProcessName = null): SaveOrderTransfer
    {
        $saveOrderTransfer = (new SaveOrderBuilder())->makeEmpty()->build();

        $salesFacade = $this->getSalesFacade();
        if ($stateMachineProcessName) {
            $salesFacade = $this->configureSalesFacadeForTests($salesFacade, $stateMachineProcessName);
        }

        $salesFacade->saveSalesOrder($quoteTransfer, $saveOrderTransfer);
        $this->cleanStaticProperty();

        return $saveOrderTransfer;
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function getSalesFacade(): SalesFacadeInterface
    {
        return $this->getLocator()->sales()->facade();
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(array $override = []): QuoteTransfer
    {
        return (new QuoteBuilder($override))
            ->withStore($override)
            ->withItem($override)
            ->withCustomer($override)
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    protected function executeSaveOrderPlugins(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        foreach ($this->saveOrderStack as $orderSaver) {
            $orderSaver->saveOrder($quoteTransfer, $saveOrderTransfer);
        }
    }

    /**
     * @return void
     */
    public function cleanStaticProperty(): void
    {
        $reflectedClass = new ReflectionClass(PersistenceManager::class);

        $stateCache = $reflectedClass->getProperty('stateCache');
        $stateCache->setAccessible(true);
        $stateCache->setValue(null);

        $processCache = $reflectedClass->getProperty('processCache');
        $processCache->setAccessible(true);
        $processCache->setValue(null);
    }

    /**
     * @param array<mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function haveSalesExpense(array $seedData = []): ExpenseTransfer
    {
        $expenseTransfer = (new ExpenseBuilder($seedData))->build();

        $salesExpenseEntity = SpySalesExpenseQuery::create()
            ->filterByUuid($expenseTransfer->getUuid())
            ->findOneOrCreate();

        $salesExpenseEntity = $this->createSalesExpenseMapper()->mapExpenseTransferToSalesExpenseEntity($expenseTransfer, $salesExpenseEntity);
        $salesExpenseEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($salesExpenseEntity): void {
            $salesExpenseEntity->delete();
        });

        return $this->createSalesExpenseMapper()->mapExpenseEntityToSalesExpenseTransfer($expenseTransfer, $salesExpenseEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function saveSalesOrderAddress(AddressTransfer $addressTransfer): AddressTransfer
    {
        $salesOrderAddressEntity = $this->createSalesOrderAddressQuery()
            ->filterByIdSalesOrderAddress($addressTransfer->getIdSalesOrderAddress())
            ->findOneOrCreate();

        $salesOrderAddressEntity->fromArray($addressTransfer->toArray());
        $salesOrderAddressEntity->save();

        return $addressTransfer->fromArray($salesOrderAddressEntity->toArray(), true);
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery
     */
    protected function createSalesOrderAddressQuery(): SpySalesOrderAddressQuery
    {
        return SpySalesOrderAddressQuery::create();
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return void
     */
    protected function cleanupSalesOrderAddress(AddressTransfer $addressTransfer): void
    {
        $this->createSalesOrderAddressQuery()
            ->findByIdSalesOrderAddress($addressTransfer->getIdSalesOrderAddress())
            ->delete();
    }

    /**
     * @return \Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesExpenseMapperInterface
     */
    protected function createSalesExpenseMapper(): SalesExpenseMapperInterface
    {
        return new SalesExpenseMapper();
    }
}
