<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesInvoice;

use Codeception\Actor;
use Generated\Shared\Transfer\OrderInvoiceTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesInvoice\Business\SalesInvoiceFacade;

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
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesInvoiceBusinessTester extends Actor
{
    use _generated\SalesInvoiceBusinessTesterActions;

    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @return void
     */
    public function prepareTestStateMachine(): void
    {
        $this->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return \Spryker\Zed\SalesInvoice\Business\SalesInvoiceFacade
     */
    public function getMockedFacade(): SalesInvoiceFacade
    {
        $factory = $this->getFactory();
        $factory->setConfig(new SalesInvoiceConfigMock());

        $facade = $this->getFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderInvoiceTransfer
     */
    public function createInvoice(): OrderInvoiceTransfer
    {
        return $this->haveOrderInvoice($this->createOrder()->getIdSalesOrder());
    }

    /**
     * @param int $number
     *
     * @return \Generated\Shared\Transfer\OrderInvoiceTransfer[]
     */
    public function createInvoiceCollection(int $number): array
    {
        $invoiceCollection = [];
        for ($i = 1; $i <= $number; $i++) {
            $invoiceCollection[] = $this->createInvoice();
        }

        return $invoiceCollection;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createOrder(): OrderTransfer
    {
        $saveOrderTransfer = $this->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);

        return (new OrderTransfer())
            ->fromArray($saveOrderTransfer->toArray(), true);
    }
}
