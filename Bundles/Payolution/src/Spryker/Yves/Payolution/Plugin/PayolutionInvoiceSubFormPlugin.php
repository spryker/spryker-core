<?php
/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */
namespace Spryker\Yves\Payolution\Plugin;

use Spryker\Yves\Checkout\Dependency\Plugin\CheckoutSubFormPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\Payolution\PayolutionFactory getFactory()
 */
class PayolutionInvoiceSubFormPlugin extends AbstractPlugin implements CheckoutSubFormPluginInterface
{

    /**
     * @return \Spryker\Yves\Payolution\Form\InvoiceSubForm
     */
    public function createSubForm()
    {
        return $this->getFactory()->createInvoiceForm();
    }

    /**
     * @return \Spryker\Yves\Checkout\Dependency\DataProvider\DataProviderInterface
     */
    public function createSubFormDataProvider()
    {
        return $this->getFactory()->createInvoiceFormDataProvider();
    }

}
