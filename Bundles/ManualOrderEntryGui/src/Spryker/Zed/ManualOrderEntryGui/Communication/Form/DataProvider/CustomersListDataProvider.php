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
     * @param \Generated\Shared\Transfer\QuoteTransfer $transfer
     *
     * @return array
     */
    public function getOptions($transfer): array
    {
        return [
            'data_class' => QuoteTransfer::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
            CustomersListType::OPTION_CUSTOMER_ARRAY => $this->getCustomerList(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $transfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData($transfer): QuoteTransfer
    {
        if ($transfer->getCustomer() === null) {
            $transfer->setCustomer(new CustomerTransfer());
        }

        $idCustomer = $transfer->getCustomer()->getIdCustomer();

        if (!$idCustomer && $this->request->query->has(CustomersListType::FIELD_CUSTOMER)) {
            $idCustomer = $this->request->query->getInt(CustomersListType::FIELD_CUSTOMER);
        }

        $transfer->getCustomer()->setIdCustomer($idCustomer);

        return $transfer;
    }

    /**
     * @return array
     */
    protected function getCustomerList(): array
    {
        /** @var array<\Orm\Zed\Customer\Persistence\SpyCustomer> $customerCollection */
        $customerCollection = $this->customerQueryContainer
            ->queryCustomers()
            ->find();

        $customerList = [];

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
