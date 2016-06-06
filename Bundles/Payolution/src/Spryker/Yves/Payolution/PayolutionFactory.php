<?php

/**
 * This file is part of the Spryker Platform.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Yves\Payolution;

use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Yves\Kernel\AbstractFactory;
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
        return new InstallmentFormDataProvider($this->getPayolutionClient(), $this->createCurrencyManager());
    }

    /**
     * @return \Spryker\Shared\Library\Currency\CurrencyManager
     */
    public function createCurrencyManager()
    {
        return CurrencyManager::getInstance();
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
        return new PayolutionHandler($this->getPayolutionClient(), $this->createCurrencyManager());
    }

    /**
     * @return \Spryker\Client\Payolution\PayolutionClientInterface
     */
    public function getPayolutionClient()
    {
        return $this->getProvidedDependency(PayolutionDependencyProvider::CLIENT_PAYOLUTION);
    }

}
