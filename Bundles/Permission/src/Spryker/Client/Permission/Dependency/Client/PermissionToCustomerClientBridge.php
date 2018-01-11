<?php

namespace Spryker\Client\Permission\Dependency\Client;


use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Client\Customer\CustomerClientInterface;

class PermissionToCustomerClientBridge implements PermissionToCustomerClientInterface
{
    /** @var  CustomerClientInterface */
    protected $customerClient;

    public function __construct($customerClient)
    {
        $this->customerClient = $customerClient;
    }

    /**
     * @example
     *
     * @return CompanyUserTransfer|null
     */
    public function getCompanyUser()
    {
//        return $this->customerClient->getCustomer();
        $companyRole =  (new CompanyRoleTransfer())
            ->setName('manager')
            ->setIdCompanyRole(1)
            ->setIsDefault(0)
            ->setPermissionCollection(new PermissionCollectionTransfer());

        $companyRoleCollection = (new CompanyRoleCollectionTransfer())
            ->addRole($companyRole);

        $companyUser = new CompanyUserTransfer();
        $companyUser->setIdCompanyUser(1);
        $companyUser->setCompanyRoleCollection($companyRoleCollection);

        return $companyUser;
    }
}