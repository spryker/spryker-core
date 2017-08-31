<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Payment\Plugin;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\Checkout\Dependency\Plugin\Form\SubFormFilterPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;

/**
 * @method \Spryker\Yves\Payment\PaymentFactory getFactory()
 */
class PaymentFormFilterPlugin extends AbstractPlugin implements SubFormFilterPluginInterface
{

    /**
     * @api
     *
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection $formPluginCollection
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $data
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection
     */
    public function filter(SubFormPluginCollection $formPluginCollection, AbstractTransfer $data)
    {
        return $this->getFactory()->createPaymentMethodFormFilter()->filter($formPluginCollection, $data);
    }

}
