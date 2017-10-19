<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Ratepay;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Ratepay\Form\DataProvider\ElvDataProvider;
use Spryker\Yves\Ratepay\Form\DataProvider\InstallmentDataProvider;
use Spryker\Yves\Ratepay\Form\DataProvider\InvoiceDataProvider;
use Spryker\Yves\Ratepay\Form\DataProvider\PrepaymentDataProvider;
use Spryker\Yves\Ratepay\Form\ElvSubForm;
use Spryker\Yves\Ratepay\Form\InstallmentSubForm;
use Spryker\Yves\Ratepay\Form\InvoiceSubForm;
use Spryker\Yves\Ratepay\Form\PrepaymentSubForm;
use Spryker\Yves\Ratepay\Handler\RatepayHandler;

/**
 * @method \Spryker\Zed\Ratepay\RatepayConfig getConfig()
 */
class RatepayFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\Ratepay\Handler\RatepayHandler
     */
    public function createRatepayHandler()
    {
        return new RatepayHandler(
            $this->getRatepayClient()
        );
    }

    /**
     * @return \Spryker\Yves\Ratepay\Form\InvoiceSubForm
     */
    public function createInvoiceForm()
    {
        return new InvoiceSubForm();
    }

    /**
     * @return \Spryker\Yves\Ratepay\Form\DataProvider\InvoiceDataProvider
     */
    public function createInvoiceFormDataProvider()
    {
        return new InvoiceDataProvider();
    }

    /**
     * @return \Spryker\Yves\Ratepay\Form\ElvSubForm
     */
    public function createElvForm()
    {
        return new ElvSubForm();
    }

    /**
     * @return \Spryker\Yves\Ratepay\Form\DataProvider\ElvDataProvider
     */
    public function createElvFormDataProvider()
    {
        return new ElvDataProvider();
    }

    /**
     * @return \Spryker\Yves\Ratepay\Form\PrepaymentSubForm
     */
    public function createPrepaymentForm()
    {
        return new PrepaymentSubForm();
    }

    /**
     * @return \Spryker\Yves\Ratepay\Form\DataProvider\PrepaymentDataProvider
     */
    public function createPrepaymentFormDataProvider()
    {
        return new PrepaymentDataProvider();
    }

    /**
     * @return \Spryker\Yves\Ratepay\Form\InstallmentSubForm
     */
    public function createInstallmentForm()
    {
        return new InstallmentSubForm();
    }

    /**
     * @return \Spryker\Yves\Ratepay\Form\DataProvider\InstallmentDataProvider
     */
    public function createInstallmentFormDataProvider()
    {
        return new InstallmentDataProvider(
            $this->getRatepayClient(),
            $this->getSessionClient()
        );
    }

    /**
     * @return \Spryker\Client\Ratepay\RatepayClientInterface
     */
    public function getRatepayClient()
    {
        return $this->getProvidedDependency(RatepayDependencyProvider::CLIENT_RATEPAY);
    }

    /**
     * @return \Spryker\Client\Session\SessionClientInterface
     */
    protected function getSessionClient()
    {
        return $this->getProvidedDependency(RatepayDependencyProvider::CLIENT_SESSION);
    }
}
