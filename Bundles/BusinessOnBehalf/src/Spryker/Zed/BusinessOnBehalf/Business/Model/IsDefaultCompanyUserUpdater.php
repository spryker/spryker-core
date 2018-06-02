<?php

namespace Spryker\Zed\BusinessOnBehalf\Business\Model;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\BusinessOnBehalf\Persistence\BusinessOnBehalfEntityManagerInterface;

class IsDefaultCompanyUserUpdater implements IsDefaultCompanyUserUpdaterInterface
{
    /**
     * @var \Spryker\Zed\BusinessOnBehalf\Persistence\BusinessOnBehalfEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\BusinessOnBehalf\Persistence\BusinessOnBehalfEntityManagerInterface $entityManager
     */
    public function __construct(BusinessOnBehalfEntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function setDefaultCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        return $this->entityManager->setDefaultCompanyUser($companyUserTransfer);
    }
}
