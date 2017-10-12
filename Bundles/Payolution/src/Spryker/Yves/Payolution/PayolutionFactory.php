<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Payolution;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Money\Plugin\MoneyPlugin;
use Spryker\Yves\Payolution\Form\DataProvider\InstallmentFormDataProvider;
use Spryker\Yves\Payolution\Form\DataProvider\InvoiceFormDataProvider;
use Spryker\Yves\Payolution\Form\InstallmentSubForm;
use Spryker\Yves\Payolution\Form\InvoiceSubForm;
use Spryker\Yves\Payolution\Handler\PayolutionHandler;

class PayolutionFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\Payolution\Form\InvoiceSubForm
     */
    public function createInvoiceForm()
    {
        return new InvoiceSubForm();
    }

    /**
     * @return \Spryker\Yves\Payolution\Form\InstallmentSubForm
     */
    public function createInstallmentForm()
    {
        return new InstallmentSubForm();
    }

    /**
     * @return \Spryker\Yves\Payolution\Form\DataProvider\InstallmentFormDataProvider
     */
    public function createInstallmentFormDataProvider()
    {
        return new InstallmentFormDataProvider($this->getPayolutionClient(), $this->getMoneyPlugin());
    }

    /**
     * @return \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    public function getMoneyPlugin()
    {
        return new MoneyPlugin();
    }

    /**
     * @return \Spryker\Yves\Payolution\Form\DataProvider\InvoiceFormDataProvider
     */
    public function createInvoiceFormDataProvider()
    {
        return new InvoiceFormDataProvider();
    }

    /**
     * @return \Spryker\Yves\Payolution\Handler\PayolutionHandler
     */
    public function createPayolutionHandler()
    {
        return new PayolutionHandler($this->getPayolutionClient());
    }

    /**
     * @return \Spryker\Client\Payolution\PayolutionClientInterface
     */
    public function getPayolutionClient()
    {
        return $this->getProvidedDependency(PayolutionDependencyProvider::CLIENT_PAYOLUTION);
    }
}
