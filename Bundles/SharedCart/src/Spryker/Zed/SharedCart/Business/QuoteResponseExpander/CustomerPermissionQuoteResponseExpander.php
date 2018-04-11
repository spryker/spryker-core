<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\QuoteResponseExpander;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToCustomerFacadeInterface;
use Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface;

class CustomerPermissionQuoteResponseExpander implements QuoteResponseExpanderInterface
{
    /**
     * @var \Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface
     */
    protected $sharedCartRepository;

    /**
     * @param \Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface $sharedCartRepository
     */
    public function __construct(SharedCartToCustomerFacadeInterface $customerFacade, SharedCartRepositoryInterface $sharedCartRepository)
    {
        $this->customerFacade = $customerFacade;
        $this->sharedCartRepository = $sharedCartRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function expand(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        $customerTransfer = $quoteResponseTransfer->getQuoteTransfer()->requireCustomer()->getCustomer();
        $quoteResponseTransfer->setCustomerPermissions($this->getPermissionCollection($customerTransfer));

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    protected function getPermissionCollection(CustomerTransfer $customerTransfer): PermissionCollectionTransfer
    {
        if ($customerTransfer->getCompanyUserTransfer()) {
            $customerTransfer->setPermissions(null);
            return $this->customerFacade->getCustomer($customerTransfer)->getPermissions();
        }

        return $this->sharedCartRepository->findPermissionsByCustomer($customerTransfer->getCustomerReference());
    }
}
