<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductList\Business\CustomerExpander;

use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ProductListCollectionTransfer;
use Spryker\Zed\MerchantRelationshipProductList\Business\ProductList\ProductListReaderInterface;

class CustomerExpander implements CustomerExpanderInterface
{
    protected const TYPE_WHITELIST = 'whitelist';

    /**
     * @var \Spryker\Zed\MerchantRelationshipProductList\Business\ProductList\ProductListReaderInterface
     */
    protected $productListReader;

    /**
     * @param \Spryker\Zed\MerchantRelationshipProductList\Business\ProductList\ProductListReaderInterface $productListReader
     */
    public function __construct(
        ProductListReaderInterface $productListReader
    ) {
        $this->productListReader = $productListReader;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expandCustomerTransferWithProductList(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        if ($customerTransfer->getCompanyUserTransfer() !== null
            && $customerTransfer->getCompanyUserTransfer()->getCompanyBusinessUnit() !== null
            && $customerTransfer->getCompanyUserTransfer()->getCompanyBusinessUnit()->getIdCompanyBusinessUnit()
        ) {
            $customerTransfer->setCustomerProductListCollection(new CustomerProductListCollectionTransfer());
            $productListCollectionTransfer = $this->productListReader->getProductListCollectionByIdCompanyBusinessUnit(
                $customerTransfer->getCompanyUserTransfer()->getCompanyBusinessUnit()
            );

            $this->addProductListsToCustomerTransfer($customerTransfer, $productListCollectionTransfer);
        }

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\ProductListCollectionTransfer $productListCollectionTransfer
     *
     * @return void
     */
    protected function addProductListsToCustomerTransfer(
        CustomerTransfer $customerTransfer,
        ProductListCollectionTransfer $productListCollectionTransfer
    ): void {
        foreach ($productListCollectionTransfer->getProductLists() as $productListTransfer) {
            if ($productListTransfer->getType() === static::TYPE_WHITELIST) {
                $customerTransfer->getCustomerProductListCollection()->addWhitelist($productListTransfer);
                continue;
            }

            $customerTransfer->getCustomerProductListCollection()->addBlacklist($productListTransfer);
        }
    }
}
