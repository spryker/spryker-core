<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Customer\CustomersListType;
use Spryker\Zed\ManualOrderEntryGui\Dependency\QueryContainer\ManualOrderEntryGuiToCustomerQueryContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class CustomersListDataProvider implements FormDataProviderInterface
{
    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\QueryContainer\ManualOrderEntryGuiToCustomerQueryContainerInterface
     */
    protected $customerQueryContainer;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Dependency\QueryContainer\ManualOrderEntryGuiToCustomerQueryContainerInterface $customerQueryContainer
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct(
        ManualOrderEntryGuiToCustomerQueryContainerInterface $customerQueryContainer,
        Request $request
    ) {
        $this->customerQueryContainer = $customerQueryContainer;
        $this->request = $request;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions($quoteTransfer)
    {
        return [
            'data_class' => QuoteTransfer::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
            CustomersListType::OPTION_CUSTOMER_ARRAY => $this->getCustomerList(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData($quoteTransfer)
    {
        if ($quoteTransfer->getCustomer() === null) {
            $quoteTransfer->setCustomer(new CustomerTransfer());
        }

        $value = $quoteTransfer->getCustomer()->getIdCustomer();

        if (!$value && $this->request->query->has(CustomersListType::FIELD_CUSTOMER)) {
            $value = $this->request->query->get(CustomersListType::FIELD_CUSTOMER);
        }

        $quoteTransfer->getCustomer()->setIdCustomer($value);

        return $quoteTransfer;
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
