<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\AsyncApi\TaxApp;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\DataBuilder\ExpenseBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\DataBuilder\ShipmentMethodBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use ReflectionProperty;
use Spryker\Shared\Oms\OmsConstants;
use Spryker\Zed\Oms\Business\OrderStateMachine\PersistenceManager;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use SprykerTest\Zed\TaxApp\Business\TaxAppFacadeCalculationTest;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\TaxApp\Business\TaxAppFacadeInterface getFacade(?string $moduleName = NULL)
 *
 * @SuppressWarnings(\SprykerTest\AsyncApi\TaxApp\PHPMD)
 */
class AsyncApiTester extends Actor
{
    use _generated\AsyncApiTesterActions;

    /**
     * @param string $stateMachineProcessName
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createOrderByStateMachineProcessName(string $stateMachineProcessName): OrderTransfer
    {
        $quoteTransfer = $this->buildFakeQuote(
            $this->haveCustomer(),
            $this->haveStore([StoreTransfer::NAME => 'DE']),
        );

        $saveOrderTransfer = $this->haveOrderFromQuote($quoteTransfer, $stateMachineProcessName);

        return (new OrderTransfer())
            ->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setStore($quoteTransfer->getStore()->getName())
            ->setCustomer($quoteTransfer->getCustomer())
            ->setItems($saveOrderTransfer->getOrderItems())
            ->setExpenses($quoteTransfer->getExpenses())
            ->setBillingAddress($quoteTransfer->getBillingAddress());
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderTransferForSubmitPaymentTaxInvoice(): OrderTransfer
    {
        $orderTransfer = $this->createOrderByStateMachineProcessName(TaxAppFacadeCalculationTest::DEFAULT_OMS_PROCESS_NAME);
        $orderTransfer->setCreatedAt(date('Y-m-d h:i:s'));
        $orderTransfer->setEmail($orderTransfer->getCustomer()->getEmail());

        foreach ($orderTransfer->getItems() as $item) {
            $item->setSku('some_sku');
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function mockSalesFacadeFindOrderByIdSalesOrderMethod(OrderTransfer $orderTransfer): void
    {
        $salesFacadeMock = Stub::makeEmpty(SalesFacadeInterface::class, [
            'findOrderByIdSalesOrder' => $orderTransfer,
        ]);

        $this->addToLocatorCache('sales-facade', $salesFacadeMock);
    }

    /**
     * @param array $activeProcesses
     * @param string|null $xmlFolder
     *
     * @return void
     */
    public function configureTestStateMachine(array $activeProcesses, ?string $xmlFolder = null): void
    {
        $this->clearPersistenceManagerCache();

        if (!$xmlFolder) {
            $xmlFolder = realpath(__DIR__ . '/../../../../../_data/state-machine/');
        }

        $this->setConfig(OmsConstants::PROCESS_LOCATION, $xmlFolder);
        $this->setConfig(OmsConstants::ACTIVE_PROCESSES, $activeProcesses);
    }

    /**
     * @return void
     */
    protected function clearPersistenceManagerCache(): void
    {
        $stateCacheProperty = new ReflectionProperty(PersistenceManager::class, 'stateCache');
        $stateCacheProperty->setAccessible(true);
        $stateCacheProperty->setValue([]);
        $processCacheProperty = new ReflectionProperty(PersistenceManager::class, 'processCache');
        $processCacheProperty->setAccessible(true);
        $processCacheProperty->setValue([]);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function buildFakeQuote(CustomerTransfer $customerTransfer, StoreTransfer $storeTransfer): QuoteTransfer
    {
        $shipmentBuilder = (new ShipmentBuilder())
            ->withShippingAddress()
            ->withMethod((new ShipmentMethodBuilder())->withPrice());

        $expenseBuilder = (new ExpenseBuilder([ExpenseTransfer::TYPE => 'SHIPMENT_EXPENSE_TYPE']))
            ->withShipment($shipmentBuilder);

        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = (new QuoteBuilder())
            ->withItem((new ItemBuilder())->withShipment($shipmentBuilder))
            ->withShipment($shipmentBuilder)
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->withExpense($expenseBuilder)
            ->build();

        $quoteTransfer
            ->setCustomer($customerTransfer)
            ->setStore($storeTransfer);

        return $quoteTransfer;
    }
}
