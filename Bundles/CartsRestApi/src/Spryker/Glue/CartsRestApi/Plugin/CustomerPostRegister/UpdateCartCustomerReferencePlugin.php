<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Plugin\CustomerPostRegister;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\CustomersRestApiExtension\Dependency\Plugin\CustomerPostRegisterPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\CartsRestApi\CartsRestApiFactory getFactory()
 */
class UpdateCartCustomerReferencePlugin extends AbstractPlugin implements CustomerPostRegisterPluginInterface
{
    /**
     * {@inheritdoc}
     *  - Updates cart of guest customer with customer reference after registration.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function postRegister(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        return $this->getFactory()
            ->createGuestCartUpdater()
            ->updateGuestCartCustomerReferenceOnRegistration($customerTransfer);
    }
}
