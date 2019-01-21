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

        return $this->isProductConcreteRestrictedInProductLists($idProduct, $customerWhitelistIds, $customerBlacklistIds);
    }

    /**
     * @param int $idProduct
     * @param int[] $customerWhitelistIds
     * @param int[] $customerBlacklistIds
     *
     * @return bool
     */
    protected function isProductConcreteRestrictedInProductLists(
        int $idProduct,
        array $customerWhitelistIds,
        array $customerBlacklistIds
    ): bool {
        $productListProductConcreteStorageTransfer = $this->productListProductConcreteStorageReader->findProductConcreteProductListStorage($idProduct);

        $isProductRestrictedInBlackList = $this->isProductConcreteRestrictedInBlacklist(
            $productListProductConcreteStorageTransfer,
            $customerBlacklistIds
        );
        if ($isProductRestrictedInBlackList) {
            return true;
        }

        $isProductRestrictedInWhiteList = $this->isProductConcreteRestrictedInWhitelist(
            $productListProductConcreteStorageTransfer,
            $customerWhitelistIds
        );
        if ($isProductRestrictedInWhiteList) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer|null $productListProductConcreteStorageTransfer
     * @param int[] $customerWhitelistIds
     *
     * @return bool
     */
    protected function isProductConcreteRestrictedInWhitelist(
        ?ProductConcreteProductListStorageTransfer $productListProductConcreteStorageTransfer,
        array $customerWhitelistIds
    ): bool {
        if (empty($customerWhitelistIds)) {
            return false;
        }

        if ($productListProductConcreteStorageTransfer === null) {
            return true;
        }

        $isProductInWhitelist = empty(array_intersect($productListProductConcreteStorageTransfer->getIdWhitelists(), $customerWhitelistIds));

        return $isProductInWhitelist;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer|null $productListProductConcreteStorageTransfer
     * @param int[] $customerBlacklistIds
     *
     * @return bool
     */
    protected function isProductConcreteRestrictedInBlacklist(
        ?ProductConcreteProductListStorageTransfer $productListProductConcreteStorageTransfer,
        array $customerBlacklistIds
    ): bool {
        if (empty($customerBlacklistIds)) {
            return false;
        }

        if ($productListProductConcreteStorageTransfer === null) {
            return false;
        }

        $isProductInBlacklist = !empty(array_intersect($productListProductConcreteStorageTransfer->getIdBlacklists(), $customerBlacklistIds));

        return $isProductInBlacklist;
    }
}
