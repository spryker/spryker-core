<?php
/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */
namespace Spryker\Yves\Payolution;

use Spryker\Yves\Payolution\Form\DataProvider\InstallmentDataProvider;
use Spryker\Yves\Payolution\Form\DataProvider\InvoiceDataProvider;
use Spryker\Yves\Payolution\Form\InstallmentSubForm;
use Spryker\Yves\Payolution\Form\InvoiceSubForm;
use Spryker\Yves\Payolution\Handler\PayolutionHandler;
use Spryker\Yves\Kernel\AbstractFactory;

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
     * @return \Spryker\Yves\Payolution\Form\DataProvider\InstallmentDataProvider
     */
    public function createInstallmentFormDataProvider()
    {
        return new InstallmentDataProvider($this->getPayolutionClient());
    }

    /**
     * @return \Spryker\Yves\Payolution\Form\DataProvider\InvoiceDataProvider
     */
    public function createInvoiceFormDataProvider()
    {
        return new InvoiceDataProvider();
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
