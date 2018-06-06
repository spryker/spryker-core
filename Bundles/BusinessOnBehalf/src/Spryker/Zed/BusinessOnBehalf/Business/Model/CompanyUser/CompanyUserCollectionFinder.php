<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalf\Business\Model\CompanyUser;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\BusinessOnBehalf\Dependency\Facade\BusinessOnBehalfToCompanyUserFacadeBridge;
use Spryker\Zed\BusinessOnBehalf\Persistence\BusinessOnBehalfRepositoryInterface;

class CompanyUserCollectionFinder implements CompanyUserCollectionFinderInterface
{
    /**
     * @var \Spryker\Zed\BusinessOnBehalf\Persistence\BusinessOnBehalfRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\BusinessOnBehalf\Dependency\Facade\BusinessOnBehalfToCompanyUserFacadeBridge
     */
    protected $companyUserFacade;

    /**
     * @param \Spryker\Zed\BusinessOnBehalf\Persistence\BusinessOnBehalfRepositoryInterface $repository
     * @param \Spryker\Zed\BusinessOnBehalf\Dependency\Facade\BusinessOnBehalfToCompanyUserFacadeBridge $companyUserFacade
     */
    public function __construct(BusinessOnBehalfRepositoryInterface $repository, BusinessOnBehalfToCompanyUserFacadeBridge $companyUserFacade)
    {
        $this->repository = $repository;
        $this->companyUserFacade = $companyUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function findActiveCompanyUsersByCustomerId(CustomerTransfer $customerTransfer): CompanyUserCollectionTransfer
    {
        $customerTransfer->requireIdCustomer();
        $companyCollection = new CompanyUserCollectionTransfer();
        $idCompanyUsers = $this->repository->findActiveCompanyUserIdsByCustomerId($customerTransfer->getIdCustomer());
        foreach ($idCompanyUsers as $idCompanyUser) {
            $companyCollection->addCompanyUser(
                $this->companyUserFacade->getCompanyUserById($idCompanyUser)
            );
        }

        return $companyCollection;
    }
}
