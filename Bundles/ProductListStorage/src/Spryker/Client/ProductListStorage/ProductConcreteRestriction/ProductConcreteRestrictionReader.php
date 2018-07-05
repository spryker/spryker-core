<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductListStorage\ProductConcreteRestriction;

use Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToCustomerClientInterface;
use Spryker\Client\ProductListStorage\ProductListProductConcreteStorage\ProductListProductConcreteStorageReaderInterface;

class ProductConcreteRestrictionReader implements ProductConcreteRestrictionReaderInterface
{
    /**
     * @var \Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Client\ProductListStorage\ProductListProductConcreteStorage\ProductListProductConcreteStorageReaderInterface
     */
    protected $productListProductConcreteStorageReader;

    /**
     * @param \Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToCustomerClientInterface $customerClient
     * @param \Spryker\Client\ProductListStorage\ProductListProductConcreteStorage\ProductListProductConcreteStorageReaderInterface $productListProductConcreteStorageReader
     */
    public function __construct(
        ProductListStorageToCustomerClientInterface $customerClient,
        ProductListProductConcreteStorageReaderInterface $productListProductConcreteStorageReader
    ) {
        $this->customerClient = $customerClient;
        $this->productListProductConcreteStorageReader = $productListProductConcreteStorageReader;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return bool
     */
    public function isProductConcreteRestricted(int $idProductConcrete): bool
    {
        $customer = $this->customerClient->getCustomer();
        if (!$customer || !$customer->getCustomerProductListCollection()) {
            return false;
        }
        $customerWhitelistIds = $customer->getCustomerProductListCollection()->getWhitelistIds() ?? [];
        $customerBlacklistIds = $customer->getCustomerProductListCollection()->getBlacklistIds() ?? [];

        return $this->checkIfProductConcreteIsRestricted($idProductConcrete, $customerWhitelistIds, $customerBlacklistIds);
    }

    /**
     * @param int $idProductConcrete
     * @param int[] $customerWhitelistIds
     * @param int[] $customerBlacklistIds
     *
     * @return bool
     */
    protected function checkIfProductConcreteIsRestricted(
        int $idProductConcrete,
        array $customerWhitelistIds,
        array $customerBlacklistIds
    ): bool {
        if (!$customerBlacklistIds && !$customerWhitelistIds) {
            return false;
        }

        $productListProductConcreteStorageTransfer = $this->productListProductConcreteStorageReader->findProductConcreteProductListStorage($idProductConcrete);

        if ($productListProductConcreteStorageTransfer) {
            $isProductInBlacklist = count(array_intersect($productListProductConcreteStorageTransfer->getIdBlacklists(), $customerBlacklistIds));
            $isProductInWhitelist = count(array_intersect($productListProductConcreteStorageTransfer->getIdWhitelists(), $customerWhitelistIds));

            return $isProductInBlacklist || !$isProductInWhitelist;
        }

        return (bool)count($customerWhitelistIds);
    }
}
