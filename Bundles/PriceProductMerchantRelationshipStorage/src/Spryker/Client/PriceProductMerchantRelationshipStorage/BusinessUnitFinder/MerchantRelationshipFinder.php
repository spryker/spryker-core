<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductMerchantRelationshipStorage\BusinessUnitFinder;

class MerchantRelationshipFinder implements MerchantRelationshipFinderInterface
{
    /**
     * @var null|int
     */
    protected static $businessUnitCache = 0;

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
    public function findCurrentCustomerMerchantRelationshipId(): ?int
    {
        if (static::$businessUnitCache !== 0) {
            return static::$businessUnitCache;
        }

        $customerTransfer = $this->customerClient->getCustomer();
        if (!$customerTransfer) {
            static::$businessUnitCache = null;
            return null;
        }

        $companyTransfer = $customerTransfer->getCompanyUserTransfer();
        if (!$companyTransfer) {
            static::$businessUnitCache = null;
            return null;
        }

        //todo find merchant relationships by business unit
        static::$businessUnitCache = $companyTransfer->getFkCompanyBusinessUnit();

        return static::$businessUnitCache;
    }
}
