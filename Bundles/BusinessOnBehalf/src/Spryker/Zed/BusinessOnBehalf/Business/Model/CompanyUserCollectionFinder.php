<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalf\Business\Model;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\BusinessOnBehalf\Dependency\Facade\CompanyUserToBusinessOnBehalfFacadeInterface;
use Spryker\Zed\BusinessOnBehalf\Persistence\BusinessOnBehalfRepositoryInterface;

class CompanyUserCollectionFinder implements CompanyUserCollectionFinderInterface
{
    /**
     * @var \Spryker\Zed\BusinessOnBehalf\Persistence\BusinessOnBehalfRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\BusinessOnBehalf\Dependency\Facade\CompanyUserToBusinessOnBehalfFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @param \Spryker\Zed\BusinessOnBehalf\Persistence\BusinessOnBehalfRepositoryInterface $repository
     * @param \Spryker\Zed\BusinessOnBehalf\Dependency\Facade\CompanyUserToBusinessOnBehalfFacadeInterface $companyUserFacade
     */
    public function __construct(BusinessOnBehalfRepositoryInterface $repository, CompanyUserToBusinessOnBehalfFacadeInterface $companyUserFacade)
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
        $companyCollection = new CompanyUserCollectionTransfer();
        $idsCompanyUser = $this->repository->findActiveCompanyUserIdsByCustomerId($customerTransfer->getIdCustomer());
        foreach ($idsCompanyUser as $idCompanyUser) {
            $companyCollection->addCompanyUser(
                $this->companyUserFacade->getCompanyUserById($idCompanyUser)
            );
        }

        return $companyCollection;
    }
}
