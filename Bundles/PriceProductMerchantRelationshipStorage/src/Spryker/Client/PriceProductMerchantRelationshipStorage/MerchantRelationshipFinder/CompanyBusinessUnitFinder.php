<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductMerchantRelationshipStorage\MerchantRelationshipFinder;

class CompanyBusinessUnitFinder implements CompanyBusinessUnitFinderInterface
{
    /**
     * @var int
     */
    protected static $companyBusinessUnitCache;

    /**
     * @var \Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Client\PriceProductMerchantRelationshipStorageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \Spryker\Client\PriceProductMerchantRelationshipStorage\Dependency\Client\PriceProductMerchantRelationshipStorageToCustomerClientInterface $customerClient
     */
    public function __construct($customerClient)
    {
        $this->customerClient = $customerClient;
    }

    /**
     * @return int|null
     */
    public function findCurrentCustomerCompanyBusinessUnitId(): ?int
    {
        if (static::$companyBusinessUnitCache !== null) {
            return static::$companyBusinessUnitCache;
        }

        $customerTransfer = $this->customerClient->getCustomer();
        if (!$customerTransfer) {
            return static::$companyBusinessUnitCache = 0;
        }

        $companyTransfer = $customerTransfer->getCompanyUserTransfer();
        if (!$companyTransfer) {
            return static::$companyBusinessUnitCache = 0;
        }

        $companyBusinessUnit = $companyTransfer->getCompanyBusinessUnit();
        if ($companyBusinessUnit) {
            return static::$companyBusinessUnitCache = $companyBusinessUnit->getIdCompanyBusinessUnit();
        }

        return null;
    }
}
