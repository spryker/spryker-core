<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Main\ManualOrderEntryType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Customer\CustomersListType;
use Spryker\Zed\ManualOrderEntryGui\Dependency\QueryContainer\ManualOrderEntryGuiToCustomerQueryContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class CheckoutFormDataProvider
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
     * @return array
     */
    public function getOptions()
    {
        return [
            'data_class' => QuoteTransfer::class,
            ManualOrderEntryType::OPTION_REQUEST => $this->request
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData()
    {
        $quoteTransfer = new QuoteTransfer();

        if ($this->request->getMethod() === $this->request::METHOD_GET
            && $this->request->query->get(CustomersListType::FIELD_CUSTOMER)
        ) {
            $quoteTransfer->setIdCustomer($this->request->query->get(CustomersListType::FIELD_CUSTOMER));
        }

        return $quoteTransfer;
    }

}
