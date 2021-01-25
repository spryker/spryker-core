<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Agent\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CustomerAutocompleteResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Propel\Runtime\Util\PropelModelPager;

class AgentMapper
{
    /**
     * @param array $customers
     * @param \Propel\Runtime\Util\PropelModelPager $pager
     *
     * @return \Generated\Shared\Transfer\CustomerAutocompleteResponseTransfer
     */
    public function mapCustomerDataToCustomerAutocompleteResponseTransfer(array $customers, PropelModelPager $pager): CustomerAutocompleteResponseTransfer
    {
        $customerAutocompleteResponseTransfer = new CustomerAutocompleteResponseTransfer();

        foreach ($customers as $customer) {
            $customerTransfer = $this->mapCustomerDataToCustomerTransfer(
                $customer,
                new CustomerTransfer()
            );

            $customerAutocompleteResponseTransfer->addCustomer($customerTransfer);
        }

        $paginationTransfer = $this->mapPropelModelPagerToPaginationTransfer($pager, new PaginationTransfer());

        return $customerAutocompleteResponseTransfer->setPagination($paginationTransfer);
    }

    /**
     * @param array $customer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function mapCustomerDataToCustomerTransfer(
        array $customer,
        CustomerTransfer $customerTransfer
    ): CustomerTransfer {
        $customerTransfer->setIdCustomer($customer[SpyCustomerTableMap::COL_ID_CUSTOMER]);
        $customerTransfer->setCustomerReference($customer[SpyCustomerTableMap::COL_CUSTOMER_REFERENCE]);
        $customerTransfer->setFirstName($customer[SpyCustomerTableMap::COL_FIRST_NAME]);
        $customerTransfer->setLastName($customer[SpyCustomerTableMap::COL_LAST_NAME]);
        $customerTransfer->setEmail($customer[SpyCustomerTableMap::COL_EMAIL]);

        return $customerTransfer;
    }

    /**
     * @param \Propel\Runtime\Util\PropelModelPager $pager
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function mapPropelModelPagerToPaginationTransfer(
        PropelModelPager $pager,
        PaginationTransfer $paginationTransfer
    ): PaginationTransfer {
        $paginationTransfer->setFirstIndex($pager->getFirstIndex())
            ->setFirstPage($pager->getFirstPage())
            ->setLastIndex($pager->getLastIndex())
            ->setLastPage($pager->getLastPage())
            ->setMaxPerPage($pager->getMaxPerPage())
            ->setNbResults($pager->getNbResults())
            ->setNextPage($pager->getNextPage())
            ->setPage($pager->getPage())
            ->setPreviousPage($pager->getPreviousPage());

        return $paginationTransfer;
    }
}
