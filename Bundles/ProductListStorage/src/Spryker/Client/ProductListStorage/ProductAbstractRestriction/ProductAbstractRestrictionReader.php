<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductListStorage\ProductAbstractRestriction;

use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;
use Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToCustomerClientInterface;
use Spryker\Client\ProductListStorage\ProductListProductAbstractStorage\ProductListProductAbstractStorageReaderInterface;

class ProductAbstractRestrictionReader implements ProductAbstractRestrictionReaderInterface
{
    /**
     * @var \Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Client\ProductListStorage\ProductListProductAbstractStorage\ProductListProductAbstractStorageReaderInterface
     */
    protected $productListProductAbstractStorageReader;

    /**
     * @param \Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToCustomerClientInterface $customerClient
     * @param \Spryker\Client\ProductListStorage\ProductListProductAbstractStorage\ProductListProductAbstractStorageReaderInterface $productListProductAbstractStorageReader
     */
    public function __construct(
        ProductListStorageToCustomerClientInterface $customerClient,
        ProductListProductAbstractStorageReaderInterface $productListProductAbstractStorageReader
    ) {
        $this->customerClient = $customerClient;
        $this->productListProductAbstractStorageReader = $productListProductAbstractStorageReader;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isProductAbstractRestricted(int $idProductAbstract): bool
    {
        $customer = $this->customerClient->getCustomer();
        if (!$customer || !$customer->getCustomerProductListCollection()) {
            return false;
        }
        $customerBlacklistIds = $this->getCustomerBlacklistIds($customer->getCustomerProductListCollection());
        $customerWhitelistIds = $this->getCustomerWhitelistIds($customer->getCustomerProductListCollection());

        return $this->checkIfProductAbstractIsRestricted($idProductAbstract, $customerWhitelistIds, $customerBlacklistIds);
    }

    /**
     * @param int $idProductAbstract
     * @param int[] $customerWhitelistIds
     * @param int[] $customerBlacklistIds
     *
     * @return bool
     */
    protected function checkIfProductAbstractIsRestricted(
        int $idProductAbstract,
        array $customerWhitelistIds,
        array $customerBlacklistIds
    ): bool {
        if (!$customerBlacklistIds && !$customerWhitelistIds) {
            return false;
        }

        $productListProductAbstractStorageTransfer = $this->productListProductAbstractStorageReader->findProductAbstractProductListStorage($idProductAbstract);

        if ($productListProductAbstractStorageTransfer) {
            $isProductInBlacklist = count(array_intersect($productListProductAbstractStorageTransfer->getIdBlacklists(), $customerBlacklistIds));
            $isProductInWhitelist = count(array_intersect($productListProductAbstractStorageTransfer->getIdWhitelists(), $customerWhitelistIds));

            return $isProductInBlacklist || (count($customerWhitelistIds) && !$isProductInWhitelist);
        }

        return (bool)count($customerWhitelistIds);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerProductListCollectionTransfer $customerProductListCollectionTransfer
     *
     * @return array
     */
    protected function getCustomerBlacklistIds(CustomerProductListCollectionTransfer $customerProductListCollectionTransfer): array
    {
        $customerBlacklistIds = [];

        foreach ($customerProductListCollectionTransfer->getBlacklists() as $productListTransfer) {
            $customerBlacklistIds[] = $productListTransfer->getIdProductList();
        }

        return $customerBlacklistIds;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerProductListCollectionTransfer $customerProductListCollectionTransfer
     *
     * @return array
     */
    protected function getCustomerWhitelistIds(CustomerProductListCollectionTransfer $customerProductListCollectionTransfer): array
    {
        $customerWhitelistIds = [];

        foreach ($customerProductListCollectionTransfer->getWhitelists() as $productListTransfer) {
            $customerWhitelistIds[] = $productListTransfer->getIdProductList();
        }

        return $customerWhitelistIds;
    }
}
