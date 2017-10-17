<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Ratepay\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface;

/**
 * @method \Spryker\Yves\Ratepay\RatepayFactory getFactory()
 */
class RatepayPrepaymentSubFormPlugin extends AbstractPlugin implements SubFormPluginInterface
{
    /**
     * @return \Spryker\Yves\Ratepay\Form\PrepaymentSubForm
     */
    public function createSubForm()
    {
        return $this->getFactory()->createPrepaymentForm();
    }

    /**
     * @return \Spryker\Yves\Ratepay\Form\DataProvider\PrepaymentDataProvider
     */
    public function createSubFormDataProvider()
    {
        return $this->getFactory()->createPrepaymentFormDataProvider();
    }
}
