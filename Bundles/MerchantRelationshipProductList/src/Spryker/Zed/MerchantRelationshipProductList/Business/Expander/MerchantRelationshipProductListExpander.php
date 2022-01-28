<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductList\Business\Expander;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\ProductListCollectionTransfer;
use Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListRepositoryInterface;

class MerchantRelationshipProductListExpander implements MerchantRelationshipProductListExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListRepositoryInterface
     */
    protected $merchantRelationshipProductListRepository;

    /**
     * @param \Spryker\Zed\MerchantRelationshipProductList\Persistence\MerchantRelationshipProductListRepositoryInterface $merchantRelationshipProductListRepository
     */
    public function __construct(MerchantRelationshipProductListRepositoryInterface $merchantRelationshipProductListRepository)
    {
        $this->merchantRelationshipProductListRepository = $merchantRelationshipProductListRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function expandMerchantRelationship(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        $productListCollectionTransfer = $this->merchantRelationshipProductListRepository->findProductListCollectionByIdMerchantRelationship(
            $merchantRelationshipTransfer->getIdMerchantRelationshipOrFail(),
        );
        $productListIds = $this->extractProductListIdsFromProductListCollectionTransfer($productListCollectionTransfer);

        return $merchantRelationshipTransfer
            ->setProductLists($productListCollectionTransfer->getProductLists())
            ->setProductListIds($productListIds);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListCollectionTransfer $productListCollectionTransfer
     *
     * @return array<int>
     */
    protected function extractProductListIdsFromProductListCollectionTransfer(ProductListCollectionTransfer $productListCollectionTransfer): array
    {
        $productListIds = [];
        foreach ($productListCollectionTransfer->getProductLists() as $productListTransfer) {
            $productListIds[] = $productListTransfer->getIdProductListOrFail();
        }

        return $productListIds;
    }
}
