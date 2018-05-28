<?php

namespace Spryker\Zed\BusinessOnBehalf\Dependency\Facade;

use Generated\Shared\Transfer\CompanyUserTransfer;

class CompanyUserToBusinessOnBehalfFacadeBridge implements CompanyUserToBusinessOnBehalfFacadeInterface
{
    /**
     * @var \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    public function __construct($companyUserFacade)
    {
        $this->companyUserFacade = $companyUserFacade;
    }

    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getCompanyUserById(int $idCompanyUser): CompanyUserTransfer
    {
        return $this->companyUserFacade->getCompanyUserById($idCompanyUser);
    }
}