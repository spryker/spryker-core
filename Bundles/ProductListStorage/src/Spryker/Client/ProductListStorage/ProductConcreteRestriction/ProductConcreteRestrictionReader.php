<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductListStorage\ProductConcreteRestriction;

use Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer;
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

        $productListProductConcreteStorageTransfer = $this->productListProductConcreteStorageReader->findProductConcreteProductListStorage($idProduct);

        $isProductRestrictedInBlackList = $this->checkIfProductConcreteRestrictedInBlacklist($productListProductConcreteStorageTransfer, $customerBlacklistIds);
        $isProductRestrictedInWhiteList = $this->checkIfProductConcreteRestrictedInWhitelist($productListProductConcreteStorageTransfer, $customerWhitelistIds);

        return $isProductRestrictedInBlackList || $isProductRestrictedInWhiteList;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer|null $productListProductConcreteStorageTransfer
     * @param int[] $customerWhitelistIds
     *
     * @return bool
     */
    protected function checkIfProductConcreteRestrictedInWhitelist(
        ?ProductConcreteProductListStorageTransfer $productListProductConcreteStorageTransfer,
        array $customerWhitelistIds
    ): bool {
        if (empty($customerWhitelistIds)) {
            return false;
        }

        if ($productListProductConcreteStorageTransfer) {
            $isProductInWhitelist = empty(array_intersect($productListProductConcreteStorageTransfer->getIdWhitelists(), $customerWhitelistIds));

            return $isProductInWhitelist;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer|null $productListProductConcreteStorageTransfer
     * @param int[] $customerBlacklistIds
     *
     * @return bool
     */
    protected function checkIfProductConcreteRestrictedInBlacklist(
        ?ProductConcreteProductListStorageTransfer $productListProductConcreteStorageTransfer,
        array $customerBlacklistIds
    ): bool {
        if (empty($customerBlacklistIds)) {
            return false;
        }

        if ($productListProductConcreteStorageTransfer) {
            $isProductInBlacklist = !empty(array_intersect($productListProductConcreteStorageTransfer->getIdBlacklists(), $customerBlacklistIds));

            return $isProductInBlacklist;
        }

        return false;
    }
}
