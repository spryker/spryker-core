<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DummyPayment\Communication;

use Spryker\Zed\DummyPayment\Communication\Form\DataProvider\DummyPaymentInvoiceFormDataProvider;
use Spryker\Zed\DummyPayment\Communication\Form\InvoiceSubForm;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\DummyPayment\DummyPaymentConfig getConfig()
 */
class DummyPaymentCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\DummyPayment\Communication\Form\InvoiceSubForm
     */
    public function createInvoiceForm(): InvoiceSubForm
    {
        return new InvoiceSubForm();
    }

    /**
     * @return \Spryker\Zed\DummyPayment\Communication\Form\DataProvider\DummyPaymentInvoiceFormDataProvider
     */
    public function createInvoiceFormDataProvider(): DummyPaymentInvoiceFormDataProvider
    {
        return new DummyPaymentInvoiceFormDataProvider();
    }
}
