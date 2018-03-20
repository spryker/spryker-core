<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DummyPayment\Communication\Plugin\Checkout;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\Payment\SubFormPluginInterface;

/**
 * @method \Spryker\Zed\DummyPayment\Business\DummyPaymentFacadeInterface getFacade()
 * @method \Spryker\Zed\DummyPayment\Communication\DummyPaymentCommunicationFactory getFactory()
 */
class DummyPaymentInvoiceSubFormPlugin extends AbstractPlugin implements SubFormPluginInterface
{

    /**
     * @return AbstractType
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
//        return $this->getFactory()->createInvoiceForm()->getPropertyPath();
        return 'path in a main form';
    }

    /**
     * @return string
     */
    public function getName()
    {
//        return $this->getFactory()->createInvoiceForm()->getPropertyPath();
        return 'name';
    }

//    /**
//     * @return
//     */
//    public function createSubFormDataProvider()
//    {
//        return $this->getFactory()->createInvoiceFormDataProvider();
//    }

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
     * @return array|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */

    public function getOptions($dataTransfer)
    {
        return $this->getFactory()->createInvoiceFormDataProvider()->getData($dataTransfer);
    }


}
