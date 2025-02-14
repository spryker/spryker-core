<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDataChangeRequest\Persistence;

use Generated\Shared\Transfer\CustomerDataChangeRequestCollectionTransfer;
use Generated\Shared\Transfer\CustomerDataChangeRequestCriteriaTransfer;
use Orm\Zed\CustomerDataChangeRequest\Persistence\SpyCustomerDataChangeRequestQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CustomerDataChangeRequest\Persistence\CustomerDataChangeRequestPersistenceFactory getFactory()
 */
class CustomerDataChangeRequestRepository extends AbstractRepository implements CustomerDataChangeRequestRepositoryInterface
{
    /**
     * @var string
     */
    protected const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var string
     */
    protected const DATE_FORMAT_MINUTES = '-%d minutes';

    /**
     * @param \Generated\Shared\Transfer\CustomerDataChangeRequestCriteriaTransfer $customerDataChangeRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerDataChangeRequestCollectionTransfer
     */
    public function get(CustomerDataChangeRequestCriteriaTransfer $customerDataChangeRequestCriteriaTransfer): CustomerDataChangeRequestCollectionTransfer
    {
        $customerDataChangeRequestQuery = $this->getFactory()->createCustomerDataChangeRequestQuery();

        $customerDataChangeRequestQuery = $this->applyFilters($customerDataChangeRequestQuery, $customerDataChangeRequestCriteriaTransfer);

        return $this->getFactory()
            ->createCustomerDataChangeRequestMapper()
            ->mapCustomerDataChangeRequestCollectionToCustomerDataChangeRequestCollectionTransfer($customerDataChangeRequestQuery->find(), new CustomerDataChangeRequestCollectionTransfer());
    }

    /**
     * @param \Orm\Zed\CustomerDataChangeRequest\Persistence\SpyCustomerDataChangeRequestQuery $customerDataChangeRequestQuery
     * @param \Generated\Shared\Transfer\CustomerDataChangeRequestCriteriaTransfer $changeRequestCriteriaTransfer
     *
     * @return \Orm\Zed\CustomerDataChangeRequest\Persistence\SpyCustomerDataChangeRequestQuery
     */
    protected function applyFilters(
        SpyCustomerDataChangeRequestQuery $customerDataChangeRequestQuery,
        CustomerDataChangeRequestCriteriaTransfer $changeRequestCriteriaTransfer
    ): SpyCustomerDataChangeRequestQuery {
        $customerDataChangeRequestConditions = $changeRequestCriteriaTransfer->getCustomerDataChangeRequestConditions();

        if ($customerDataChangeRequestConditions === null) {
            return $customerDataChangeRequestQuery;
        }

        if ($customerDataChangeRequestConditions->getIdCustomers() !== []) {
            $customerDataChangeRequestQuery->filterByFkCustomer_In($customerDataChangeRequestConditions->getIdCustomers());
        }

        if ($customerDataChangeRequestConditions->getTypes() !== []) {
            $customerDataChangeRequestQuery->filterByType_In($customerDataChangeRequestConditions->getTypes());
        }

        if ($customerDataChangeRequestConditions->getStatuses() !== []) {
            $customerDataChangeRequestQuery->filterByStatus_In($customerDataChangeRequestConditions->getStatuses());
        }

        if ($customerDataChangeRequestConditions->getIsExpired()) {
            $customerDataChangeRequestQuery->filterByCreatedAt(
                date(static::DATE_FORMAT, (int)strtotime(sprintf(static::DATE_FORMAT_MINUTES, $this->getFactory()->getConfig()->getEmailChangeVerificationExpirationMinutes()))),
                Criteria::LESS_THAN,
            );
        }

        if ($customerDataChangeRequestConditions->getIsExpired() === false) {
            $customerDataChangeRequestQuery->filterByCreatedAt(
                date(static::DATE_FORMAT, (int)strtotime(sprintf(static::DATE_FORMAT_MINUTES, $this->getFactory()->getConfig()->getEmailChangeVerificationExpirationMinutes()))),
                Criteria::GREATER_EQUAL,
            );
        }

        if ($customerDataChangeRequestConditions->getVerificationToken() !== null) {
            $customerDataChangeRequestQuery->filterByVerificationToken($customerDataChangeRequestConditions->getVerificationTokenOrFail());
        }

        return $customerDataChangeRequestQuery;
    }
}
