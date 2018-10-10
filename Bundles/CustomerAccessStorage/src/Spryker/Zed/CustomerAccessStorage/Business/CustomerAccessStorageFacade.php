<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CustomerAccessStorage\Business\CustomerAccessStorageBusinessFactory getFactory()
 */
class CustomerAccessStorageFacade extends AbstractFacade implements CustomerAccessStorageFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function publish(): void
    {
        $this->getFactory()->createCustomerAccessStorage()->publish();
    }
}
