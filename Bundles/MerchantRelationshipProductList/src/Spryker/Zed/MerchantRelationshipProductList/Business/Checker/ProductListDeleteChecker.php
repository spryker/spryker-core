<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductList\Business\Checker;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\MerchantRelationshipProductList\Business\Reader\MerchantRelationshipReaderInterface;

class ProductListDeleteChecker implements ProductListDeleteCheckerInterface
{
    protected const ERROR_MESSAGE_UNABLE_TO_DELETE_PRODUCT_LIST = 'Unable to delete Product List since it\'s used by Merchant Relation "%merchant_relation%".';
    protected const ERROR_MESSAGE_PARAM_MERCHANT_RELATION = '%merchant_relation%';

    /**
     * @var \Spryker\Zed\MerchantRelationshipProductList\Business\Reader\MerchantRelationshipReaderInterface
     */
    protected $merchantRelationshipReader;

    /**
     * @param \Spryker\Zed\MerchantRelationshipProductList\Business\Reader\MerchantRelationshipReaderInterface $merchantRelationshipReader
     */
    public function __construct(MerchantRelationshipReaderInterface $merchantRelationshipReader)
    {
        $this->merchantRelationshipReader = $merchantRelationshipReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function isProductListDeletable(ProductListTransfer $productListTransfer): ProductListResponseTransfer
    {
        $productListResponseTransfer = (new ProductListResponseTransfer())
            ->setProductList($productListTransfer)
            ->setIsSuccessful(true);

        $merchantRelationshipTransfers = $this->merchantRelationshipReader->getMerchantRelationshipsByProductList($productListTransfer);

        if (!$merchantRelationshipTransfers) {
            return $productListResponseTransfer;
        }

        $productListResponseTransfer = $this->expandProductListResponseWithMessages(
            $productListResponseTransfer,
            $merchantRelationshipTransfers
        );

        return $productListResponseTransfer->setIsSuccessful(false);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer[] $merchantRelationshipTransfers
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function expandProductListResponseWithMessages(
        ProductListResponseTransfer $productListResponseTransfer,
        array $merchantRelationshipTransfers
    ): ProductListResponseTransfer {
        foreach ($merchantRelationshipTransfers as $merchantRelationshipTransfer) {
            $productListResponseTransfer->addMessage(
                (new MessageTransfer())->setValue(static::ERROR_MESSAGE_UNABLE_TO_DELETE_PRODUCT_LIST)
                    ->setParameters([
                        static::ERROR_MESSAGE_PARAM_MERCHANT_RELATION => $merchantRelationshipTransfer->getName(),
                    ])
            );
        }

        return $productListResponseTransfer;
    }
}
