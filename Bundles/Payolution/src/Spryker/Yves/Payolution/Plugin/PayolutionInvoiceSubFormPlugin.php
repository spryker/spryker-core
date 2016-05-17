<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Payolution\Plugin;

use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\Payolution\PayolutionFactory getFactory()
 */
class PayolutionInvoiceSubFormPlugin extends AbstractPlugin implements SubFormPluginInterface
{

    /**
     * @return \Spryker\Yves\Payolution\Form\InvoiceSubForm
     */
    public function createSubForm()
    {
        return $this->getFactory()->createInvoiceForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\DataProvider\DataProviderInterface
     */
    public function createSubFormDataProvider()
    {
        return $this->getFactory()->createInvoiceFormDataProvider();
    }

}
