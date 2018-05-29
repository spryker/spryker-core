<?php

namespace Spryker\Client\BusinessOnBehalf\Zed;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\BusinessOnBehalf\Dependency\Client\BusinessOnBehalfToZedRequestClientInterface;

class BusinessOnBehalfStub implements BusinessOnBehalfStubInterface
{
    /**
     * @var \Spryker\Client\BusinessOnBehalf\Dependency\Client\BusinessOnBehalfToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\BusinessOnBehalf\Dependency\Client\BusinessOnBehalfToZedRequestClientInterface
     */
    public function __construct(BusinessOnBehalfToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function findActiveCompanyUsersByCustomerId(CustomerTransfer $customerTransfer): CompanyUserCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\CompanyUserCollectionTransfer $companyUserCollection */
        $companyUserCollection = $this->zedRequestClient->call(
            '/business-on-behalf/gateway/find-active-company-users-by-customer-id',
            $customerTransfer
        );

        return $companyUserCollection;
    }
}