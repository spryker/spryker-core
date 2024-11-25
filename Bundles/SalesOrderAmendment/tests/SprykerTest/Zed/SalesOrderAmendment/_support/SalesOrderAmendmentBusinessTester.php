<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentRequestTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentTransfer;
use Orm\Zed\SalesOrderAmendment\Persistence\SpySalesOrderAmendment;
use Orm\Zed\SalesOrderAmendment\Persistence\SpySalesOrderAmendmentQuery;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentValidatorRulePluginInterface;

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
 * @method \Spryker\Zed\SalesOrderAmendment\Business\SalesOrderAmendmentFacadeInterface getFacade(?string $moduleName = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesOrderAmendmentBusinessTester extends Actor
{
    use _generated\SalesOrderAmendmentBusinessTesterActions;

    /**
     * @var string
     */
    public const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentRequestTransfer
     */
    public function createSalesOrderAmendmentRequestTransfer(): SalesOrderAmendmentRequestTransfer
    {
        $customerTransfer = $this->haveCustomer();
        $amendmentOrderTransfer = $this->haveOrderFromQuote(
            $this->createQuoteTransfer($customerTransfer),
            static::DEFAULT_OMS_PROCESS_NAME,
        );
        $amendedOrderTransfer = $this->haveOrderFromQuote(
            $this->createQuoteTransfer($customerTransfer),
            static::DEFAULT_OMS_PROCESS_NAME,
        );

        return (new SalesOrderAmendmentRequestTransfer())
            ->setOriginalOrderReference($amendmentOrderTransfer->getOrderReferenceOrFail())
            ->setAmendedOrderReference($amendedOrderTransfer->getOrderReferenceOrFail());
    }

    /**
     * @param array<string, mixed> $seedData
     * @param string $stateMachineProcessName
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentTransfer
     */
    public function createSalesOrderAmendment(
        array $seedData = [],
        string $stateMachineProcessName = self::DEFAULT_OMS_PROCESS_NAME
    ): SalesOrderAmendmentTransfer {
        if (!isset($seedData[SalesOrderAmendmentTransfer::ORIGINAL_ORDER_REFERENCE])) {
            $orderTransfer = $this->haveOrder([], $stateMachineProcessName);
            $seedData[SalesOrderAmendmentTransfer::ORIGINAL_ORDER_REFERENCE] = $orderTransfer->getOrderReferenceOrFail();
        }

        if (!isset($seedData[SalesOrderAmendmentTransfer::AMENDED_ORDER_REFERENCE])) {
            $orderTransfer = $this->haveOrder([], $stateMachineProcessName);
            $seedData[SalesOrderAmendmentTransfer::AMENDED_ORDER_REFERENCE] = $orderTransfer->getOrderReferenceOrFail();
        }

        return $this->haveSalesOrderAmendment($seedData);
    }

    /**
     * @param string $orderReference
     *
     * @return \Orm\Zed\SalesOrderAmendment\Persistence\SpySalesOrderAmendment|null
     */
    public function findSalesOrderAmendmentByOriginalOrderReference(string $orderReference): ?SpySalesOrderAmendment
    {
        return $this->getSalesOrderAmendmentQuery()
            ->filterByOriginalOrderReference($orderReference)
            ->findOne();
    }

    /**
     * @return void
     */
    public function ensureSalesOrderAmendmentTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getSalesOrderAmendmentQuery());
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(CustomerTransfer $customerTransfer): QuoteTransfer
    {
        return (new QuoteBuilder([QuoteTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReferenceOrFail()]))
                ->withStore()
                ->withItem()
                ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReferenceOrFail()])
                ->withTotals()
                ->withShippingAddress()
                ->withBillingAddress()
                ->withCurrency()
                ->build();
    }

    /**
     * @return \Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentValidatorRulePluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    public function createSalesOrderAmendmentValidatorRulePluginMock(): SalesOrderAmendmentValidatorRulePluginInterface
    {
        $salesOrderAmendmentValidatorRulePluginMock = Stub::makeEmpty(SalesOrderAmendmentValidatorRulePluginInterface::class);
        $salesOrderAmendmentValidatorRulePluginMock
            ->expects(new InvokedCount(1))
            ->method('validate')
            ->willReturn(new ErrorCollectionTransfer());

        return $salesOrderAmendmentValidatorRulePluginMock;
    }

    /**
     * @return \Orm\Zed\SalesOrderAmendment\Persistence\SpySalesOrderAmendmentQuery
     */
    protected function getSalesOrderAmendmentQuery(): SpySalesOrderAmendmentQuery
    {
        return SpySalesOrderAmendmentQuery::create();
    }
}
