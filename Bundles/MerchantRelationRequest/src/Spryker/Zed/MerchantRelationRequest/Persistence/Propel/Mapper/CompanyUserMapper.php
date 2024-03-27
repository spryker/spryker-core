<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUser;

class CompanyUserMapper
{
    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Persistence\Propel\Mapper\CustomerMapper
     */
    protected CustomerMapper $customerMapper;

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Persistence\Propel\Mapper\CustomerMapper $customerMapper
     */
    public function __construct(CustomerMapper $customerMapper)
    {
        $this->customerMapper = $customerMapper;
    }

    /**
     * @param \Orm\Zed\CompanyUser\Persistence\SpyCompanyUser $companyUserEntity
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function mapCompanyUserEntityToCompanyUserTransfer(
        SpyCompanyUser $companyUserEntity,
        CompanyUserTransfer $companyUserTransfer
    ): CompanyUserTransfer {
        $companyUserTransfer = $companyUserTransfer
            ->fromArray($companyUserEntity->toArray(), true);

        $customerTransfer = $this->customerMapper->mapCustomerEntityToCustomerTransfer(
            $companyUserEntity->getCustomer(),
            new CustomerTransfer(),
        );

        return $companyUserTransfer->setCustomer($customerTransfer);
    }
}
