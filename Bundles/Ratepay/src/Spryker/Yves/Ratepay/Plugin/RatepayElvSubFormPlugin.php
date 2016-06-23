<?php
/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
