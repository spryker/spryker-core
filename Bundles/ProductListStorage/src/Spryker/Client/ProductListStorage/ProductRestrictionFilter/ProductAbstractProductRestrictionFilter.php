<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductListStorage\ProductRestrictionFilter;

use Generated\Shared\Transfer\ProductAbstractProductListStorageTransfer;
use Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToCustomerClientInterface;
use Spryker\Client\ProductListStorage\Exception\NotSupportedProductListTransferTypeException;
use Spryker\Client\ProductListStorage\ProductListProductAbstractStorage\ProductListProductAbstractStorageReaderInterface;

class ProductAbstractProductRestrictionFilter extends AbstractProductRestrictionFilter
{
    protected const ERROR_MESSAGE_WRONG_PRODUCT_LIST_TRANSFER_TYPE = 'Type `%s` is not supported for getting concrete product id. Please use `%s` instead.';

    /**
     * @var \Spryker\Client\ProductListStorage\ProductListProductAbstractStorage\ProductListProductAbstractStorageReaderInterface
     */
    protected $productListProductAbstractStorageReader;

    /**
     * @param \Spryker\Client\ProductListStorage\Dependency\Client\ProductListStorageToCustomerClientInterface $customerClient
     * @param \Spryker\Client\ProductListStorage\ProductListProductAbstractStorage\ProductListProductAbstractStorageReaderInterface $productListProductAbstractStorageReader
     */
    public function __construct(ProductListStorageToCustomerClientInterface $customerClient, ProductListProductAbstractStorageReaderInterface $productListProductAbstractStorageReader)
    {
        parent::__construct($customerClient);
        $this->productListProductAbstractStorageReader = $productListProductAbstractStorageReader;
    }

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\ProductAbstractProductListStorageTransfer[]|\Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer[]
     */
    protected function getProductListStorageTransfers(array $productIds): array
    {
        return $this
            ->productListProductAbstractStorageReader
            ->getProductAbstractProductListStorageTransfersByProductAbstractIds($productIds);
    }

    /**
     * @param mixed $productListStorageTransfer
     *
     * @throws \Spryker\Client\ProductListStorage\Exception\NotSupportedProductListTransferTypeException
     *
     * @return int
     */
    protected function getIdProduct($productListStorageTransfer): int
    {
        if (!$productListStorageTransfer instanceof ProductAbstractProductListStorageTransfer) {
            $actualType = is_object($productListStorageTransfer) ? get_class($productListStorageTransfer) : gettype($productListStorageTransfer);

            throw new NotSupportedProductListTransferTypeException(sprintf(static::ERROR_MESSAGE_WRONG_PRODUCT_LIST_TRANSFER_TYPE, $actualType, ProductAbstractProductListStorageTransfer::class));
        }
        /** @var \Generated\Shared\Transfer\ProductAbstractProductListStorageTransfer $productAbstractProductListStorageTransfer */
        $productListStorageTransfer->requireIdProductAbstract();

        return $productListStorageTransfer->getIdProductAbstract();
    }
}
