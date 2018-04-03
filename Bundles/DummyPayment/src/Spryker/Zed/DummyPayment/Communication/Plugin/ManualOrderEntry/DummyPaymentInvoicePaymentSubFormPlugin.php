<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DummyPayment\Communication\Plugin\ManualOrderEntry;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\DummyPayment\DummyPaymentConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
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
    public function createSubForm(): AbstractType
    {
        return $this->getFactory()->createInvoiceForm();
    }

    /**
     * @return string
     */
    public function getPropertyPath(): string
    {
        return DummyPaymentConfig::PAYMENT_METHOD_INVOICE;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return DummyPaymentConfig::PAYMENT_METHOD_INVOICE;
    }

    /**
     * @return string
     */
    public function getPaymentProvider(): string
    {
        return static::PAYMENT_PROVIDER;
    }

    /**
     * @return string
     */
    public function getPaymentMethod(): string
    {
        return 'invoice';
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData($dataTransfer): QuoteTransfer
    {
        return $this->getFactory()->createInvoiceFormDataProvider()->getData($dataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     *
     * @return array
     */
    public function getOptions($dataTransfer): array
    {
        return $this->getFactory()->createInvoiceFormDataProvider()->getOptions($dataTransfer);
    }
}
