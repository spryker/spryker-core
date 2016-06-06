<?php

/**
 * This file is part of the Spryker Platform.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Yves\Payolution\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface;

/**
 * @method \Spryker\Yves\Payolution\PayolutionFactory getFactory()
 */
class PayolutionInstallmentSubFormPlugin extends AbstractPlugin implements SubFormPluginInterface
{

    /**
     * @return \Spryker\Yves\Payolution\Form\InstallmentSubForm
     */
    public function createSubForm()
    {
        return $this->getFactory()->createInstallmentForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createSubFormDataProvider()
    {
        return $this->getFactory()->createInstallmentFormDataProvider();
    }

}
