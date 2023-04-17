<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\SalesOrdersBackendApi;

use Codeception\Actor;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Glue\SalesOrdersBackendApi\SalesOrdersBackendApiResourceInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Glue\SalesOrdersBackendApi\SalesOrdersBackendApiResourceInterface
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesOrdersBackendApiTester extends Actor
{
    use _generated\SalesOrdersBackendApiTesterActions;

    /**
     * @var string
     */
    public const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @return \Spryker\Glue\SalesOrdersBackendApi\SalesOrdersBackendApiResourceInterface
     */
    public function getSalesOrdersBackendApiResource(): SalesOrdersBackendApiResourceInterface
    {
        return $this->getLocator()->salesOrdersBackendApi()->resource();
    }

    /**
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function createSaveOrderTransfer(): SaveOrderTransfer
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withCustomer()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->withItem()
            ->build();

        return $this->haveOrderFromQuote(
            $quoteTransfer,
            static::DEFAULT_OMS_PROCESS_NAME,
        );
    }
}
