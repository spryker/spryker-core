<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ManualOrderEntryTransfer;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Customer\CustomersListType;
use Spryker\Zed\ManualOrderEntryGui\Dependency\QueryContainer\ManualOrderEntryGuiToCustomerQueryContainerInterface;

class CustomersListDataProvider
{
    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\QueryContainer\ManualOrderEntryGuiToCustomerQueryContainerInterface
     */
    protected $customerQueryContainer;

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Dependency\QueryContainer\ManualOrderEntryGuiToCustomerQueryContainerInterface $customerQueryContainer
     */
    public function __construct(
        ManualOrderEntryGuiToCustomerQueryContainerInterface $customerQueryContainer
    ) {
        $this->customerQueryContainer = $customerQueryContainer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'data_class' => ManualOrderEntryTransfer::class,
            CustomersListType::OPTION_CUSTOMER_ARRAY => $this->getCustomerList(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ManualOrderEntryTransfer $manualOrderEntryTransfer
     *
     * @return \Generated\Shared\Transfer\ManualOrderEntryTransfer
     */
    public function getData(ManualOrderEntryTransfer $manualOrderEntryTransfer)
    {
        return $manualOrderEntryTransfer;
    }

    /**
     * @return array
     */
    protected function getCustomerList()
    {
        $customerCollection = $this->customerQueryContainer
            ->queryCustomers()
            ->find();

        $customerList = [];

        /** @var \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity */
        foreach ($customerCollection as $customerEntity) {
            $customerFieldData = $customerEntity->getLastName()
                . ' '
                . $customerEntity->getFirstName()
                . ' [' . $customerEntity->getEmail() . ']';

            $customerList[$customerEntity->getIdCustomer()] = $customerFieldData;
        }

        return $customerList;
    }

}
