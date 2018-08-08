<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomersRestApi\Business;

/**
 * @method \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiBusinessFactory getFactory()
 */
class CustomersRestApiFacade implements CustomersRestApiFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function updateCustomerAddressUuid(): void
    {
        $this->getFactory()->createCustomersAddressesUuidUpdater()->updateAddressesUuid();
    }
}
