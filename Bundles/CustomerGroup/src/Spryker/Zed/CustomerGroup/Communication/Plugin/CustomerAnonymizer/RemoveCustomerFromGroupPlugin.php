<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Communication\Plugin\CustomerAnonymizer;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Customer\Dependency\Plugin\CustomerAnonymizerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CustomerGroup\Business\CustomerGroupFacadeInterface getFacade()
 * @method \Spryker\Zed\CustomerGroup\Communication\CustomerGroupCommunicationFactory getFactory()
 */
class RemoveCustomerFromGroupPlugin extends AbstractPlugin implements CustomerAnonymizerPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function process(CustomerTransfer $customerTransfer)
    {
        $this->getFacade()->removeCustomerFromAllGroups($customerTransfer);
    }
}
