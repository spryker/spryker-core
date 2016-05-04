<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Checkout\Dependency\Plugin;

interface CheckoutSubFormPluginInterface
{

    /**
     *
     * @return \Spryker\Yves\Checkout\Dependency\Form\SubFormInterface
     */
    public function createSubForm();

    /**
     * @return \Spryker\Yves\Checkout\Dependency\DataProvider\DataProviderInterface
     */
    public function createSubFormDataProvider();

}
