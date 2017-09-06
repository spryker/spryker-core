<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnectorGui\Communication\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Customer\Dependency\Plugin\CustomerTransferExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CustomerUserConnector\Communication\CustomerUserConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\CustomerUserConnectorGui\Business\CustomerUserConnectorGuiFacade getFacade()
 */
class CustomerTransferUsernameExpanderPlugin extends AbstractPlugin implements CustomerTransferExpanderPluginInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expandTransfer(CustomerTransfer $customerTransfer)
    {
        return $this->addUsername($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function addUsername(CustomerTransfer $customerTransfer)
    {
        $userCollection = $this->getFactory()
            ->getUserQueryContainer()
            ->queryUserById($customerTransfer->getFkUser())
            ->find();

        if ($userCollection->count() < 1) {
            return $customerTransfer;
        }

        return $customerTransfer->setUsername($userCollection[0]->getUsername());
    }

}
