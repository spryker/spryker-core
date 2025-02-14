<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDataChangeRequest\Persistence;

use Generated\Shared\Transfer\CustomerDataChangeRequestTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CustomerDataChangeRequest\Persistence\CustomerDataChangeRequestPersistenceFactory getFactory()
 */
class CustomerDataChangeRequestEntityManager extends AbstractEntityManager implements CustomerDataChangeRequestEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerDataChangeRequestTransfer
     */
    public function saveEmailCustomerDataChangeRequest(CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer): CustomerDataChangeRequestTransfer
    {
        $customerDataChangeRequestEntity = $this->getFactory()
            ->createCustomerDataChangeRequestQuery()
            ->filterByFkCustomer($customerDataChangeRequestTransfer->getIdCustomer())
            ->filterByVerificationToken($customerDataChangeRequestTransfer->getVerificationToken())
            ->findOneOrCreate();

        $customerDataChangeRequestEntity = $this->getFactory()
            ->createCustomerDataChangeRequestMapper()
            ->mapCustomerDataChangeRequestTransferToCustomerDataChangeRequestEntity($customerDataChangeRequestTransfer, $customerDataChangeRequestEntity);

        if ($customerDataChangeRequestEntity->isNew() || $customerDataChangeRequestEntity->isModified()) {
            $customerDataChangeRequestEntity->save();
        }

        return $this->getFactory()
            ->createCustomerDataChangeRequestMapper()
            ->mapCustomerDataChangeRequestEntityToCustomerDataChangeRequestTransfer($customerDataChangeRequestEntity, $customerDataChangeRequestTransfer);
    }
}
