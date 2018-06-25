<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductMerchantRelationshipStorage\MerchantRelationshipFinder;

class MerchantRelationshipFinder implements MerchantRelationshipFinderInterface
{
    /**
     * @var array
     */
    protected static $merchantRelationshipCache;

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
     * @return int[]
     */
    public function findCurrentCustomerMerchantRelationshipIds(): array
    {
        if (static::$merchantRelationshipCache !== null) {
            return static::$merchantRelationshipCache;
        }

        $customerTransfer = $this->customerClient->getCustomer();
        if (!$customerTransfer) {
            return static::$merchantRelationshipCache = [];
        }

        $companyTransfer = $customerTransfer->getCompanyUserTransfer();
        if (!$companyTransfer) {
            return static::$merchantRelationshipCache = [];
        }

        $businessUnit = $companyTransfer->getCompanyBusinessUnit();
        if (!$businessUnit) {
            return static::$merchantRelationshipCache = [];
        }

        if ($businessUnit->getMerchantRelationships()->count() === 0) {
            return static::$merchantRelationshipCache = [];
        }

        $idMerchantRelationshipCollection = [];
        foreach ($businessUnit->getMerchantRelationships() as $merchantRelationshipTransfer) {
            $idMerchantRelationship = $merchantRelationshipTransfer->getIdMerchantRelationship();
            if (isset($idMerchantRelationshipCollection[$idMerchantRelationship])) {
                continue;
            }
            $idMerchantRelationshipCollection[$idMerchantRelationship] = $idMerchantRelationship;
        }

        return static::$merchantRelationshipCache = $idMerchantRelationshipCollection;
    }
}
