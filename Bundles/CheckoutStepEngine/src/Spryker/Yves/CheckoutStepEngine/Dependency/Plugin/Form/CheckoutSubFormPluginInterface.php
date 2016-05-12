<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CheckoutStepEngine\Dependency\Plugin\Form;

interface CheckoutSubFormPluginInterface
{

    /**
     *
     * @return \Spryker\Yves\CheckoutStepEngine\Dependency\Form\SubFormInterface
     */
    public function createSubForm();

    /**
     * @return \Spryker\Yves\CheckoutStepEngine\Dependency\DataProvider\DataProviderInterface
     */
    public function createSubFormDataProvider();

}
