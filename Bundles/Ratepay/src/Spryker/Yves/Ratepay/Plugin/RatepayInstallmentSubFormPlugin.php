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
class RatepayInstallmentSubFormPlugin extends AbstractPlugin implements SubFormPluginInterface
{
    /**
     * @return \Spryker\Yves\Ratepay\Form\InstallmentSubForm
     */
    public function createSubForm()
    {
        return $this->getFactory()->createInstallmentForm();
    }

    /**
     * @return \Spryker\Yves\Ratepay\Form\DataProvider\InstallmentDataProvider
     */
    public function createSubFormDataProvider()
    {
        return $this->getFactory()->createInstallmentFormDataProvider();
    }
}
