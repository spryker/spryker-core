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
     * @param int $idProduct
     *
     * @return bool
     */
    public function isProductConcreteRestricted(int $idProduct): bool
    {
        $customer = $this->customerClient->getCustomer();
        if (!$customer) {
            return false;
        }

        $customerProductListCollectionTransfer = $customer->getCustomerProductListCollection();
        if (!$customerProductListCollectionTransfer) {
            return false;
        }

        $customerWhitelistIds = $customer->getCustomerProductListCollection()->getWhitelistIds() ?: [];
        $customerBlacklistIds = $customer->getCustomerProductListCollection()->getBlacklistIds() ?: [];

        return $this->checkIfProductConcreteIsRestricted($idProduct, $customerWhitelistIds, $customerBlacklistIds);
    }

    /**
     * @param int $idProduct
     * @param int[] $customerWhitelistIds
     * @param int[] $customerBlacklistIds
     *
     * @return bool
     */
    protected function checkIfProductConcreteIsRestricted(
        int $idProduct,
        array $customerWhitelistIds,
        array $customerBlacklistIds
    ): bool {
        if (empty($customerBlacklistIds) && empty($customerWhitelistIds)) {
            return false;
        }

        $productListProductConcreteStorageTransfer = $this->productListProductConcreteStorageReader->findProductConcreteProductListStorage($idProduct);

        if ($productListProductConcreteStorageTransfer) {
            $isProductInBlacklist = !empty(array_intersect($productListProductConcreteStorageTransfer->getIdBlacklists(), $customerBlacklistIds));
            $isProductInWhitelist = !empty(array_intersect($productListProductConcreteStorageTransfer->getIdWhitelists(), $customerWhitelistIds));

            if (empty($customerWhitelistIds)) {
                return $isProductInBlacklist;
            }

            if (empty($customerBlacklistIds)) {
                return !$isProductInWhitelist;
            }

            return $isProductInBlacklist || !$isProductInWhitelist;
        }

        return false;
    }
}
