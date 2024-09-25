<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesDiscountConnector;

use Codeception\Actor;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\SalesDiscountConnector\Business\SalesDiscountConnectorFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\SalesDiscountConnector\PHPMD)
 */
class SalesDiscountConnectorBusinessTester extends Actor
{
    use _generated\SalesDiscountConnectorBusinessTesterActions;

    /**
     * @see \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators::TYPE_NUMBER
     *
     * @var string
     */
    protected const TYPE_NUMBER = 'number';

    /**
     * @use \Spryker\Zed\SalesDiscountConnector\Communication\Plugin\Discount\CustomerOrderCountDecisionRulePlugin::FIELD_NAME_CUSTOMER_ORDER_COUNT
     *
     * @var string
     */
    protected const FIELD_NAME_CUSTOMER_ORDER_COUNT = 'customer-order-count';

    /**
     * @param string $operator
     * @param string $value
     *
     * @return \Generated\Shared\Transfer\ClauseTransfer
     */
    public function createClauseTransfer(string $operator, string $value): ClauseTransfer
    {
        return (new ClauseTransfer())
            ->setOperator($operator)
            ->setField(static::FIELD_NAME_CUSTOMER_ORDER_COUNT)
            ->setValue($value)
            ->setAcceptedTypes([static::TYPE_NUMBER]);
    }

    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function buildQuote(array $seed = []): QuoteTransfer
    {
        return (new QuoteBuilder($seed))
            ->withItem()
            ->withStore()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->build();
    }
}
