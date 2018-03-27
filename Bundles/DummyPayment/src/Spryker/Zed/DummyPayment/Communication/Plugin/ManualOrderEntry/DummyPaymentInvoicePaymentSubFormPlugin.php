<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DummyPayment\Communication\Plugin\ManualOrderEntry;

use Spryker\Shared\DummyPayment\DummyPaymentConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ManualOrderEntryGuiExtension\Dependency\Plugin\PaymentSubFormPluginInterface;

/**
 * @method \Spryker\Zed\DummyPayment\Business\DummyPaymentFacadeInterface getFacade()
 * @method \Spryker\Zed\DummyPayment\Communication\DummyPaymentCommunicationFactory getFactory()
 */
class DummyPaymentInvoicePaymentSubFormPlugin extends AbstractPlugin implements PaymentSubFormPluginInterface
{
    const PAYMENT_PROVIDER = 'DummyPayment';

    /**
     * @return \Spryker\Zed\Kernel\Communication\Form\AbstractType
     */
    public function createSubForm()
    {
        return $this->getFactory()->createInvoiceForm();
    }

    /**
     * @return string
     */
    public function getPropertyPath()
    {
        return DummyPaymentConfig::PAYMENT_METHOD_INVOICE;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return DummyPaymentConfig::PAYMENT_METHOD_INVOICE;
    }

    /**
     * @return string
     */
    public function getPaymentProvider()
    {
        return static::PAYMENT_PROVIDER;
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return 'invoice';
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getData($dataTransfer)
    {
        return $this->getFactory()->createInvoiceFormDataProvider()->getData($dataTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return array
     */
    public function getOptions($dataTransfer)
    {
        return $this->getFactory()->createInvoiceFormDataProvider()->getOptions($dataTransfer);
    }
}
