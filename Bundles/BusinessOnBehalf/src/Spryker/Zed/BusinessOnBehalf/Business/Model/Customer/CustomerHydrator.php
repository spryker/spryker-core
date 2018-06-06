<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalf\Business\Model\Customer;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\BusinessOnBehalf\Dependency\Facade\BusinessOnBehalfToCompanyUserFacadeBridge;
use Spryker\Zed\BusinessOnBehalf\Persistence\BusinessOnBehalfRepositoryInterface;

class CustomerHydrator implements CustomerHydratorInterface
{
    /**
     * @var \Spryker\Zed\BusinessOnBehalf\Persistence\BusinessOnBehalfRepositoryInterface
     */
    protected $businessOnBehalfRepository;

    /**
     * @var \Spryker\Zed\BusinessOnBehalf\Dependency\Facade\BusinessOnBehalfToCompanyUserFacadeBridge
     */
    protected $companyUserFacade;

    /**
     * @param \Spryker\Zed\BusinessOnBehalf\Persistence\BusinessOnBehalfRepositoryInterface $businessOnBehalfRepository
     * @param \Spryker\Zed\BusinessOnBehalf\Dependency\Facade\BusinessOnBehalfToCompanyUserFacadeBridge $companyUserFacade
     */
    public function __construct(
        BusinessOnBehalfRepositoryInterface $businessOnBehalfRepository,
        BusinessOnBehalfToCompanyUserFacadeBridge $companyUserFacade
    ) {
        $this->businessOnBehalfRepository = $businessOnBehalfRepository;
        $this->companyUserFacade = $companyUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function hydrateCustomerWithCompanyUser(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        if ($customerTransfer->getCompanyUserTransfer()) {
            return $customerTransfer;
        }
        $customerTransfer->requireIdCustomer();
        $defaultCompanyUser = $this->businessOnBehalfRepository
            ->findDefaultCompanyUserByCustomerId($customerTransfer->getIdCustomer());
        if ($defaultCompanyUser) {
            $defaultCompanyUser = $this->companyUserFacade->getCompanyUserById($defaultCompanyUser->getIdCompanyUser());
        }

        $customerTransfer->setCompanyUserTransfer($defaultCompanyUser);

        return $customerTransfer;
    }
}
