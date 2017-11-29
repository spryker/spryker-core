<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Payment\Form\Filter;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;

interface PaymentFormFilterInterface
{
    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection $formPluginCollection
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $data
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection
     */
    public function filter(SubFormPluginCollection $formPluginCollection, AbstractTransfer $data);
}
