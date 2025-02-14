<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDataChangeRequest\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CustomerDataChangeRequestCollectionTransfer;
use Generated\Shared\Transfer\CustomerDataChangeRequestTransfer;
use Orm\Zed\CustomerDataChangeRequest\Persistence\SpyCustomerDataChangeRequest;
use Propel\Runtime\Collection\Collection;

class CustomerDataChangeRequestMapper
{
    /**
     * @param \Generated\Shared\Transfer\CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer
     * @param \Orm\Zed\CustomerDataChangeRequest\Persistence\SpyCustomerDataChangeRequest $customerDataChangeRequestEntity
     *
     * @return \Orm\Zed\CustomerDataChangeRequest\Persistence\SpyCustomerDataChangeRequest
     */
    public function mapCustomerDataChangeRequestTransferToCustomerDataChangeRequestEntity(
        CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer,
        SpyCustomerDataChangeRequest $customerDataChangeRequestEntity
    ): SpyCustomerDataChangeRequest {
        $customerDataChangeRequestEntity
            ->setFkCustomer($customerDataChangeRequestTransfer->getIdCustomerOrFail())
            ->setData($customerDataChangeRequestTransfer->getDataOrFail())
            ->setVerificationToken($customerDataChangeRequestTransfer->getVerificationTokenOrFail())
            ->setType($customerDataChangeRequestTransfer->getTypeOrFail())
            ->setStatus($customerDataChangeRequestTransfer->getStatusOrFail());

        if ($customerDataChangeRequestTransfer->getIdCustomerDataChangeRequest() !== null) {
            $customerDataChangeRequestEntity->setIdCustomerDataChangeRequest($customerDataChangeRequestTransfer->getIdCustomerDataChangeRequest());
        }

        return $customerDataChangeRequestEntity;
    }

    /**
     * @param \Orm\Zed\CustomerDataChangeRequest\Persistence\SpyCustomerDataChangeRequest $customerDataChangeRequestEntity
     * @param \Generated\Shared\Transfer\CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerDataChangeRequestTransfer
     */
    public function mapCustomerDataChangeRequestEntityToCustomerDataChangeRequestTransfer(
        SpyCustomerDataChangeRequest $customerDataChangeRequestEntity,
        CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer
    ): CustomerDataChangeRequestTransfer {
        return $customerDataChangeRequestTransfer
            ->setIdCustomer($customerDataChangeRequestEntity->getFkCustomer())
            ->setType($customerDataChangeRequestEntity->getType())
            ->setStatus($customerDataChangeRequestEntity->getStatus())
            ->setData($customerDataChangeRequestEntity->getData())
            ->setVerificationToken($customerDataChangeRequestEntity->getVerificationToken())
            ->setIdCustomerDataChangeRequest($customerDataChangeRequestEntity->getIdCustomerDataChangeRequest());
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\CustomerDataChangeRequest\Persistence\SpyCustomerDataChangeRequest> $customerDataChangeRequestEntities
     * @param \Generated\Shared\Transfer\CustomerDataChangeRequestCollectionTransfer $customerDataChangeRequestCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerDataChangeRequestCollectionTransfer
     */
    public function mapCustomerDataChangeRequestCollectionToCustomerDataChangeRequestCollectionTransfer(
        Collection $customerDataChangeRequestEntities,
        CustomerDataChangeRequestCollectionTransfer $customerDataChangeRequestCollectionTransfer
    ): CustomerDataChangeRequestCollectionTransfer {
        $customerDataChangeRequests = new ArrayObject();

        foreach ($customerDataChangeRequestEntities as $customerDataChangeRequestEntity) {
            $customerDataChangeRequests->append($this->mapCustomerDataChangeRequestEntityToCustomerDataChangeRequestTransfer($customerDataChangeRequestEntity, new CustomerDataChangeRequestTransfer()));
        }

        $customerDataChangeRequestCollectionTransfer->setCustomerDataChangeRequests($customerDataChangeRequests);

        return $customerDataChangeRequestCollectionTransfer;
    }
}
