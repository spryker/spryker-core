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
class RatepayElvSubFormPlugin extends AbstractPlugin implements SubFormPluginInterface
{
    /**
     * @return \Spryker\Yves\Ratepay\Form\ElvSubForm
     */
    public function createSubForm()
    {
        return $this->getFactory()->createElvForm();
    }

    /**
     * @return \Spryker\Yves\Ratepay\Form\DataProvider\ElvDataProvider
     */
    public function createSubFormDataProvider()
    {
        return $this->getFactory()->createElvFormDataProvider();
    }
}
